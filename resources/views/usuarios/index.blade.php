@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Agregar Usuario</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->telefono }}</td>
                    <td>{{ $usuario->direccion }}</td>
                    <td>
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
