<?php

namespace App\Http\Controllers\Api;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends BaseApiController
{
    public function index()
    {
        $salaries = Salary::with('employee')->get();
        return $this->success($salaries, 'Список зарплат');
    }

    public function show($id)
    {
        $salary = Salary::with('employee')->find($id);
        if (!$salary) {
            return $this->error('Запись не найдена', 404);
        }
        return $this->success($salary, 'Детали зарплаты');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'employee_name' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'accrued' => 'nullable|numeric',
            'received' => 'nullable|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        $salary = Salary::create($validated);
        return $this->success($salary, 'Зарплата создана', 201);
    }

    public function update(Request $request, $id)
    {
        $salary = Salary::find($id);
        if (!$salary) {
            return $this->error('Запись не найдена', 404);
        }

        $validated = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'employee_name' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'accrued' => 'nullable|numeric',
            'received' => 'nullable|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        $salary->update($validated);
        return $this->success($salary, 'Зарплата обновлена');
    }

    public function destroy($id)
    {
        $salary = Salary::find($id);
        if (!$salary) {
            return $this->error('Запись не найдена', 404);
        }

        $salary->delete();
        return $this->success([], 'Зарплата удалена');
    }
}