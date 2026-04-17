<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones_tratamiento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tratamiento_id')
                  ->constrained('tratamientos_paciente')
                  ->cascadeOnDelete();

            // 🔥 NUEVO
            $table->foreignId('sucursal_id')
                  ->constrained('sucursales')
                  ->cascadeOnDelete();

            $table->date('fecha')->nullable();
            $table->date('fecha_atencion')->nullable();

            $table->text('observaciones')->nullable();
            $table->text('analisis')->nullable();
            $table->text('plan_accion')->nullable();

            $table->decimal('costo', 10, 2)->nullable();
            $table->decimal('saldo', 10, 2)->nullable();

            $table->string('firma')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones_tratamiento');
    }
};