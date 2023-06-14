<?php

namespace mod_gameofknowledge\game;

defined('MOODLE_INTERNAL') || die();

use core\session\exception;
use mod_data\manager;
use mod_gameofknowledge\game_exception;
use mod_gameofknowledge\questions;
use mod_gameofknowledge\state_based_game;

class game_of_knowledge extends state_based_game {

    const DEFAULT_LAYOUT = '
        S Q Q # Q Q S
        Q _ _ Q _ _ Q
        Q _ _ Q _ _ Q
        # Q Q G Q Q #
        Q _ _ Q _ _ Q
        Q _ _ Q _ _ Q
        S Q Q # Q Q S
    ';

    const TYPE_NONE = 'none';
    const TYPE_EMPTY = 'empty';
    const TYPE_START = 'start';
    const TYPE_QUESTION = 'question';
    const TYPE_SOLVED = 'solved';
    const TYPE_GOAL = 'goal';

    private $tiles;
    private $activeplayer;
    private $playerlist;
    private $questioncategoryid;
    private $questioncount;
    private $seed;
    private $winner;

    protected function init_new_game(\stdClass $settings) {
        $this->seed = rand();
        $this->questioncategoryid = $settings->questioncategoryid;

        $layout = self::DEFAULT_LAYOUT;

        $this->maxplayers = 0;
        $this->tiles = [];
        $lines = explode("\n", trim($layout));
        $this->questioncount = 0;
        for ($i = 0; $i < sizeof($lines); $i++) {
            $line = str_replace([' ', "\t"], '', trim($lines[$i]));
            $this->tiles[$i] = [];
            for ($j = 0; $j < strlen($line); $j++) {
                switch ($line[$j]) {
                    case 'S':
                        $this->maxplayers++;
                        $tile = [
                            'type' => self::TYPE_START,
                            'questionindex' => null
                        ];
                        break;

                    case '#':
                        $tile = [
                            'type' => self::TYPE_EMPTY,
                            'questionindex' => null
                        ];
                        break;

                    case 'Q':
                        $tile = [
                            'type' => self::TYPE_QUESTION,
                            'questionindex' => $this->questioncount++
                        ];
                        break;

                    case 'G':
                        $tile = [
                            'type' => self::TYPE_GOAL,
                            'questionindex' => null
                        ];
                        break;

                    default:
                        $tile = [
                            'type' => self::TYPE_NONE,
                            'questionindex' => null
                        ];
                        break;
                }

                $this->tiles[$i][] = $tile;
            }
        }
    }

    protected function find_empty_start_position(): ?array {
        for ($i = 0; $i < sizeof($this->tiles); $i++) {
            for ($j = 0; $j < sizeof($this->tiles[$i]); $j++) {
                if ($this->tiles[$i][$j]['type'] == self::TYPE_START) {
                    $occupied = false;
                    foreach ($this->playerlist as $player) {
                        if ($player['i'] == $i && $player['j'] == $j) {
                            $occupied = true;
                            break;
                        }
                    }
                    if (!$occupied) {
                        return [$i, $j];
                    }
                }
            }
        }
        return null;
    }

    protected function init_player(int $player) {
        $position = $this->find_empty_start_position();
        if (!$position) {
            throw new game_exception('noemptystartposition');
        }

        $questions = questions::create($this->questioncategoryid, $this->manager->get_context(), $this->seed);
        if ($questions->question_count() < $this->questioncount) {
            throw new game_exception('notenoughquestions');
        }

        $this->playerlist[$player] = [
            'number' => $player,
            'i' => $position[0],
            'j' => $position[1],
            'questionusageid' => $questions->get_questionusageid(),
            'lastmark' => null,
        ];

        if ($this->players == $this->maxplayers) {
            $this->status = self::RUNNING;
        }
    }

