<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_direcciones_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDireccionesTable extends Migration
{
        // 2025_03_31_000000_create_direcciones_table.php
    public function up()
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained()->onDelete('cascade');
            $table->string('direccion');
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('direcciones');
    }
}
