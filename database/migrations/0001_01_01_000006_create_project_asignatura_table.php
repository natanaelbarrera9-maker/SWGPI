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
        Schema::create('project_asignatura', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('asignatura_id');

            // Foreign keys
            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade');
            
            $table->foreign('asignatura_id')
                ->references('id')->on('asignaturas')
                ->onDelete('cascade');

            // Unique constraint
            $table->unique(['project_id', 'asignatura_id'], 'project_asignatura_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_asignatura');
    }
};
