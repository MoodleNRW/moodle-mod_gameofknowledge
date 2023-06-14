<?php

namespace mod_gameofknowledge;

defined('MOODLE_INTERNAL') || die();

class game_exception extends \moodle_exception {

    public function __construct($errorcode) {
        parent::__construct($errorcode, 'gameofknowledge');
    }
}
