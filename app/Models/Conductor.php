<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model {
    use HasFactory;

    protected $table = 'conductores';
    protected $fillable = ['nombre', 'telefono', 'licencia'];

    public function vehiculos() {
        return $this->belongsToMany(Vehiculo::class, 'vehiculos_conductores');
    }
}
