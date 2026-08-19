<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

//Отвечает за отображение публичных страниц сайта (главная, категории, товары).
class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function category($category)
    {
        // 1. Находим все товары в этой категории
        $products = Product::where('category', $category)->get();

        // 2. Сопоставляем ключ с человеческим названием
        $categoryNames = [
            'mangal' => 'Мангалы',
            'lavo4ki' => 'Лавочки',
            'kozirek' => 'Козырьки',
            'ogradki' => 'Оградки',
            'zabor' => 'Заборы',
            'vorota' => 'Ворота',
            'mebel' => 'Мебель',
            'reshetki' => 'Решетки',
            'melo4i' => 'Полезные мелочи',
        ];

        // 3. Получаем название или используем сам ключ
        $categoryName = $categoryNames[$category] ?? $category;

        // 4. Возвращаем страницу с данными
        return view('pages.category', compact('products', 'category', 'categoryName'));
    }

    public function product($category, $id)
{
    $product = Product::where('category', $category)->findOrFail($id);
    return view('pages.product', compact('product', 'category'));
}
}