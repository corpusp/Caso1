@extends('layouts.app')

@section('content')
    <h2>Registro</h2>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <label>Nombre:</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required>
        <br><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="{{ old('telefono') }}" required>
        <br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required>
        <br>
        
        <label>Confirmar contraseña:</label>
        <input type="password" name="password_confirmation" required>
        <br>
        <small style="color: gray;">La contraseña debe tener al menos 6 caracteres.</small>
        <br><br>

        <button type="submit">Registrarse</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
@endsection
