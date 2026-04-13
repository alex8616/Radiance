<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antecedentes_medicos', function (Blueprint $table) {
            $table->id();

            // Relación con paciente
            $table->foreignId('paciente_id')->unique()->constrained('pacientes')->cascadeOnDelete();

            // Familiares y personales
            $table->text('antecedentes_familiares')->nullable();
            $table->text('otros')->nullable();
            $table->text('alergias')->nullable();

            // Patológicos personales (Sí/No)
            $table->boolean('anemia')->default(false);
            $table->boolean('asma')->default(false);
            $table->boolean('cardiopatias')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('epilepsia')->default(false);
            $table->boolean('hipertension')->default(false);
            $table->boolean('tuberculosis')->default(false);
            $table->boolean('vih')->default(false);
            $table->boolean('embarazo')->default(false);

            // Tratamientos médicos
            $table->boolean('en_tratamiento')->default(false);
            $table->boolean('recibe_tratamiento')->default(false);
            $table->boolean('hemorragia_extraccion')->default(false);

            // Bucodentales
            $table->date('ultima_visita')->nullable();
            $table->boolean('fuma')->default(false);
            $table->boolean('bebe')->default(false);
            $table->boolean('protesis')->default(false);

            // Higiene oral
            $table->boolean('cepillo')->default(false);
            $table->boolean('hilo')->default(false);
            $table->boolean('enjuague')->default(false);
            $table->string('frecuencia')->nullable(); // 1, 2, 3 veces al día
            $table->boolean('sangrado')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antecedentes_medicos');
    }
};
