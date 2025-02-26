<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorarioSeeder extends Seeder
{
    public function run()
    {
        DB::table('horarios')->insert([
            [
                'tour_id' => 1, // Tour a Machu Picchu
                'hora_salida' => '06:00:00',
                'hora_llegada' => '18:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tour_id' => 2, // Tour al Valle Sagrado
                'hora_salida' => '07:30:00',
                'hora_llegada' => '17:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tour_id' => 3, // Tour a las Líneas de Nazca
                'hora_salida' => '08:00:00',
                'hora_llegada' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
