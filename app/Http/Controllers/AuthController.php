<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nombre' => 'required|string|max:255',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return redirect()->route('menu')->with('user', $user);
        }

        return back()->withErrors(['nombre' => 'Credenciales incorrectas']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}

