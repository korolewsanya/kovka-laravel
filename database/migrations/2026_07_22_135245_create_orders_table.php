<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Связь с товаром (products)
            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->onDelete('set null');
            
            // Данные заказчика (из старой таблицы zakaz)
            $table->string('customer_name')->nullable();      // Name
            $table->string('customer_phone')->nullable();     // Tel
            $table->string('customer_email')->nullable();     // Email
            $table->text('comment')->nullable();              // Coment
            
            // Информация о заказе
            $table->date('order_date')->nullable();           // date
            $table->decimal('price', 10, 2)->nullable();      // Prise
            $table->decimal('paid', 10, 2)->default(0);       // Pay
            $table->text('progress')->nullable();             // Proces
            
            // Размеры (если кастомный заказ)
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            
            // Статус заказа
            $table->string('status')->default('new'); // new, in_progress, done
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};