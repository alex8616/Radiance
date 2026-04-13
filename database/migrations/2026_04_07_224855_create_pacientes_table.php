<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();

            // 🔥 quién registró el paciente
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('nombre', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100)->nullable();
            $table->string('ci', 50)->nullable();

            $table->date('fecha_nacimiento')->nullable();
            $table->string('lugar_nacimiento', 150)->nullable();

            $table->string('telefono', 50)->nullable();
            $table->string('direccion')->nullable();
            $table->string('ocupacion', 100)->nullable();
            $table->string('estado_civil', 50)->nullable();
            $table->string('sexo', 10)->nullable();

            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};