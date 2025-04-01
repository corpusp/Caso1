<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('password');
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('usuarios');
    }
};

