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
        Schema::create('entregables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competencia_id');
            $table->string('nombre', 255);
            $table->dateTime('fecha_limite');
            $table->string('formatos_aceptados', 255)->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('competencia_id')
                ->references('id')->on('competencias')
                ->onDelete('cascade');
            
            $table->index('competencia_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregables');
    }
};
