<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $REDIRECT_HOME = "home";
    protected $REDIRECT_LOGIN = "login";

    public function __construct(){
        
    }

    public function loginForm() {
        if(!Auth::check())
            return view("login");
        
        $user = Auth::user(); 
        $posts = $user->posts()->orderBy("created_at", "desc")->get();
        return view("home", compact("posts"));
    }


    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();
            return redirect()->intended(route($this->REDIRECT_HOME, ["user" => $user->name]))->with('success', 'Inicio de sesión exitoso');
        }

        return back()->with("error", "Las credenciales no coinciden con nuestros registros.");
    }

    public function logout(Request $request){
        Auth::logout();
        return redirect()->route($this->REDIRECT_LOGIN)->with('success', 'Sesión cerrada exitosamente');
    }
}
