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
        Schema::create('entregas_estudiantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entregable_id');
            $table->string('user_id', 20);
            $table->unsignedBigInteger('project_id');
            $table->string('nombre_archivo', 255);
            $table->string('ruta_archivo', 255);
            $table->timestamp('fecha_entrega')->useCurrent();
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->text('comentarios_docente')->nullable();

            // Índices y foreign keys
            $table->foreign('entregable_id')
                ->references('id')->on('entregables')
                ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            
            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade');

            // Unique constraint: solo 1 entrega por estudiante, entregable y proyecto
            $table->unique(['entregable_id', 'user_id', 'project_id'], 'entrega_unica');
            
            $table->index('user_id');
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas_estudiantes');
    }
};
