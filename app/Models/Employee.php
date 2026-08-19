<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Employee extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'address',
        'hire_date',
        'notes',
        'position',
        'is_active',
    ];
    
    protected $casts = [
        'hire_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function workReports()
    {
        return $this->hasMany(WorkReport::class);
    }
    
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    //СОБЫТИЕ ПРИ СОЗДАНИИ
    protected static function booted()
    {
        static::created(function ($employee) {
            if ($employee->email && !User::where('email', $employee->email)->exists()) {
                User::create([
                    'name' => $employee->full_name,
                    'email' => $employee->email,
                    'password' => bcrypt('12345678'),
                    'role' => 'employee',
                ]);
            }
        });

        //СОБЫТИЕ ПРИ УДАЛЕНИИ
        static::deleted(function ($employee) {
            if ($employee->email) {
                User::where('email', $employee->email)->delete();
            }
        });
    }
}