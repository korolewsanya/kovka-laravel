<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            
            $table->date('date')->nullable();              // date
            $table->decimal('income', 10, 2)->default(0);  // dohod
            $table->decimal('expense', 10, 2)->default(0); // rashod
            $table->decimal('profit', 10, 2)->default(0);  // prib
            
            $table->text('note')->nullable();              // Примечание
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};