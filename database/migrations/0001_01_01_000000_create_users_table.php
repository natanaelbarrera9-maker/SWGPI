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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 10)->primary(); // Matrícula/Nómina
            $table->string('password', 255);
            $table->string('nombres', 200);
            $table->string('apa', 100)->nullable(); // Apellido paterno
            $table->string('ama', 100)->nullable(); // Apellido materno
            $table->text('direccion')->nullable();
            $table->string('telefonos', 200)->nullable();
            $table->string('curp', 20)->nullable();
            $table->string('email', 255)->nullable()->unique();
            $table->tinyInteger('perfil_id')->default(3); // 1=Admin, 2=Teacher, 3=Student
            $table->timestamp('created_at')->useCurrent();
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