    protected function parse_state(array $state) {
        $this->tiles = $state['tiles'];
        $this->activeplayer = $state['activeplayer'];
        $this->playerlist = $state['playerlist'];
        $this->questioncategoryid = $state['questioncategoryid'];
        $this->questioncount = $state['questioncount'];
        $this->seed = $state['seed'];
    }

    public function get_global_state(): array {
        return [
            'tiles' => $this->tiles,
            'activeplayer' => $this->activeplayer,
            'playerlist' => $this->playerlist,
            'questioncategoryid' => $this->questioncategoryid,
            'questioncount' => $this->questioncount,
            'seed' => $this->seed,
            'winner' => $this->winner
        ];
    }

    public function get_player_state(int $player): array {
        $questions = questions::load($this->playerlist[$player]['questionusageid']);
        $questionlist = [];
        for ($i = 0; $i < $this->questioncount; $i++) {
            list($html, $js) = $questions->get_question($i + 1);
            $questionlist[] = [
                'html' => $html,
                'js' => $js
            ];
        }

        $playerpositions = array_map(function($player) {
            return [
                'i' => $player['i'],
                'j' => $player['j']
            ];
        }, $this->playerlist);

        $statuskeys = [
            'initializing',
            'running',
            'finished'
        ];

        return [
            'status' => $statuskeys[$this->status],
            'tiles' => $this->tiles,
            'player' => $player,
            'activeplayer' => $this->activeplayer,
            'playerlist' => $this->playerlist,
            'playerpositions' => $playerpositions,
            'questions' => $questionlist,
            'winner' => $this->winner
        ];
    }

    protected function solve_tile(int $i, int $j) {
        if ($this->tiles[$i][$j]['type'] == self::TYPE_QUESTION) {
            $this->tiles[$i][$j]['type'] = self::TYPE_SOLVED;
        }
    }

    protected function move_player(int $player, int $newi, int $newj) {
        $this->playerlist[$player]['i'] = $newi;
        $this->playerlist[$player]['j'] = $newj;
    }

    protected function end_game(int $winner) {
        $this->status = self::FINISHED;
        $this->winner = $winner;
    }

    protected function next_turn() {
        // Next player's turn.
        $this->activeplayer = ($this->activeplayer + 1) % sizeof($this->playerlist);
    }

    public function perform_action(int $player, string $action) {
        if ($this->status != self::RUNNING) {
            throw new game_exception('gamenotrunning');
        }
        if ($this->activeplayer != $player) {
            throw new game_exception('notyourturn');
        }

        $request = json_decode($action, true, 255, JSON_THROW_ON_ERROR);

        $newi = $request['i'];
        $newj = $request['j'];

        // Check if neighboring question was answered
        $i = $this->playerlist[$player]['i'];
        $j = $this->playerlist[$player]['j'];

        if ($newi < 0 || $newi >= sizeof($this->tiles) ||
            $newj < 0 || $newj >= sizeof($this->tiles[0]) ||
            abs($i - $newi) + abs($j - $newj) != 1) {

            throw new game_exception('illegalmove');
        }

        $tile = $this->tiles[$newi][$newj];

        switch ($tile['type']) {
            case self::TYPE_QUESTION:
                $questions = questions::load($this->playerlist[$player]['questionusageid']);
                $mark = $questions->process_answer_and_get_mark($tile['questionindex'] + 1, $request['answer']);
                $this->playerlist[$player]['lastmark'] = $mark;

                if ($mark == 1) {
                    // Correct answer was given.
                    $this->solve_tile($newi, $newj);
                    $this->move_player($player, $newi, $newj);
                }
                $this->next_turn();
                break;

            case self::TYPE_START:
            case self::TYPE_EMPTY:
                $this->move_player($player, $newi, $newj);
                break;

            case self::TYPE_GOAL:
                $this->move_player($player, $newi, $newj);
                $this->end_game($player);
                break;

            default:
                throw new game_exception('illegalmove');
        }

    }
}
