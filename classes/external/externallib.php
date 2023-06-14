<?php

namespace mod_gameofknowledge\external;

use external_function_parameters;
use external_multiple_structure;
use external_value;
use external_single_structure;
use mod_gameofknowledge\game_exception;
use mod_gameofknowledge\game_manager;

defined('MOODLE_INTERNAL') || die();

class externallib extends \external_api {

    public static function start_game_parameters() {
        return new external_function_parameters([
            'coursemoduleid' => new external_value(PARAM_INT, 'course module id'),
        ]);
    }

    public static function start_game_returns() {
        return new external_value(PARAM_RAW, 'response');
    }

    /**
     * @param $coursemoduleid
     * @return bool
     * @throws \invalid_parameter_exception
     */
    public static function start_game($coursemoduleid) {
        $params = self::validate_parameters(self::get_state_parameters(), ['coursemoduleid' => $coursemoduleid]);

        list($course, $coursemodule) = get_course_and_cm_from_cmid($params['coursemoduleid'], 'gameofknowledge');
        self::validate_context($coursemodule->context);

        $manager = new game_manager($coursemodule);

        $game = $manager->get_current_game();
        if ($game) {
            $manager->end_game($game->get_id());
        }

        $gameid = $manager->get_open_gameid();
        if (is_null($gameid)) {
            $gameid = $manager->start_new_game()->get_id();
        }

        $player = $manager->join_game($gameid);

        $game = $manager->get_game($gameid);
        $state = $game->get_player_state($player);

        return json_encode($state, JSON_THROW_ON_ERROR);
    }

    public static function end_game_parameters() {
        return new external_function_parameters([
            'coursemoduleid' => new external_value(PARAM_INT, 'course module id'),
        ]);
    }

    public static function end_game_returns() {
        return new external_value(PARAM_RAW, 'response');
    }

    /**
     * @param $coursemoduleid
     * @return bool
     * @throws \invalid_parameter_exception
     */
    public static function end_game($coursemoduleid) {
        $params = self::validate_parameters(self::get_state_parameters(), ['coursemoduleid' => $coursemoduleid]);

        list($course, $coursemodule) = get_course_and_cm_from_cmid($params['coursemoduleid'], 'gameofknowledge');
        self::validate_context($coursemodule->context);

        $manager = new game_manager($coursemodule);

        $game = $manager->get_current_game();
        if ($game) {
            $manager->end_game($game->get_id());
        }

        return 'ok';
    }

    public static function get_state_parameters() {
        return new external_function_parameters([
            'coursemoduleid' => new external_value(PARAM_INT, 'course module id'),
        ]);
    }

    public static function get_state_returns() {
        return new external_value(PARAM_RAW, 'response');
    }

    /**
     * @param $coursemoduleid
     * @return bool
     * @throws \invalid_parameter_exception
     */
    public static function get_state($coursemoduleid) {
        $params = self::validate_parameters(self::get_state_parameters(), ['coursemoduleid' => $coursemoduleid]);

        list($course, $coursemodule) = get_course_and_cm_from_cmid($params['coursemoduleid'], 'gameofknowledge');
        self::validate_context($coursemodule->context);

        $manager = new game_manager($coursemodule);

        $player = $manager->get_current_player_record();
        if (!$player) {
            throw new game_exception('notingame');
        }

        $game = $manager->get_game($player->gameid);

        $state = $game->get_player_state($player->number);

        return json_encode($state, JSON_THROW_ON_ERROR);
    }

    public static function perform_action_parameters() {
        return new external_function_parameters([
            'coursemoduleid' => new external_value(PARAM_INT, 'course module id'),
            'action' => new external_value(PARAM_RAW, 'action to be done')
        ]);
    }

    public static function perform_action_returns() {
        return new external_value(PARAM_RAW, 'response');
    }

    /**
     * @param $coursemoduleid
     * @param $action
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     */
    public static function perform_action($coursemoduleid, $action) {
        $params = self::validate_parameters(self::perform_action_parameters(), ['coursemoduleid' => $coursemoduleid, 'action' => $action]);

        list($course, $coursemodule) = get_course_and_cm_from_cmid($params['coursemoduleid'], 'gameofknowledge');
        self::validate_context($coursemodule->context);

        $manager = new game_manager($coursemodule);

        $player = $manager->get_current_player_record();
        if (!$player) {
            throw new game_exception('notingame');
        }

        $game = $manager->get_game($player->gameid);

        $game->perform_action($player->number, $params['action']);
        $game->save_game();

        $state = $game->get_player_state($player->number);

        return json_encode($state, JSON_THROW_ON_ERROR);
    }
}
