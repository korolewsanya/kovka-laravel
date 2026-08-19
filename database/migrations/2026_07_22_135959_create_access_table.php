<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('access', function (Blueprint $table) {
            $table->id();
            
            $table->integer('work_class')->nullable();    // class_work
            $table->string('profession')->nullable();     // prof
            $table->string('full_name');                  // name
            $table->string('access_code')->unique();      // cod (уникальный)
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access');
    }
};