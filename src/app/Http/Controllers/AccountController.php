<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    function account()
    {
        if (session()->has('login')) {
            return view('account');
        } else {
            return redirect()->route('index');
        }
    }
}
