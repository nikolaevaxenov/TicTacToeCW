<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RulesController extends Controller
{
    function rules()
    {
        return view('rules');
    }
}
