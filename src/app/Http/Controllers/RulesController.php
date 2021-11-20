<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RulesController extends Controller
{
    function rules()
    {
        if (session()->missing('login')) {
            session()->put('guestuuid', Str::uuid()->toString());
        }

        return view('rules');
    }
}
