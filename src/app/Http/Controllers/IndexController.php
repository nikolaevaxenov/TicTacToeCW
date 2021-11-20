<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Game;

class IndexController extends Controller
{
    function index()
    {
        if (session()->missing('login') && session()->missing('guestuuid')) {
            session()->put('guestuuid', Str::uuid()->toString());
        }

        $scoreboard = User::select('login', 'score')->orderByDesc('score')->limit(30)->get();

        if (session()->get('login')) {
            $fielddb = Game::select('field', 'player1', 'player2', 'mode')->where('player1', '=', session()->get('login'))->orWhere('player2', '=', session()->get('login'))->first();
        } else {
            $fielddb = Game::select('field', 'player1', 'player2', 'mode')->where('player1', '=', session()->get('guestuuid'))->orWhere('player2', '=', session()->get('guestuuid'))->first();
        }

        return view('index')->with('data', ['scoreboard' => $scoreboard, 'fielddb' => $fielddb]);
    }
}
