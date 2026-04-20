<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->decimal('saldo_ingreso', 10, 2)->default(0);
            $table->decimal('saldo_egreso', 10, 2)->default(0);
            $table->decimal('saldo_total', 10, 2)->default(0);

            $table->decimal('saldo_ingresoQr', 10, 2)->default(0);
            $table->decimal('saldo_ingresoTarjeta', 10, 2)->default(0);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
