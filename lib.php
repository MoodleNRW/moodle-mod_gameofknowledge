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
    $gameofknowledge->questioncategoryid = explode(',', $gameofknowledge->questioncategory)[0];

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
    $gameofknowledge->questioncategoryid = explode(',', $gameofknowledge->questioncategory)[0];

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
    $DB->delete_records('gameofknowledge_games', ['gameofknowledgeid' => $id]);
    $DB->delete_records('gameofknowledge_players', ['gameofknowledgeid' => $id]);
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
        case FEATURE_MOD_PURPOSE:
            return MOD_PURPOSE_ASSESSMENT;
        default:
            return null;
    }
}

/**
 * Called via pluginfile.php -> question_pluginfile to serve files belonging to
 * a question in a question_attempt.
 *
 * Mostly copied from moodle-filter_embedquestion.
 *
 * @category files
 * @param stdClass $givencourse course settings object
 * @param stdClass $context context object
 * @param string $component the name of the component we are serving files for.
 * @param string $filearea the name of the file area.
 * @param int $qubaid the question_usage this image belongs to.
 * @param int $slot the relevant slot within the usage.
 * @param array $args the remaining bits of the file path.
 * @param bool $forcedownload whether the user must be forced to download the file.
 * @param array $fileoptions additional options affecting the file serving
 */
function mod_gameofknowledge_question_pluginfile($givencourse, $context, $component,
        $filearea, $qubaid, $slot, $args, $forcedownload, $fileoptions) {

    list($context, $course, $cm) = get_context_info_array($context->id);
    if ($givencourse->id !== $course->id) {
        send_file_not_found();
    }
    require_login($course, false, $cm);

    $quba = question_engine::load_questions_usage_by_activity($qubaid);

    // TODO Check if this $quba belongs to the current user.
    if ($quba->get_owning_component() != 'mod_gameofknowledge') {
        send_file_not_found();
    }

    $options = \mod_gameofknowledge\questions::get_question_display_options();
    if (!$quba->check_file_access($slot, $options, $component,
        $filearea, $args, $forcedownload)) {
        send_file_not_found();
    }

    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/{$context->id}/{$component}/{$filearea}/{$relativepath}";
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        send_file_not_found();
    }

    send_stored_file($file, 0, 0, $forcedownload, $fileoptions);
}
