<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model {
    use HasFactory;

    protected $table = 'horarios';
    protected $fillable = ['hora_salida', 'hora_regreso'];

    public function reservas() {
        return $this->hasMany(Reserva::class);
    }
}
