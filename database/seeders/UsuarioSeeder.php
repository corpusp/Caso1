<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Direccion;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios
        $usuario1 = Usuario::create([
            'nombre' => 'Pepe',
            'email' => 'pepe@gmail.com',
            'password' => bcrypt('123456'),
            'telefono' => '123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $usuario2 = Usuario::create([
            'nombre' => 'Maria',
            'email' => 'maria@gmail.com',
            'password' => bcrypt('123456'),
            'telefono' => '987654321',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $usuario3 = Usuario::create([
            'nombre' => 'Juan',
            'email' => 'juan@gmail.com',
            'password' => bcrypt('123456'),
            'telefono' => '987655555',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear direcciones para los usuarios
        Direccion::create([
            'usuario_id' => $usuario1->id, // Relaciona con el usuario Pepe
            'direccion' => 'Calle 123',
            'latitud' => -11.985350,
            'longitud' => -77.097352,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Direccion::create([
            'usuario_id' => $usuario2->id, // Relaciona con el usuario Maria
            'direccion' => 'Calle 122',
            'latitud' => -12.024282,
            'longitud' => -77.081013,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Direccion::create([
            'usuario_id' => $usuario3->id, // Relaciona con el usuario Juan
            'direccion' => 'Calle 123',
            'latitud' => -11.973931,
            'longitud' => -77.062763,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
