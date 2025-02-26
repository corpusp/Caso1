<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable {
    use HasFactory, Notifiable;

    protected $table = 'usuarios'; // Si la tabla no es 'usuarios', cámbiala
    protected $fillable = ['nombre', 'email', 'password', 'telefono', 'direccion', 'latitud', 'longitud'];

    protected $hidden = ['password', 'remember_token']; // Ocultamos la contraseña y el token de sesión

    public function reservas() {
        return $this->hasMany(Reserva::class);
    }
}

