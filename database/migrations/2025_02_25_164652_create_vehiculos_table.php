<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 20)->unique();
            $table->string('modelo', 50);
            $table->integer('capacidad');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('vehiculos');
    }
};

