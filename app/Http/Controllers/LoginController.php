<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //Muestra el formulario de login
    public function showLoginForm ()
    {
        return view('auth.login');
    }
    //Manejar el login
    public function login(Request $request)
    {
        //Validación de las credenciales
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //Autenticar
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/'); //Redirije a la página principal
        }

        //Si no es correcto
        return back()->withErrors([
            'email' => 'Credenciales inválidas'
        ]);
    }
    //Maneja el logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
