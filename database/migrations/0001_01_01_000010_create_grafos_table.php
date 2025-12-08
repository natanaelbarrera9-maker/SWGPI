<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grafos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->string('nombre_archivo', 255);
            $table->enum('status', ['activo', 'inactivo'])->default('activo');
            $table->timestamp('fecha_creacion')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grafos');
    }
};
