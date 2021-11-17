<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RegistrationLoginController extends Controller
{
    function registration(Request $request)
    {
        $request->validate([
            'loginReg' => 'required|alpha_num|unique:users,login',
            'passwordReg' => 'required|regex:/^\S*$/u'
        ]);

        $user = new User;
        $user->login = $request->loginReg;
        $user->password = Hash::make($request->passwordReg);
        $user->apikey = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));

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

    public function findAction(Request $request)
    {
        if ($request->has('loginLog')) {
            return $this->login($request);
        } else if ($request->has('loginReg')) {
            return $this->registration($request);
        } else if ($request->has('logout')) {
            return $this->logout($request);
        }
        return 'no action found';
    }
}
