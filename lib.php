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
 * Library of functions for mod_gameofknowledge.
 *
 * @package    mod_gameofknowledge
 * @copyright  2019 Martin Gauk, innoCampus, TU Berlin
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Saves a new gameofknowledge instance into the database.
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $gameofknowledge an object from the form in mod_form.php
 * @return int the id of the newly inserted record
 */
function gameofknowledge_add_instance($gameofknowledge) {
    global $DB;

    $gameofknowledge->timecreated = time();
    $gameofknowledge->timemodified = time();

    $id = $DB->insert_record('gameofknowledge', $gameofknowledge);
    return $id;
}

/**
 * Updates a gameofknowledge instance.
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $gameofknowledge an object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function gameofknowledge_update_instance($gameofknowledge) {
    global $DB;

    $gameofknowledge->timemodified = time();
    $gameofknowledge->id = $gameofknowledge->instance;

    $ret = $DB->update_record('gameofknowledge', $gameofknowledge);
    return $ret;
}

/**
 * Removes a gameofknowledge instance from the database.
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id ID of the module instance.
 * @return boolean Success/Failure
 */
function gameofknowledge_delete_instance($id) {
    global $DB;

    // Check if an instance with this id exists.
    if (!$gameofknowledgeinstance = $DB->get_record('gameofknowledge', array('id' => $id))) {
        return false;
    }

    $DB->delete_records('gameofknowledge', ['id' => $id]);
    $DB->delete_records('gameofknowledge_rooms', ['gameofknowledgeid' => $id]);
    return true;
}

/**
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool True if feature is supported
 */
function gameofknowledge_supports($feature) {
    switch ($feature) {
        case FEATURE_GROUPS:
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;

        default:
            return null;
    }
}

/**
 * View or submit an mform.
 *
 * Returns the HTML to view an mform.
 * If form data is delivered and the data is valid, this returns 'ok'.
 *
 * @param $args
 * @return string
 * @throws moodle_exception
 */
function mod_gameofknowledge_output_fragment_mform($args) {
    $context = $args['context'];
    if ($context->contextlevel != CONTEXT_MODULE) {
        throw new \moodle_exception('fragment_mform_wrong_context', 'gameofknowledge');
    }

    list($course, $coursemodule) = get_course_and_cm_from_cmid($context->instanceid, 'gameofknowledge');
    $gameofknowledge = new \mod_gameofknowledge\gameofknowledge($coursemodule);

    $formdata = [];
    if (!empty($args['jsonformdata'])) {
        $serialiseddata = json_decode($args['jsonformdata']);
        if (is_string($serialiseddata)) {
            parse_str($serialiseddata, $formdata);
        }
    }

    $moreargs = (isset($args['moreargs'])) ? json_decode($args['moreargs']) : new stdClass;
    $formname = $args['form'] ?? '';

    $form = \mod_gameofknowledge\form\form_controller::get_controller($formname, $gameofknowledge, $formdata, $moreargs);

    if ($form->success()) {
        $ret = 'ok';
        if ($msg = $form->get_message()) {
            $ret .= ' ' . $msg;
        }
        return $ret;
    } else {
        return $form->render();
    }
}
