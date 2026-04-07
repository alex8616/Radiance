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
        Schema::create('habitos_higiene', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')->unique()->constrained('pacientes')->cascadeOnDelete();

            $table->boolean('fuma')->default(false);
            $table->boolean('bebe')->default(false);
            $table->text('otros_habitos')->nullable();

            $table->boolean('usa_cepillo')->default(true);
            $table->boolean('usa_hilo_dental')->default(false);
            $table->boolean('usa_enjuague')->default(false);

            $table->string('frecuencia_cepillado', 50)->nullable();
            $table->boolean('sangrado_encias')->default(false);

            $table->string('ultima_visita_odontologo', 100)->nullable();
            $table->boolean('usa_protesis')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habito_higienes');
    }
};
