<?php

$functions = array(
    'mod_gameofknowledge_get_rooms' => array(
        'classname' => 'mod_gameofknowledge\external\externallib',
        'methodname' => 'get_state',
        'description' => 'Get the current game state.',
        'type' => 'read',
        'ajax' => true,
    ),
    'mod_gameofknowledge_get_rooms' => array(
        'classname' => 'mod_gameofknowledge\external\externallib',
        'methodname' => 'perform_action',
        'description' => 'Input a new move.',
        'type' => 'write',
        'ajax' => true,
    )
);
