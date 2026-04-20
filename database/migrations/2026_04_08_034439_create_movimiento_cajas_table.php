<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();

            // Relación con cajas
            $table->foreignId('caja_id')
                  ->constrained('cajas')
                  ->cascadeOnDelete();

            // Campos principales
            $table->enum('tipo', ['Ingreso','Egreso']);
            $table->enum('categoria', ['Adelanto','PagoTratamiento','Gasto','Otro']);
            $table->decimal('monto', 10, 2);
            $table->date('fecha');
            $table->text('descripcion')->nullable();

            // Relaciones opcionales
            $table->foreignId('tratamiento_id')
                  ->nullable()
                  ->constrained('tratamientos_paciente')
                  ->nullOnDelete(); // más claro que onDelete('set null')

            $table->foreignId('paciente_id')
                  ->nullable()
                  ->constrained('pacientes')
                  ->nullOnDelete();

            // Método de pago
            $table->enum('metodo_pago', ['efectivo', 'qr', 'tarjeta'])
                  ->default('Efectivo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
    }
};
