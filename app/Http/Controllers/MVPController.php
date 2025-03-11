<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Reserva;
use App\Models\Tour;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MVPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getUsers()
    {
        return response()->json(Usuario::all());
    }

    public function addUser(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'telefono' => 'required|string|max:20',
        ]);
        
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make('123456'), // Generamos una contraseña por defecto
            'telefono' => $request->telefono,
            'direccion' => 'micasa',
            'latitud' => -12.11325900,
            'longitud' => -76.95542436,
        ]);
        

        return response()->json($usuario, 201);
    }
}

