<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('producto_sucursal', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('sucursal_id')
                    ->constrained('sucursales')
                    ->cascadeOnDelete();

            $table->decimal('precio', 10, 2);
            $table->integer('stock')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_sucursal');
    }
};