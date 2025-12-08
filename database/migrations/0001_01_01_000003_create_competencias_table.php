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
        Schema::create('competencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asignatura_id');
            $table->string('nombre', 255);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->timestamps();

            // Foreign key
            $table->foreign('asignatura_id')
                ->references('id')->on('asignaturas')
                ->onDelete('cascade');
            
            $table->index('asignatura_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencias');
    }
};
