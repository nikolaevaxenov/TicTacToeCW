<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller
{
    function showAPI(Request $request)
    {
        $score = User::select('score', 'wins', 'losses')->where('login', session()->get('login'))->limit(1)->get();
        if ($request->showapi == 0) {
            return view('account')->with('data', [
                'score' => $score,
                'apikey' => null
            ]);
        } else {
            $apikey = User::select('apikey')->where('login', session()->get('login'))->limit(1)->get();
            return view('account')->with('data', [
                'score' => $score,
                'apikey' => $apikey
            ]);
        }
    }

    function account()
    {
        if (session()->has('login')) {
            $score = User::select('score', 'wins', 'losses')->where('login', session()->get('login'))->limit(1)->get();
            return view('account')->with('data', [
                'score' => $score,
                'apikey' => null
            ]);
        } else {
            return redirect()->route('index');
        }
    }
}
