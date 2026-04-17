<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sesion_producto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sesion_id')
                  ->constrained('sesiones_tratamiento')
                  ->cascadeOnDelete();

            // 🔥 CAMBIO CLAVE
            $table->foreignId('producto_sucursal_id')
                  ->constrained('producto_sucursal')
                  ->cascadeOnDelete();

            $table->text('detalle')->nullable();

            // 🔥 guardar precio histórico
            $table->decimal('precio', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesion_producto');
    }
};