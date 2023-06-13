<?php

namespace mod_gameofknowledge\game;

use mod_gameofknowledge\state_based_game;

defined('MOODLE_INTERNAL') || die();

public class game_of_knowledge extends state_based_game {

    protected function init_new_game(\stdClass $settings)
    {
        // TODO: Implement init_new_game() method.
    }

    protected function init_player(int $player)
    {
        // TODO: Implement init_player() method.
    }

    protected function parse_state(array $state)
    {
        // TODO: Implement parse_state() method.
    }

    public function get_global_state(): array
    {
        // TODO: Implement get_global_state() method.
    }

    public function perform_action(int $player, string $action)
    {
        // TODO: Implement perform_action() method.
    }
}
