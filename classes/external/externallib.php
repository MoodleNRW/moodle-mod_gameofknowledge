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

    public static function get_state_parameters() {
        return new external_function_parameters([
            'coursemoduleid' => new external_value(PARAM_INT, 'course module id'),
        ]);
    }

    public static function perform_action_parameters() {
        return new external_function_parameters([
            'coursemoduleid' => new external_value(PARAM_INT, 'course module id'),
            'action' => new external_value(PARAM_RAW, 'action to be done')
        ]);
    }

    public static function get_state_returns() {
        return new external_value(PARAM_RAW, 'response');
    }

    public static function perform_action_returns() {
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

        $state = $game->get_player_state($player->number);

        return json_encode($state, JSON_THROW_ON_ERROR);
    }
}
