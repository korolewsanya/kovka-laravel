<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeeController extends BaseApiController
{
    public function index()
    {
        $employees = Employee::all();
        return $this->success($employees, 'Список сотрудников');
    }

    public function show($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return $this->error('Сотрудник не найден', 404);
        }
        return $this->success($employee, 'Детали сотрудника');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email',
            'address' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'password' => 'required|string|min:6',
        ]);

        // Сохраняем пароль
        $password = $validated['password'];
        unset($validated['password']);
        
        // Создаем сотрудника
        $employee = Employee::create($validated);
        
        // Создаем или обновляем пользователя
        if ($employee->email) {
            // Проверяем, существует ли пользователь с таким email
            $user = User::where('email', $employee->email)->first();
            if ($user) {
                // Если существует - обновляем пароль
                $user->password = bcrypt($password);
                $user->save();
            } else {
                // Если не существует - создаем
                User::create([
                    'name' => $employee->full_name,
                    'email' => $employee->email,
                    'password' => bcrypt($password),
                    'role' => 'employee',
                ]);
            }
        }
        
        return $this->success($employee, 'Сотрудник создан', 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return $this->error('Сотрудник не найден', 404);
        }

        $validated = $request->validate([
            'full_name' => 'string|max:255',
            'position' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:employees,email,' . $id,
            'address' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'reset_password' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        $resetPassword = isset($validated['reset_password']) ? $validated['reset_password'] : false;
        $newPassword = isset($validated['password']) ? $validated['password'] : null;
        
        unset($validated['reset_password']);
        unset($validated['password']);
        
        // Обновляем сотрудника
        $employee->update($validated);

        // Сброс пароля
        if ($resetPassword && $employee->email) {
            $user = User::where('email', $employee->email)->first();
            if ($user) {
                $password = $newPassword ?: Str::random(8);
                $user->password = bcrypt($password);
                $user->save();
                
                return $this->success([
                    'employee' => $employee,
                    'new_password' => $password
                ], 'Пароль сброшен');
            } else {
                // Если пользователь не найден - создаем
                $password = $newPassword ?: Str::random(8);
                User::create([
                    'name' => $employee->full_name,
                    'email' => $employee->email,
                    'password' => bcrypt($password),
                    'role' => 'employee',
                ]);
                
                return $this->success([
                    'employee' => $employee,
                    'new_password' => $password
                ], 'Пользователь создан и пароль установлен');
            }
        }

        return $this->success($employee, 'Сотрудник обновлён');
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return $this->error('Сотрудник не найден', 404);
        }

        $employee->delete();
        return $this->success([], 'Сотрудник удалён');
    }
}