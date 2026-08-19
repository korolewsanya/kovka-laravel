<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            
            $table->integer('code')->unique();          // cod
            $table->string('description')->nullable();  // Описание
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codes');
    }
};