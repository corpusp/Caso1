<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model {
    use HasFactory;

    protected $table = 'reservas';
    protected $fillable = ['usuario_id', 'tour_id', 'horario_id', 'estado', 'direccion', 'latitud', 'longitud'];

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function tour() {
        return $this->belongsTo(Tour::class);
    }

    public function horario() {
        return $this->belongsTo(Horario::class);
    }
}

