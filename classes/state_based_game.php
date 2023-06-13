<?php

namespace mod_gameofknowledge;

defined('MOODLE_INTERNAL') || die();

abstract class state_based_game {

    protected const GAME_TYPE_BASEPATH = '\mod_gameofknowledge\state_based_game\\';

    public const INITIALIZING = 0;
    public const RUNNING = 1;
    public const FINISHED = 2;

    protected $id;
    protected $type;
    protected $status = self::INITIALIZING;
    protected $players = 0;
    protected $maxplayers = 1;

    protected function __construct() {}

    /**
     * Adds a player to this game and performs any required updates to the state.
     *
     * @return int player number
     * @throws game_exception
     */
    public function add_player(): int {
        if ($this->status != self::INITIALIZING) {
            throw new game_exception('cannotjoinrunninggame');
        }
        if ($this->players >= $this->maxplayers) {
            throw new game_exception('cannotjoinfullgame');
        }
        $player = $this->players++;
        $this->init_player($player);
        return $player;
    }

    protected abstract function init_new_game(\stdClass $settings);

    protected abstract function init_player(int $player);

    protected abstract function parse_state(array $state);

    public abstract function get_global_state(): array;

    public function get_player_state(int $player): array {
        return $this->get_global_state();
    }

    public abstract function perform_action(int $player, string $action);

    public function save_game() {
        global $DB;
        $record = $this->save_to_record();

        if (isset($record->id)) {
            $DB->update_record('gameofknowledge_games', $record);
        } else {
            $this->id = $DB->insert_record('gameofknowledge_games', $record);
        }
    }

    public static function load_game_by_id(int $gameid): state_based_game {
        global $DB;

        $record = $DB->get_record('gameofknowledge_games', ['id' => $gameid]);
        if (!$record) {
            throw new game_exception('unknowngame');
        }

        return self::load_game($record);
    }

    public static function load_game(\stdClass $record): state_based_game {
        $type = clean_param($record->type, PARAM_ALPHANUMEXT);
        $classname = self::GAME_TYPE_BASEPATH . $type;
        if (!class_exists($classname)) {
            throw new game_exception('unknowngametype');
        }
        if (!is_subclass_of($classname, '\mod_gameofknowledge\state_based_game')) {
            throw new game_exception('invalidgametype');
        }
        $game = new $classname();

        $game->load_from_record($record);

        return $game;
    }

    protected function save_to_record(): \stdClass {
        if (!$this->type) {
            $classname = get_class($this);
            if (!str_starts_with($classname, self::GAME_TYPE_BASEPATH)) {
                throw new game_exception('unknowngametype');
            }
            $this->type = substr($classname, strlen(self::GAME_TYPE_BASEPATH));
        }

        $record = new \stdClass();
        $record->id = $this->id;
        $record->type = $this->type;
        $record->status = $this->status;
        $record->players = $this->players;
        $record->maxplayers = $this->maxplayers;
        $state = json_encode($this->get_global_state(), JSON_THROW_ON_ERROR);
        $record->state = $state;
        return $record;
    }

    protected function load_from_record(\stdClass $record) {
        $this->id = $record->id;
        $this->type = $record->type;
        $this->status = $record->status;
        $this->players = $record->players;
        $this->maxplayers = $record->maxplayers;
        $state = json_decode($record->state, true, 255, JSON_THROW_ON_ERROR);
        $this->parse_state($state);
    }
}
