<?php

namespace App\Models;
// app/Models/Usuario.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasRoles;
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    protected $table = 'usuarios';
    protected $fillable = ['nombre', 'password', 'telefono'];

    protected $hidden = ['password', 'remember_token'];

    // Relación uno a muchos con direcciones
    public function direccion()
    {
        return $this->hasOne(Direccion::class);
    }
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }
    public function getAuthPassword()
    {
        return $this->password;
    }

}
