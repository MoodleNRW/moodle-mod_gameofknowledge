<?php

namespace mod_gameofknowledge\game;

use mod_gameofknowledge\state_based_game;
use mod_gameofknowledge\state;

defined('MOODLE_INTERNAL') || die();

public class game_of_knowledge extends state_based_game {

    private $tiles;
    private $actviteplayer;
    private $playerlist;

    protected function init_new_game(\stdClass $settings)
    {
        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $this->tiles[$i][$j] = ['question' => 'questiontext', 'answer' => '1'];
            }
        }
    }

    protected function init_player(int $player)
    {
        $this->playerlist[$player] = ['position' => '0/0'];
    }

    protected function parse_state(array $state)
    {
        $this->tiles = $state->tiles;
        $this->activeplayer = $state->activeplayer;
        $this->playerlist = $state->playerlist;
    }

    public function get_global_state(): array
    {
        return ['tiles' => $this->tiles, 'activeplayer' => $this->actviteplayer, 'playerlist' => $this->playerlist];
    }
    public function get_player_state(int $player): array
    {
        return parent::get_player_state($player); // TODO: Change the autogenerated stub
    }

    public function perform_action(int $player, string $action)
    {
        $request = json_decode($action, true, 255, JSON_THROW_ON_ERROR);
        $coordinates = explode('/', $request->position);
        if (!($coordinates[0] <= count($this->tiles[0]) && $coordinates[1] <= count($this->tiles))) {
            return;
        }
        if ($request->position == $this->playerlist[$player] && $this->activeplayer != $player) {
            return;
        }
    }
}