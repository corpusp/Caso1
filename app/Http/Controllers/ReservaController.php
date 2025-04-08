<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Reserva;
use App\Models\Tour;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Can;
use Illuminate\Support\Facades\Gate;

class ReservaController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Por favor, inicie sesión.');
        }
    
        // Verifica si el usuario tiene el rol de 'admin' o 'cliente'
        if (auth()->user()->hasRole('Admin')) {
            // Si es admin, cargar todas las reservas
            $reservas = Reserva::with('usuario', 'tour', 'horario')->get();
        } else {
            // Si es cliente, solo cargar las reservas del usuario autenticado
            $reservas = Reserva::with('usuario', 'tour', 'horario')
                               ->where('usuario_id', Auth::id()) // Filtra por el ID del usuario autenticado
                               ->get();
        }
    
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
        'direccion' => 'required|string',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
    ]);

    $usuario = \App\Models\Usuario::findOrFail($request->usuario_id);

    // Guardar la reserva
    Reserva::create([
        'usuario_id' => $request->usuario_id,
        'tour_id' => $request->tour_id,
        'horario_id' => $request->horario_id,
        'fecha_reserva' => $request->fecha_reserva,
        'direccion' => $request->direccion,
        'latitud' => $request->latitud,
        'longitud' => $request->longitud,
    ]);

    return redirect()->route('reservas.index')->with('success', 'Reserva y dirección guardadas exitosamente.');
}


    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $reserva = Reserva::with(['usuario', 'tour', 'horario'])->findOrFail($id);

    // Obtener los usuarios que tienen la misma fecha, horario y tour
    $usuarios =  Usuario::whereHas('reservas', function ($query) use ($reserva) {
        $query->whereDate('created_at', $reserva->created_at->format('Y-m-d'))
              ->where('horario_id', $reserva->horario_id)
              ->where('tour_id', $reserva->tour_id);
    })->get();

    return view('reservas.show', [
        'reserva' => $reserva,
        'usuarios' => Usuario::with('reservas')->get(),
        'reservaReferencia' => $reserva
    ]);
    
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
