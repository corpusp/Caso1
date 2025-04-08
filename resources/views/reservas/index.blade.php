@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>Listado de Reservas</h1>
        <a href="{{ route('reservas.create') }}" class="btn btn-primary">Nueva Reserva</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Tour</th>
                <th>Horario</th>
                <th>Fecha</th>
                <th>Personas</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservas as $reserva)
                <tr onclick="window.location='{{ route('reservas.show', $reserva) }}'" style="cursor: pointer;">
                    <td>{{ $reserva->usuario->nombre }}</td>
                    <td>{{ $reserva->tour->nombre }}</td>
                    <td>{{ $reserva->horario->hora_salida }}</td>
                    <td>{{ $reserva->created_at->format('Y-m-d') }}</td>
                    <td>{{ $reserva->cantidad_personas }}</td>
                    <td>${{ $reserva->precio_total }}</td>
                    <td>{{ ucfirst($reserva->estado) }}</td>
                    <td>
                        <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
@endsection
