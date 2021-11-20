<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;

class RegistrationLoginController extends Controller
{
    function registration(Request $request)
    {
        $request->validate([
            'loginReg' => 'required|alpha_num|unique:users,login',
            'passwordReg' => 'required|confirmed|regex:/^\S*$/u'
        ]);

        $user = new User;
        $user->login = $request->loginReg;
        $user->password = Hash::make($request->passwordReg);

        $save = $user->save();
        if ($save) {
            session()->put('login', $request->loginReg);
            session()->forget('regStatus');
            return redirect()->route('index');
        } else {
            session()->flash('regStatus', 1);
            return redirect()->route('index')->with('failReg', 'Упс. Что-то пошло не так!');
        }
    }

    function login(Request $request)
    {
        $request->validate([
            'loginLog' => 'required',
            'passwordLog' => 'required'
        ]);

        $userInfo = User::where('login', '=', $request->loginLog)->first();
        if (!$userInfo) {
            session()->flash('logStatus', 1);
            return redirect()->route('index')->with('failLog', "Данное имя пользователя не найдено!");
        } else {
            if (Hash::check($request->passwordLog, $userInfo->password)) {
                session()->forget('logStatus');
                session()->put('login', $request->loginLog);
                return redirect()->route('index');
            } else {
                session()->flash('logStatus', 1);
                return redirect()->route('index')->with('failLog', "Неверный пароль учетной записи!");
            }
        }
    }

    function logout(Request $request)
    {
        if (session()->has('login')) {
            session()->forget('login');
            session()->forget('logStatus');
            session()->forget('regStatus');
        }
        return redirect()->route('index');
    }

    function saveField(Request $request)
    {
        if (session()->get('login')) {
            $login = session()->get('login');
        } else {
            $login = session()->get('guestuuid');
        }

        if ($request->newGame == true) {
            if (session()->get('login')) {
                Game::where('player1', '=', session()->get('login'))->orWhere('player2', '=', session()->get('login'))->delete();
                if ($request->side == 'X') {
                    Game::insert([
                        'player1' => session()->get('login'),
                        'player2' => null,
                        'mode' => $request->mode
                    ]);
                } else {
                    Game::insert([
                        'player1' => null,
                        'player2' => session()->get('login'),
                        'mode' => $request->mode
                    ]);
                }
            } else {
                Game::where('player1', '=', session()->get('guestuuid'))->orWhere('player2', '=', session()->get('guestuuid'))->delete();
                if ($request->side == 'X') {
                    Game::insert([
                        'player1' => session()->get('guestuuid'),
                        'player2' => null,
                        'mode' => $request->mode
                    ]);
                } else {
                    Game::insert([
                        'player1' => null,
                        'player2' => session()->get('guestuuid'),
                        'mode' => $request->mode
                    ]);
                }
            }
        } else {
            if ($request->side == 'X') {
                Game::table('games')->upsert([['field' => $request->field, 'player1' => $login, 'player2' => null]], ['player1', 'player2'], ['field']);
            } else {
                Game::table('games')->upsert([['field' => $request->field, 'player1' => null, 'player2' => $login]], ['player1', 'player2'], ['field']);
            }
        }

        return redirect()->route('index');
    }

    public function findAction(Request $request)
    {
        if ($request->has('loginLog')) {
            return $this->login($request);
        } else if ($request->has('loginReg')) {
            return $this->registration($request);
        } else if ($request->has('logout')) {
            return $this->logout($request);
        } else if ($request->has('saveField')) {
            return $this->saveField($request);
        }
        return 'no action found';
    }
}
