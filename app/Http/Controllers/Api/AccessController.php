<?php

namespace App\Http\Controllers\Api;

use App\Models\Access;
use Illuminate\Http\Request;

class AccessController extends BaseApiController
{
    public function index()
    {
        $access = Access::all();
        return $this->success($access, 'Список прав доступа');
    }

    public function show($id)
    {
        $access = Access::find($id);
        if (!$access) {
            return $this->error('Запись не найдена', 404);
        }
        return $this->success($access, 'Детали записи');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'work_class' => 'nullable|integer',
            'access_code' => 'required|string|max:255|unique:access',
            'is_active' => 'nullable|boolean',
        ]);

        $access = Access::create($validated);
        return $this->success($access, 'Право доступа создано', 201);
    }

    public function update(Request $request, $id)
    {
        $access = Access::find($id);
        if (!$access) {
            return $this->error('Запись не найдена', 404);
        }

        $validated = $request->validate([
            'full_name' => 'string|max:255',
            'profession' => 'nullable|string|max:255',
            'work_class' => 'nullable|integer',
            'access_code' => 'string|max:255|unique:access,access_code,' . $id,
            'is_active' => 'nullable|boolean',
        ]);

        $access->update($validated);
        return $this->success($access, 'Право доступа обновлено');
    }

    public function destroy($id)
    {
        $access = Access::find($id);
        if (!$access) {
            return $this->error('Запись не найдена', 404);
        }

        $access->delete();
        return $this->success([], 'Право доступа удалено');
    }
}