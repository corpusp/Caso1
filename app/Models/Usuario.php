<?php

namespace App\Models;
// app/Models/Usuario.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $fillable = ['nombre', 'password', 'telefono'];

    protected $hidden = ['password', 'remember_token'];

    // Relación uno a muchos con direcciones
    public function direccion()
    {
        return $this->hasOne(Direccion::class);
    }
}
