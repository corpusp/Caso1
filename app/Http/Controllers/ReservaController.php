<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Reserva;
use App\Models\Tour;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::with('usuario', 'tour', 'horario')->get();
        return view('reservas.index', compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = Usuario::all();
        $tours = Tour::all();
        $horarios = Horario::all();

        return view('reservas.create', compact('usuarios', 'tours', 'horarios'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'tour_id' => 'required|exists:tours,id',
            'horario_id' => 'required|exists:horarios,id',
            'fecha_reserva' => 'required|date',
        ]);

        Reserva::create([
            'usuario_id' => $request->usuario_id, // ID del usuario autenticado
            'tour_id' => $request->tour_id,
            'horario_id' => $request->horario_id,
            'fecha_reserva' => $request->fecha_reserva,
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva creada exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $reserva = Reserva::with(['usuario', 'tour', 'horario'])->findOrFail($id);

    // Obtener los usuarios que tienen la misma fecha, horario y tour
    $usuarios = Usuario::whereHas('reservas', function ($query) use ($reserva) {
        $query->where('fecha_reserva', $reserva->fecha_reserva)
              ->where('horario_id', $reserva->horario_id)
              ->where('tour_id', $reserva->tour_id);
    })->get(['nombre', 'latitud', 'longitud']);

    return view('reservas.show', compact('reserva', 'usuarios'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
