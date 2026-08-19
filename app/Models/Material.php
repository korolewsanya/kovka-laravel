<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    //Список полей форм, которые разрешено заполнять
    protected $fillable = [
        'date',
        'name',
        'purchased',
        'used',
        'balance',
        'price_per_unit',
        'total_price',
    ];
    
    //Превращаем данные из базы данных в удобные PHP-типы данных, а не просто строки
    protected $casts = [
        'date' => 'date',  // Дата → объект Carbon 
        'purchased' => 'decimal:2',
        'used' => 'decimal:2',
        'balance' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];
}
//  ---Связь модели с таблицей---
//Модель Material автоматически связывается с таблицей materials по правилу:
//имя класса во множественном числе и в нижнем регистре = имя таблицы.
//Класс модели Material	Ищет таблицу materials
