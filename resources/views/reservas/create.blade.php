@extends('layouts.app')

@section('content')
    <h1>Crear Nueva Reserva</h1>

    <form action="{{ route('reservas.store') }}" method="POST">
        @csrf

        <input type="hidden" name="usuario_id" value="{{ auth()->user()->id }}">

        <label for="tour_id">Tour:</label>
        <select name="tour_id" required>
            @foreach($tours as $tour)
                <option value="{{ $tour->id }}">{{ $tour->nombre }}</option>
            @endforeach
        </select>

        <label for="horario_id">Horario:</label>
        <select name="horario_id" required>
            @foreach($horarios as $horario)
                <option value="{{ $horario->id }}">{{ $horario->hora_salida }}</option>
            @endforeach
        </select>

        <label for="fecha_reserva">Fecha de Reserva:</label>
        <input type="date" name="fecha_reserva" required>

        <button type="submit">Guardar Reserva</button>
    </form>
@endsection
