<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'specialty',
        'employee_name',
        'accrued',
        'received',
        'description',
    ];
    
    protected $casts = [
        'date' => 'date',
        'accrued' => 'decimal:2',
        'received' => 'decimal:2',
    ];
    
    // Связь с сотрудником
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}