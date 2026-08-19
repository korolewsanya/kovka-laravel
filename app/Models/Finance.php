<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $fillable = [
        'date',
        'income',
        'expense',
        'profit',
        'note',
    ];
    
    protected $casts = [
        'date' => 'date',
        'income' => 'decimal:2',
        'expense' => 'decimal:2',
        'profit' => 'decimal:2',
    ];
}