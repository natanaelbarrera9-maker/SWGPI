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
        Schema::create('project_user', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id');
            $table->string('user_id', 10);
            $table->enum('rol_asesor', ['primario', 'secundario'])->nullable();

            // Composite primary key
            $table->primary(['project_id', 'user_id']);

            // Foreign keys
            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};
