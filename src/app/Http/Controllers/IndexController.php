<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class IndexController extends Controller
{
    function index()
    {
        $scoreboard = User::select('login', 'score')->orderByDesc('score')->limit(30)->get();
        return view('index')->with('scoreboard', $scoreboard);
    }
}
