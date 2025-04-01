<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Reserva;
use App\Models\Tour;
use App\Models\Usuario;
use App\Models\Direccion; // Correct namespace for the Direccion model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MVPController extends Controller
{
    public function deleteUser($id) {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
    public function getUsers()
    {
        // Cargar usuarios con la relación de dirección
        return response()->json(Usuario::with('direccion')->get());
    }

    public function addUser(Request $request) {      
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);
        
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'password' => Hash::make('123456'), // Generamos una contraseña por defecto
            'telefono' => $request->telefono,
        ]);


        // Crear la dirección asociada al usuario
        $direccion = new Direccion([
            'direccion' => $request->direccion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        // Relacionamos la dirección con el usuario
        $usuario->direccion()->save($direccion); // Esto debería guardar la dirección

        return response()->json($usuario, 201);
    }
}

