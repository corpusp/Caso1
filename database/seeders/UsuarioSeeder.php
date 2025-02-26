<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Pepe',
                'email' => 'pepe@gmail.com',
                'password' => bcrypt('123456'),
                'telefono' => '123456789',
                'direccion' => 'Calle 123',
                'latitud' => -11.985350,
                'longitud' => -77.097352,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'maria',
                'email' => 'maria@gmail.com',
                'password' => bcrypt('123456'),
                'telefono' => '987654321',
                'direccion' => 'Calle 122',
                'latitud' => -12.024282,
                'longitud' => -77.081013,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'juan',
                'email' => 'juan@gmail.com',
                'password' => bcrypt('123456'),
                'telefono' => '987655555',
                'direccion' => 'Calle 123',
                'latitud' => -11.973931,
                'longitud' => -77.062763,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
