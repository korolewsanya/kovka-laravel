<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            
            // Данные из старой таблицы workes
            $table->string('specialty')->nullable();     // spec
            $table->string('full_name');                 // name
            $table->string('phone')->nullable();         // tel
            $table->string('email')->nullable();         // email
            $table->text('address')->nullable();         // adres
            $table->date('hire_date')->nullable();       // data
            $table->text('notes')->nullable();           // proch
            
            // Дополнительные поля
            $table->string('position')->nullable();      // Должность
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};