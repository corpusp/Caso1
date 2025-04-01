<?php
// app/Models/Direccion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direcciones'; 

    protected $fillable = ['usuario_id', 'direccion', 'latitud', 'longitud'];

    // Relación inversa: una dirección pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
