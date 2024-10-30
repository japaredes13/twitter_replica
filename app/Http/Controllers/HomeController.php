<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct(){
        
    }

    public function home(){
        if (Auth::guest())
            return view("login");
        else
            return view("timeline");
    
    }
}
