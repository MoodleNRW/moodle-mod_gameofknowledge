<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The main gameofknowledge configuration form
 *
 * @package    mod_gameofknowledge
 */

use mod_gameofknowledge\game\game_of_knowledge;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_gameofknowledge_mod_form extends moodleform_mod {
    /**
     * Defines forms elements.
     */
    public function definition() {
        global $CFG, $PAGE;
        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', 'Title', array(
                'size' => '64'
        ));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Adding the standard "intro" and "introformat" fields.
        $this->standard_intro_elements();

        $defaultlayout = implode("\n", array_map('trim', explode("\n", trim(game_of_knowledge::DEFAULT_LAYOUT))));
        $mform->addElement('textarea', 'gamelayout', get_string('gamelayout', 'gameofknowledge'), 'rows="10"');
        $mform->setDefault('gamelayout', $defaultlayout);

        $mform->addElement('questioncategory', 'questioncategory', get_string('category', 'question'),
            ['contexts' => [$this->context, $this->context->get_course_context()], 'top'=>true]);
        $mform->addRule('questioncategory', null, 'required', null, 'client');

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard action buttons, common to all modules.
        $this->add_action_buttons();
    }

    /**
     * Checks that accesstimestart is before accesstimestop
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        return $errors;
    }

    public function set_data($data) {
        global $DB;

        if (isset($data->questioncategoryid)) {
            $cid = $DB->get_field('question_categories', 'contextid', ['id' => $data->questioncategoryid]);
            $data->questioncategory = $data->questioncategoryid . ',' . $cid;
        }
        parent::set_data($data);
    }
}
