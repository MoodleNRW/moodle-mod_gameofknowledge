<?php

namespace mod_gameofknowledge\external;

use external_function_parameters;
use external_multiple_structure;
use external_value;
use external_single_structure;

defined('MOODLE_INTERNAL') || die();

class externallib extends \external_api {

    public static function get_state_parameters() {
        return new external_function_parameters([
            'coursemoduleid' => new external_value(PARAM_INT, 'course module id'),
        ]);
    }

    public static function perform_move_parameters() {
        return new external_function_parameters([
            'coursemoduleid' => new external_value(PARAM_INT, 'course module id'),
            'action' => new external_value(PARAM_RAW, 'action to be done')
        ]);
    }

    public static function get_state_returns() {
        return new external_value(PARAM_RAW, 'response');
    }

    public static function perform_move_returns() {
        return new external_value(PARAM_RAW, 'response');
    }

    /**
     * @param $coursemoduleid
     * @return bool
     * @throws \invalid_parameter_exception
     */
    public static function get_state($coursemoduleid) {
        $params = self::validate_parameters(self::get_rooms_parameters(), ['coursemoduleid' => $coursemoduleid]);

        list($course, $coursemodule) = get_course_and_cm_from_cmid($params['coursemoduleid'], 'gameofknowledge');
        self::validate_context($coursemodule->context);

        global $PAGE, $DB;
        $renderer = $PAGE->get_renderer('core');
        $ctx = $coursemodule->context;

        $list = [];
        $rooms = $DB->get_records('gameofknowledge_rooms', ['gameofknowledgeid' => $coursemodule->instance]);
        foreach ($rooms as $room) {
            $exporter = new exporter\room($room, $ctx);
            $list[] = $exporter->export($renderer);
        }

        return $list;
    }

    /**
     * @param $coursemoduleid
     * @param $action
     * @return void
     * @throws \core_external\restricted_context_exception
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     */
    public static function perform_move($coursemoduleid, $action) {
        $params = self::validate_parameters(self::get_rooms_parameters(), ['coursemoduleid' => $coursemoduleid, 'action' => $action]);

        list($course, $coursemodule) = get_course_and_cm_from_cmid($params['coursemoduleid'], 'gameofknowledge');
        self::validate_context($coursemodule->context);
    }
}
