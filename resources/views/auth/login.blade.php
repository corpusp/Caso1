@extends('layouts.app')

@section('content')
    <h2>Iniciar Sesión</h2>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit">Iniciar Sesión</button>
    </form>

    <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
@endsection
