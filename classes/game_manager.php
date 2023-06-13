<?php

namespace mod_gameofknowledge;

defined('MOODLE_INTERNAL') || die();

class game_manager {

    /** @var int */
    private $instance;

    /** @var \stdClass */
    private $coursemodule;

    /** @var \context_module */
    private $context;

    /** @var \stdClass|null  */
    private $settings = null;

    private $games = [];

    public function __construct(\cm_info $coursemodule) {
        if ($coursemodule->modname !== 'gameofknowledge') {
            throw new \moodle_exception('wrong_cm_info_given', 'gameofknowledge');
        }
        $this->instance = $coursemodule->instance;
        $this->coursemodule = $coursemodule;
        $this->context = $coursemodule->context;
    }

    /**
     * Get gameofknowledge instance id.
     *
     * @return int
     */
    public function get_id() : int {
        return $this->instance;
    }

    public function get_context() : \context_module {
        return $this->context;
    }

    public function get_coursemodule() {
        return $this->coursemodule;
    }

    public function get_settings() {
        if ($this->settings === null) {
            global $DB;
            $settings = $DB->get_record('gameofknowledge', ['id' => $this->instance], '*', MUST_EXIST);

            $this->settings = new \stdClass();
            $this->settings->name = $settings->name;
        }

        return $this->settings;
    }

    public function create_game(): state_based_game {
        $type = 'game_of_knowledge';
        $settings = $this->get_settings();

        return state_based_game::create_game($type, $settings);
    }

    public function get_game(int $gameid): state_based_game {
        if (array_key_exists($gameid, $this->games)) {
            return $this->games[$gameid];
        }
        $game = state_based_game::load_game_by_id($gameid);
        $this->games[$gameid] = $game;
        return $game;
    }

    public function get_open_gameid(): ?int {
        global $DB;

        $gameid = $DB->get_field_select('gameofknowledge_games', 'id',
            'gameofknowledgeid = :gameofknowledgeid AND status = :status AND players < maxplayers',
        [
            'gameofknowledgeid' => $this->instance,
            'status' => state_based_game::INITIALIZING,
        ]);
        if ($gameid === false) {
            return null;
        }
        return $gameid;
    }

    public function join_game(int $gameid, int $userid = null): int {
        global $DB, $USER;
        if (is_null($userid)) {
            $userid = $USER->id;
        }

        $game = $this->get_game($gameid);

        $transaction = $DB->start_delegated_transaction();

        $player = $game->add_player();
        $game->save_game();

        $record = new \stdClass();
        $record->userid = $userid;
        $record->gameofknowledgeid = $this->instance;
        $record->gameid = $game->get_id();
        $record->number = $player;

        $DB->insert_record('gameofknowledge_players', $record);

        $DB->commit_delegated_transaction($transaction);

        return $player;
    }

    public function get_current_player_record(int $userid = null): ?\stdClass {
        global $DB, $USER;
        if (is_null($userid)) {
            $userid = $USER->id;
        }

        $record = $DB->get_record('gameofknowledge_players', ['gameofknowledgeid' => $this->instance, 'userid' => $userid]);
        if (!$record) {
            return null;
        }

        return $record;
    }

    public function get_current_game(int $userid = null): ?state_based_game {
        $record = $this->get_current_player_record($userid);
        if (!$record) {
            return null;
        }
        return $this->get_game($record->gameid);
    }
}
