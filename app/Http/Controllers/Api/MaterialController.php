<?php

namespace App\Http\Controllers\Api;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends BaseApiController
{
    //Получает все материалы из БД. Возвращает их в формате JSON
    public function index()
    {
        $materials = Material::all();
        return $this->success($materials, 'Список материалов');
    }

    //Ищет материал по ID. Если найден - возвращает его. Если нет - возвращает ошибку 404
    public function show($id)
    {
        $material = Material::find($id);
        if (!$material) {
            return $this->error('Материал не найден', 404);
        }
        return $this->success($material, 'Детали материала');
    }

    //Создает новый материал, проверяет правильность полей. Возвращает созданный материал с кодом 201 (Created) 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'name' => 'required|string|max:255',
            'purchased' => 'nullable|numeric',
            'used' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'price_per_unit' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
        ]);

        $material = Material::create($validated);
        return $this->success($material, 'Материал создан', 201);
    }

    //Обновляем материал
    public function update(Request $request, $id)
    {
        //Находит материал по ID (если нет - ошибка 404)
        $material = Material::find($id);
        if (!$material) {
            return $this->error('Материал не найден', 404);
        }

        //Валидирует данные (поля не обязательны, можно обновить только часть)
        $validated = $request->validate([
            'date' => 'nullable|date',
            'name' => 'string|max:255',
            'purchased' => 'nullable|numeric',
            'used' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'price_per_unit' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
        ]);

        //Обновляет только переданные поля
        $material->update($validated);
        //Возвращает обновленный материал
        return $this->success($material, 'Материал обновлён');
    }

    //Удалить материал
    public function destroy($id)
    {
        // Находим материал
        $material = Material::find($id);
        if (!$material) {
            return $this->error('Материал не найден', 404);
        }

        // Удаляем материал
        $material->delete();
        return $this->success([], 'Материал удалён');
    }
}