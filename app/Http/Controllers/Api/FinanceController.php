<?php

namespace App\Http\Controllers\Api;

use App\Models\Finance;
use Illuminate\Http\Request;

class FinanceController extends BaseApiController
{
    public function index()
    {
        $finances = Finance::all();
        return $this->success($finances, 'Список финансов');
    }

    public function show($id)
    {
        $finance = Finance::find($id);
        if (!$finance) {
            return $this->error('Запись не найдена', 404);
        }
        return $this->success($finance, 'Детали записи');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'income' => 'nullable|numeric',
            'expense' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
            'note' => 'nullable|string',
        ]);

        $finance = Finance::create($validated);
        return $this->success($finance, 'Запись создана', 201);
    }

    public function update(Request $request, $id)
    {
        $finance = Finance::find($id);
        if (!$finance) {
            return $this->error('Запись не найдена', 404);
        }

        $validated = $request->validate([
            'date' => 'nullable|date',
            'income' => 'nullable|numeric',
            'expense' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
            'note' => 'nullable|string',
        ]);

        $finance->update($validated);
        return $this->success($finance, 'Запись обновлена');
    }

    public function destroy($id)
    {
        $finance = Finance::find($id);
        if (!$finance) {
            return $this->error('Запись не найдена', 404);
        }

        $finance->delete();
        return $this->success([], 'Запись удалена');
    }
}