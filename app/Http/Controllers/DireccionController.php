<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direccion;
use Illuminate\Support\Facades\Auth;

class DireccionController extends Controller
{
    public function index()
    {
        return view('direcciones.index');
    }

    public function store(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Usuario no autenticado'], 401);
    }

    $request->validate([
        'direccion' => 'required|string|max:255',
        'latitud'   => 'required|numeric',
        'longitud'  => 'required|numeric',
    ]);

    Direccion::create([
        'usuario_id' => Auth::id(),
        'direccion'  => $request->direccion,
        'latitud'    => $request->latitud,
        'longitud'   => $request->longitud,
    ]);

    return response()->json(['message' => 'Dirección guardada correctamente']);
}

}

