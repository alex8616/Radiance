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
        Schema::create('firmas_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sesion_id');
            $table->string('token')->unique();
            $table->timestamp('expira_en');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firmas_tokens');
    }
};
