<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;

class GameController extends Controller
{
    function saveField(Request $request)
    {
        if (session()->get('login')) {
            $login = session()->get('login');
        } else {
            $login = session()->get('guestuuid');
        }

        if ($request->newGame == true) {
            if ($request->exitGame == false && $request->fromOld == true && $request->draw == false) {
                $tscore = User::select('score', 'losses', 'wins')->where('login', '=', $login)->first();
                if ($request->winSide != $request->side) {
                    if ($request->mode == "single") {
                        User::where('login', '=', $login)->update(['score' => $tscore['score'] - 1]);
                        User::where('login', '=', $login)->update(['losses' => $tscore['losses'] + 1]);
                    }
                } else {
                    if ($request->mode == "single") {
                        User::where('login', '=', $login)->update(['score' => $tscore['score'] + 3]);
                        User::where('login', '=', $login)->update(['wins' => $tscore['wins'] + 1]);
                    }
                }
                Game::where('player1', '=', $login)->orWhere('player2', '=', $login)->delete();
                if ($request->side == 'X') {
                    Game::insert([
                        'player1' => $login,
                        'player2' => null,
                        'mode' => $request->mode
                    ]);
                } else {
                    Game::insert([
                        'player1' => null,
                        'player2' => $login,
                        'mode' => $request->mode
                    ]);
                }
            } else if ($request->exitGame == true && $request->draw == false) {
                if ($request->gameRunning == 1) {
                    $tscore = User::select('score', 'losses')->where('login', '=', $login)->first();
                    if ($request->mode == "single") {
                        User::where('login', '=', $login)->update(['score' => $tscore['score'] - 1]);
                        User::where('login', '=', $login)->update(['losses' => $tscore['losses'] + 1]);
                    }
                } else if ($request->gameRunning == 0) {
                    $tscore = User::select('score', 'losses', 'wins')->where('login', '=', $login)->first();
                    if ($request->winSide != $request->side) {
                        if ($request->mode == "single") {
                            User::where('login', '=', $login)->update(['score' => $tscore['score'] - 1]);
                            User::where('login', '=', $login)->update(['losses' => $tscore['losses'] + 1]);
                        }
                    } else {
                        if ($request->mode == "single") {
                            User::where('login', '=', $login)->update(['score' => $tscore['score'] + 3]);
                            User::where('login', '=', $login)->update(['wins' => $tscore['wins'] + 1]);
                        }
                    }
                }
                Game::where('player1', '=', $login)->orWhere('player2', '=', $login)->delete();
            } else {
                Game::where('player1', '=', $login)->orWhere('player2', '=', $login)->delete();
                if ($request->side == 'X') {
                    Game::insert([
                        'player1' => $login,
                        'player2' => null,
                        'mode' => $request->mode
                    ]);
                } else {
                    Game::insert([
                        'player1' => null,
                        'player2' => $login,
                        'mode' => $request->mode
                    ]);
                }
            }
        } else {
            if ($request->side == 'X') {
                Game::where('player1', '=', $login)->update(['field' => $request->field]);
            } else {
                Game::where('player2', '=', $login)->update(['field' => $request->field]);
            }
        }

        return redirect()->route('index');
    }
}
