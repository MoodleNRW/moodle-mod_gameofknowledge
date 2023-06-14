<?php

namespace mod_gameofknowledge;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/questionlib.php');
use \question_display_options;

class questions {
    /** @var \question_usage_by_activity */
    private $quba;

    /**
     * Create new questions object.
     *
     * Users playing together should have the same seed (users will see the questions in the same order).
     *
     * @param int $questioncategoryid
     * @param \context $context
     * @param int $seed
     * @return questions
     */
    public static function create(int $questioncategoryid, \context $context, int $seed) : questions {
        $obj = new questions();

        $obj->quba = \question_engine::make_questions_usage_by_activity('mod_gameofknowledge', $context);
        $obj->quba->set_preferred_behaviour('interactive');
        $obj->load_questions($questioncategoryid, $seed);
        $obj->quba->start_all_questions();
        \question_engine::save_questions_usage_by_activity($obj->quba);

        return $obj;
    }

    public static function load(int $questionusageid) : questions {
        $obj = new questions();
        $obj->quba = \question_engine::load_questions_usage_by_activity($questionusageid);
        return $obj;
    }

    private function load_questions(int $questioncategoryid, int $seed) {
        global $DB;

        $questionids = $DB->get_fieldset_sql('
            SELECT q.id
            FROM {question} q
            JOIN {question_versions} qv on q.id = qv.questionid
            JOIN {question_bank_entries} qbe on qv.questionbankentryid = qbe.id
            JOIN (
                SELECT
                    questionbankentryid,
                    max(version) as current_version
                FROM
                    {question_versions}
                GROUP BY
                    questionbankentryid
            ) mqv on qv.questionbankentryid = mqv.questionbankentryid
            WHERE
                qbe.questioncategoryid = ?
                AND qv.version = mqv.current_version', [$questioncategoryid]);

        $questions = question_load_questions($questionids);

        srand($seed);
        $randquestions = [];
        $questionbyid = [];
        foreach ($questions as $questionid => $question) {
            $randquestions[$questionid] = rand();
            $questionbyid[$questionid] = $question;
        }
        asort($randquestions);

        foreach ($randquestions as $questionid => $random) {
            $question = \question_bank::make_question($questionbyid[$questionid]);
            $this->quba->add_question($question);
        }
    }

    public function get_questionusageid() : int {
        return $this->quba->get_id();
    }

    public function question_count() : int {
        return $this->quba->question_count();
    }

    /**
     * Get question html and javascript code.
     *
     * @param int $slot 1-based
     * @return string[] (html, javascript)
     */
    public function get_question(int $slot) {
        $options = self::get_question_display_options();
        $html = $this->quba->render_question($slot, $options, null);
        return [$html, ''];
    }

    /**
     * @param int $slot
     * @param array $submitteddata
     * @return float|null null if answer is invalid and could not be marked (yet)
     */
    public function process_answer_and_get_mark(int $slot, array $submitteddata) {
        global $DB;

        $transaction = $DB->start_delegated_transaction();

        $submitteddata = $this->quba->extract_responses($slot, $submitteddata);
        $submitteddata['-submit'] = 1; // Always submit submission.
        $this->quba->process_action($slot, $submitteddata);
        \question_engine::save_questions_usage_by_activity($this->quba);

        $transaction->allow_commit();
        return $this->quba->get_question_mark($slot);
    }

    public static function get_question_display_options() : \question_display_options {
        $options = new question_display_options();
        $options->marks = question_display_options::MARK_AND_MAX;
        $options->markdp = 2; // Display marks to 2 decimal places.
        $options->flags = question_display_options::HIDDEN;
        $options->feedback = question_display_options::HIDDEN;
        $options->numpartscorrect = question_display_options::HIDDEN;
        $options->generalfeedback = question_display_options::HIDDEN;
        $options->rightanswer = question_display_options::HIDDEN;
        $options->manualcomment = question_display_options::HIDDEN;
        $options->history = question_display_options::HIDDEN;
        return $options;
    }
}
