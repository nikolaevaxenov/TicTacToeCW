<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class IndexController extends Controller
{
    function index()
    {
        return view('index');
    }
}
