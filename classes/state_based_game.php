<?php

namespace mod_gameofknowledge;

defined('MOODLE_INTERNAL') || die();

abstract class state_based_game {

    protected const GAME_TYPE_BASEPATH = '\mod_gameofknowledge\state_based_game\\';

    public const INITIALIZING = 0;
    public const RUNNING = 1;
    public const FINISHED = 2;

    /** @var int Game ID. */
    protected $id;
    /** @var string Game type. */
    protected $type;
    /** @var int Game status. */
    protected $status = self::INITIALIZING;
    /** @var int Player count. */
    protected $players = 0;
    /** @var int Max player count. */
    protected $maxplayers = 1;

    /**
     * @deprecated Use {@see self::create_game()} or {@see self::load_game_by_id()}.
     */
    protected function __construct() {}

    /**
     * @return int
     */
    public function get_id(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function get_type(): string {
        return $this->type;
    }

    /**
     * @return int
     */
    public function get_status(): int {
        return $this->status;
    }

    /**
     * @return int
     */
    public function get_players(): int {
        return $this->players;
    }

    /**
     * @return int
     */
    public function get_maxplayers(): int {
        return $this->maxplayers;
    }

    /**
     * Adds a player to this game and performs any required updates to the state.
     *
     * @return int The new player number
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

    /**
     * Initializes a new game. This must initialize the state based on the settings object that was passed.
     *
     * @param \stdClass $settings
     */
    protected abstract function init_new_game(\stdClass $settings);

    /**
     * Initializes a new player. This must update the state to include the new player.
     *
     * @param int $player The player number
     */
    protected abstract function init_player(int $player);

    /**
     * Parses an array representation of the game state when the game is loaded from DB.
     *
     * @param array $state The game state as returned by {@see self::get_global_state()}
     */
    protected abstract function parse_state(array $state);

    /**
     * Returns an array representation of the game state to be stored in DB.
     *
     * @return array The game state.
     */
    public abstract function get_global_state(): array;

    /**
     * Returns an array representation of the game state to be transferred to a player. This may return only part of
     * the state if the player is not supposed to see the entire state.
     *
     * @param int $player The player number.
     * @return array The (partial) game state.
     */
    public function get_player_state(int $player): array {
        return $this->get_global_state();
    }

    /**
     * Performs an action on behalf of a player. Throws an exception if the action is illegal (e.g., if it's not the
     * player's turn).
     *
     * @param int $player The player number.
     * @param string $action The action represented as a string.
     */
    public abstract function perform_action(int $player, string $action);

    /**
     * Construct a new game object by type.
     *
     * @param string $type The game type.
     * @return state_based_game
     * @throws \coding_exception
     * @throws game_exception
     */
    protected static function construct_game(string $type) {
        $type = clean_param($type, PARAM_ALPHANUMEXT);
        $classname = self::GAME_TYPE_BASEPATH . $type;
        if (!class_exists($classname)) {
            throw new game_exception('unknowngametype');
        }
        if (!is_subclass_of($classname, '\mod_gameofknowledge\state_based_game')) {
            throw new game_exception('invalidgametype');
        }
        return new $classname();
    }

    /**
     * Create a new game with given type and settings.
     *
     * @param string $type The game type.
     * @param \stdClass $settings The game settings.
     * @return state_based_game
     * @throws \coding_exception
     * @throws game_exception
     */
    public static function create_game(string $type, \stdClass $settings): state_based_game {
        $game = self::construct_game($type);
        $game->init_new_game($settings);
        return $game;
    }

    /**
     * Load game from a DB record.
     *
     * @param \stdClass $record The DB record.
     * @return state_based_game The game object.
     * @throws \JsonException
     * @throws \coding_exception
     * @throws game_exception
     */
    public static function load_game(\stdClass $record): state_based_game {
        $game = self::construct_game($record->type);
        $game->load_from_record($record);
        return $game;
    }

    /**
     * Load game from the DB.
     *
     * @param int $gameid The game ID.
     * @return state_based_game The game object.
     * @throws \dml_exception
     * @throws game_exception
     */
    public static function load_game_by_id(int $gameid): state_based_game {
        global $DB;

        $record = $DB->get_record('gameofknowledge_games', ['id' => $gameid]);
        if (!$record) {
            throw new game_exception('unknowngame');
        }

        return self::load_game($record);
    }

    /**
     * Saves the game state in the DB.
     *
     * @throws \JsonException
     * @throws \dml_exception
     * @throws game_exception
     */
    public function save_game() {
        global $DB;
        $record = $this->save_to_record();

        if (isset($record->id)) {
            $DB->update_record('gameofknowledge_games', $record);
        } else {
            $this->id = $DB->insert_record('gameofknowledge_games', $record);
        }
    }

    /**
     * Load the state of this game object from a DB record.
     *
     * @param \stdClass $record The DB record.
     * @throws \JsonException
     */
    protected function load_from_record(\stdClass $record) {
        $this->id = $record->id;
        $this->type = $record->type;
        $this->status = $record->status;
        $this->players = $record->players;
        $this->maxplayers = $record->maxplayers;
        $state = json_decode($record->state, true, 255, JSON_THROW_ON_ERROR);
        $this->parse_state($state);
    }

    /**
     * Save the state of this game object to a DB record.
     *
     * @return \stdClass The DB record.
     * @throws \JsonException
     * @throws game_exception
     */
    protected function save_to_record(): \stdClass {
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

}
