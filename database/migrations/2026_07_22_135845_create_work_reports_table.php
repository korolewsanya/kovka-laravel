<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_reports', function (Blueprint $table) {
            $table->id();
            
            // Связь с сотрудником
            $table->foreignId('employee_id')
                  ->nullable()
                  ->constrained('employees')
                  ->onDelete('set null');
            
            // Данные из старой таблицы otchet  integer
            $table->string('work_class')->nullable();     // class_work   //потом integer поменял на string
            $table->string('specialty')->nullable();       // prof
            $table->string('employee_name')->nullable();   // name
            $table->text('task')->nullable();              // tz
            $table->text('report')->nullable();            // otchet
            $table->date('date')->nullable();              // date
            $table->string('image')->nullable();           // image
            $table->string('access_code')->nullable();     // cod
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_reports');
    }
};