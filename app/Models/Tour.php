<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model {
    use HasFactory;

    protected $table = 'tours';
    protected $fillable = ['nombre', 'descripcion', 'precio', 'duracion'];

    public function reservas() {
        return $this->hasMany(Reserva::class);
    }
}

