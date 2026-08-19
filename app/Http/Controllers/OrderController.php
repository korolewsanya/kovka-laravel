<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

//Отвечает за обработку заказов (создание и отображение страницы успеха)
class OrderController extends Controller
{
    //Создание заказа
  public function store(Request $request)
{
     // Шаг 1: Валидация данных
    $request->validate([
        'name' => 'required|string|max:255',            // Имя обязательно
        'tel' => 'required|string|max:255',             // Телефон обязательно
        'email' => 'nullable|email|max:255',            // Email необязательно
        'coment' => 'nullable|string',                  // Комментарий необязательно
        'product_id' => 'required|exists:products,id',  // Товар должен существовать
    ]);

    // Шаг 2: Находим товар по id, выбранный при заказе, в базе данных
    $product = Product::findOrFail($request->product_id);

    // Шаг 3: Создаем заказ и сохраняем его в таблицу orders
    $order = Order::create([
        'product_id' => $product->id,           // ID товара
        'customer_name' => $request->name,      // Имя клиента
        'customer_phone' => $request->tel,      // Телефон
        'customer_email' => $request->email,    // Email (может быть null)
        'comment' => $request->coment,          // Комментарий (может быть null)
        'price' => $product->price,             // Цена из товара
        'status' => 'new',                      // Статус "Новый"
        'order_date' => now(),                  // Дата заказа (сейчас)
        'image' => $product->image,             // Фото из товара
    ]);

    // Шаг 4: Перенаправляем на страницу успеха
    return redirect()->route('order.success', $order->id);
}

    public function success($id)
    {
        $order = Order::with('product')->findOrFail($id);
        return view('orders.success', compact('order'));
    }
}