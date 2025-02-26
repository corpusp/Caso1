<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade'); // Relación con 'tours'
            $table->time('hora_salida');
            $table->time('hora_llegada'); // Agregar esta línea
            $table->timestamps();
        });
        
    }

    public function down() {
        Schema::dropIfExists('horarios');
    }
};

