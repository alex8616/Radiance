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
        Schema::create('antecedentes_medicos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')->unique()->constrained('pacientes')->cascadeOnDelete();

            $table->text('antecedentes_familiares')->nullable();
            $table->text('antecedentes_personales')->nullable();

            $table->boolean('anemia')->default(false);
            $table->boolean('cardiopatias')->default(false);
            $table->boolean('asma')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('enfermedades_gastricas')->default(false);
            $table->boolean('hepatitis')->default(false);
            $table->boolean('tuberculosis')->default(false);
            $table->boolean('epilepsia')->default(false);
            $table->boolean('hipertension')->default(false);
            $table->boolean('vih')->default(false);

            $table->text('alergias')->nullable();
            $table->boolean('en_tratamiento')->default(false);
            $table->text('medicamentos')->nullable();
            $table->boolean('hemorragia_extraccion')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedente_medicos');
    }
};
