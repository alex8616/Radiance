<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tratamiento_id')->constrained('tratamientos_paciente')->cascadeOnDelete();

            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();

            $table->foreignId('sesion_id')->nullable()->constrained('sesiones_tratamiento')->nullOnDelete();

            $table->decimal('monto', 10, 2);

            $table->date('fecha');

            $table->enum('metodo_pago', ['efectivo', 'qr', 'tarjeta'])->nullable();

            $table->string('referencia')->nullable();

            $table->timestamps();

            $table->index('tratamiento_id');
            $table->index('sesion_id');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};