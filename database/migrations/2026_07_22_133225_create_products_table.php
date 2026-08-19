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
        Schema::create('products', function (Blueprint $table) {
            // Основной ID (уже есть)
            $table->id();
            
            // Название изделия (было izdelie в старых таблицах)
            $table->string('name')->nullable();
            
            // Категория товара (vorota, zabor, mangal, и т.д.)
            $table->string('category');
            
            // Изображение (было image)
            $table->string('image')->nullable();
            
            // Размеры (были Dlina, Shirina, Visota)
            $table->string('length')->nullable();   // Длина
            $table->string('width')->nullable();    // Ширина
            $table->string('height')->nullable();   // Высота
            
            // Цена (было Prise)
            $table->decimal('price', 10, 2)->nullable();
            
            // Дополнительные полезные поля
            $table->text('description')->nullable();  // Описание товара
            $table->boolean('is_active')->default(true); // Активен/не активен
            
            // Поля created_at и updated_at (уже есть)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};