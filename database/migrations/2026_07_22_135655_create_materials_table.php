<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //Применяем миграцию //up() выполнится php artisan migrate
    public function up(): void
    {
        //Создаем новую таблицу в базе данных и определяем её структуру //Blueprint- это конструктор таблиц
        Schema::create('materials', function (Blueprint $table) {
            //Создаем колонки в таблице
            $table->id();
            
            $table->date('date')->nullable();                     // date (было в БД kovka)
            $table->string('name');                               // name -//-//-
            $table->decimal('purchased', 10, 2)->default(0);     // kup (было в БД kovka)
            $table->decimal('used', 10, 2)->default(0);          // izras -//-//-
            $table->decimal('balance', 10, 2)->default(0);       // ost -//-//-
            $table->decimal('price_per_unit', 10, 2)->default(0); // prise -//-//-
            $table->decimal('total_price', 10, 2)->default(0);    // itogo -//-//-
            
            //Создаем сразу две колонки: created_at (время создания записи) и updated_at (время последнего обновления записи).
            $table->timestamps();
        });
    }

    //Отменяем миграцию   //down() выполнится при php artisan migrate:rollback
    public function down(): void
    {
        //Проверяем, существует ли таблица materials в базе данных, и если да — удаляем её полностью вместе со всеми данными.
        Schema::dropIfExists('materials');
    }
};