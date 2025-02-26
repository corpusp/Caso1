<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model {
    use HasFactory;

    protected $table = 'reservas';
    protected $fillable = ['usuario_id', 'tour_id', 'horario_id', 'estado'];

    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }

    public function tour() {
        return $this->belongsTo(Tour::class);
    }

    public function horario() {
        return $this->belongsTo(Horario::class);
    }
}

