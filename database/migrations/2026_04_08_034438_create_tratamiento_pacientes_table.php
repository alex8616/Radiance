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
        Schema::create('tratamientos_paciente', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctores')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();

            $table->string('nombre_tratamiento', 150);
            $table->text('descripcion')->nullable();

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin_estimada')->nullable();

            $table->decimal('costo_total', 10, 2)->nullable();

            $table->enum('estado', ['activo', 'finalizado', 'cancelado'])
                ->default('activo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tratamiento_pacientes');
    }
};
