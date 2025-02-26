<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourSeeder extends Seeder
{
    public function run()
    {
        DB::table('tours')->insert([
            [
                'nombre' => 'Tour a Machu Picchu',
                'descripcion' => 'Visita guiada a la maravilla del mundo, Machu Picchu.',
                'precio' => 250.00,
                'duracion' => '1 día',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Tour al Valle Sagrado',
                'descripcion' => 'Descubre los hermosos paisajes y ruinas del Valle Sagrado.',
                'precio' => 150.00,
                'duracion' => '1 día',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Tour a las Líneas de Nazca',
                'descripcion' => 'Sobrevuelo sobre las misteriosas Líneas de Nazca.',
                'precio' => 300.00,
                'duracion' => '1 día',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

