<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends BaseApiController
{
    // Получить все заказы
    public function index()
    {
        $orders = Order::with('product')->get();
        return $this->success($orders, 'Список заказов');
    }

    // Получить один заказ
    public function show($id)
    {
        $order = Order::with('product')->find($id);
        if (!$order) {
            return $this->error('Заказ не найден', 404);
        }
        return $this->success($order, 'Детали заказа');
    }

    // Создать заказ
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'comment' => 'nullable|string',
            'price' => 'nullable|numeric',
            'paid' => 'nullable|numeric',           
            'image' => 'nullable|string',
            'length' => 'nullable|string',          
            'width' => 'nullable|string',           
            'height' => 'nullable|string',          
            'progress' => 'nullable|string',        
            'order_date' => 'nullable|date',       
        ]);

        $order = Order::create([
            'product_id' => $validated['product_id'],
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'price' => $validated['price'] ?? 0,
            'paid' => $validated['paid'] ?? 0,              
            'image' => $validated['image'] ?? null,
            'length' => $validated['length'] ?? null,       
            'width' => $validated['width'] ?? null,        
            'height' => $validated['height'] ?? null,       
            'progress' => $validated['progress'] ?? null,   
            'order_date' => $validated['order_date'] ?? now(), 
            'status' => 'new',
        ]);

        return $this->success($order, 'Заказ создан', 201);
    }

    // Обновить заказ
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->error('Заказ не найден', 404);
        }

        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'comment' => 'nullable|string',
            'price' => 'nullable|numeric',
            'paid' => 'nullable|numeric',           
            'status' => 'nullable|in:new,in_progress,done,cancelled',
            'progress' => 'nullable|string',
            'image' => 'nullable|string',
            'length' => 'nullable|string',          
            'width' => 'nullable|string',           
            'height' => 'nullable|string',          
            'order_date' => 'nullable|date',       
        ]);

        $order->update($validated);
        return $this->success($order, 'Заказ обновлён');
    }

    // Удалить заказ
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->error('Заказ не найден', 404);
        }

        $order->delete();
        return $this->success([], 'Заказ удалён');
    }
}