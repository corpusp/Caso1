@extends('layouts.app')

@section('content')
    <h2>Bienvenido, {{ auth()->user()->nombre }} 👋</h2>
    <p>Tu dirección registrada es: {{ auth()->user()->direccion }}</p>
    <p>Coordenadas: Latitud {{ auth()->user()->latitud }}, Longitud {{ auth()->user()->longitud }}</p>

    <a href="{{ route('logout') }}">Cerrar sesión</a>
@endsection
