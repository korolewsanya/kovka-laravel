<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            
            // Связь с сотрудником
            $table->foreignId('employee_id')
                  ->nullable()
                  ->constrained('employees')
                  ->onDelete('set null');
            
            $table->date('date')->nullable();                 // date
            $table->string('specialty')->nullable();          // spec
            $table->string('employee_name')->nullable();      // name
            $table->decimal('accrued', 10, 2)->default(0);    // nachis
            $table->decimal('received', 10, 2)->default(0);   // poluch
            $table->string('description')->nullable();        // names
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};