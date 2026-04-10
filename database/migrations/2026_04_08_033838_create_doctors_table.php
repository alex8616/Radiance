<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctores', function (Blueprint $table) {
            $table->id();

            // 🔥 relación con users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('nombre', 150);
            $table->string('especialidad', 100)->nullable();
            $table->string('telefono', 50)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctores'); // 🔴 corregido
    }
};