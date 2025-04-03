<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    //Muestra el formulario de registro
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    //Maneja el registro
    public function register(Request $request)
    {
        //Validación
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8'
        ]);
        if ($validator->fails()) {
            return redirect()->route('register')->withErrors($validator)->withInput();
        }
        //Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        //Iniciar sesión
        Auth::login($user);
        return redirect('/');
    }
}
