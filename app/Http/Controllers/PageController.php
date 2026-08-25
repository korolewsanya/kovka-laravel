<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function category($category)
    {
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

        if (!isset($categoryNames[$category])) {
            abort(404, 'Категория не найдена');
        }

        $categoryName = $categoryNames[$category];

        return view('pages.category', compact('category', 'categoryName'));
    }

    public function product($category, $id)
    {
        $product = Product::where('category', $category)->findOrFail($id);
        return view('pages.product', compact('product', 'category'));
    }

    // МЕТОД ДЛЯ ПОИСКА
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        return view('pages.search', compact('query'));
    }
}