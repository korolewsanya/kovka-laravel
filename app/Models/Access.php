<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{   
    protected $table = 'access';
    protected $fillable = [
        'work_class',
        'profession',
        'full_name',
        'access_code',
        'is_active',
    ];
    
    protected $casts = [
        'work_class' => 'integer',
        'is_active' => 'boolean',
    ];
}