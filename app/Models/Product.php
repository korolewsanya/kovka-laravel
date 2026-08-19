<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Указываем, какие поля можно заполнять
    protected $fillable = [
        'name',
        'category',
        'image',
        'length',
        'width',
        'height',
        'price',
        'description',
        'is_active',
    ];
    
    // Преобразование типов (чтобы price возвращался как число, а is_active как boolean)
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
