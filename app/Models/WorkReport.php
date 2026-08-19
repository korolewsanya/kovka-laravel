<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkReport extends Model
{
    protected $fillable = [
        'employee_id',
        'work_class',
        'specialty',
        'employee_name',
        'task',
        'report',
        'date',
        'image',
    ];
    
    protected $casts = [
        'date' => 'date',
        'work_class' => 'string',
    ];
    
    // Связь с сотрудником
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}