<?php

$functions = array(
    'mod_gameofknowledge_start_game' => array(
        'classname' => 'mod_gameofknowledge\external\externallib',
        'methodname' => 'start_game',
        'description' => 'Start a new game.',
        'type' => 'write',
        'ajax' => true,
    ),
    'mod_gameofknowledge_end_game' => array(
        'classname' => 'mod_gameofknowledge\external\externallib',
        'methodname' => 'end_game',
        'description' => 'End a new game.',
        'type' => 'write',
        'ajax' => true,
    ),
    'mod_gameofknowledge_get_state' => array(
        'classname' => 'mod_gameofknowledge\external\externallib',
        'methodname' => 'get_state',
        'description' => 'Get the current game state.',
        'type' => 'read',
        'ajax' => true,
    ),
    'mod_gameofknowledge_perform_action' => array(
        'classname' => 'mod_gameofknowledge\external\externallib',
        'methodname' => 'perform_action',
        'description' => 'Input a new action.',
        'type' => 'write',
        'ajax' => true,
    )
);
