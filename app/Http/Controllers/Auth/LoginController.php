<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(){
        
    }

    public function loginForm() {
        if (Auth::guest())
            return view("login");
        else
            return view("home");
    }


    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            // Redirecciona al usuario a la página de inicio tras el inicio de sesión exitoso
            return redirect()->intended(route('home'))->with('success', 'Inicio de sesión exitoso');
        }

        // Si las credenciales no son correctas, regresa al formulario de inicio de sesión
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente');
    }
}
