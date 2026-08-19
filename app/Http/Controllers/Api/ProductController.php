<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends BaseApiController
{
    public function index()
    {
        $products = Product::all();
        return $this->success($products, 'Список товаров');
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->error('Товар не найден', 404);
        }
        return $this->success($product, 'Детали товара');
    }

    public function byCategory($category)
    {
        $products = Product::where('category', $category)->get();
        if ($products->isEmpty()) {
            return $this->error('Товары в этой категории не найдены', 404);
        }
        return $this->success($products, 'Товары категории: ' . $category);
    }

    public function store(Request $request)
    {
        try {
            \Log::info('=== STORE PRODUCT ===');
            \Log::info('Request data:', $request->all());

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'image' => 'nullable|string',
                'price' => 'nullable|numeric',
                'length' => 'nullable|string|max:255',
                'width' => 'nullable|string|max:255',
                'height' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            // Если есть изображение в base64, обрабатываем его
            if (!empty($validated['image']) && strpos($validated['image'], 'data:image') === 0) {
                \Log::info('Processing base64 image for product');
                $validated['image'] = $this->handleBase64Image($validated['image']);
            } elseif (!empty($validated['image'])) {
                \Log::info('Image is just a filename: ' . $validated['image']);
            }

            $product = Product::create($validated);
            \Log::info('Product created:', $product->toArray());

            return $this->success($product, 'Товар создан', 201);

        } catch (\Exception $e) {
            \Log::error('Store Product error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return $this->error('Ошибка: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            \Log::info('=== UPDATE PRODUCT ===');
            \Log::info('ID: ' . $id);
            \Log::info('Request data:', $request->all());

            $product = Product::find($id);
            if (!$product) {
                return $this->error('Товар не найден', 404);
            }

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',
                'image' => 'nullable|string',
                'price' => 'nullable|numeric',
                'length' => 'nullable|string|max:255',
                'width' => 'nullable|string|max:255',
                'height' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            // Если есть новое изображение в base64
            if (!empty($validated['image']) && strpos($validated['image'], 'data:image') === 0) {
                \Log::info('Processing new base64 image for product');

                // Удаляем старое изображение, если оно есть
                if ($product->image) {
                    \Log::info('Deleting old image: ' . $product->image);
                    $this->deleteImage($product->image);
                }

                $validated['image'] = $this->handleBase64Image($validated['image']);
            } elseif (!empty($validated['image'])) {
                \Log::info('Image is just a filename: ' . $validated['image']);
            }

            $product->update($validated);
            \Log::info('Product updated:', $product->toArray());

            return $this->success($product, 'Товар обновлён');

        } catch (\Exception $e) {
            \Log::error('Update Product error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return $this->error('Ошибка: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            \Log::info('=== DELETE PRODUCT ===');
            \Log::info('ID: ' . $id);

            $product = Product::find($id);
            if (!$product) {
                return $this->error('Товар не найден', 404);
            }

            if ($product->image) {
                \Log::info('Deleting image: ' . $product->image);
                $this->deleteImage($product->image);
            }

            $product->delete();
            \Log::info('Product deleted');

            return $this->success([], 'Товар удалён');

        } catch (\Exception $e) {
            \Log::error('Delete Product error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return $this->error('Ошибка: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Обработка base64 изображения
     */
   private function handleBase64Image($base64Image)
{
    try {
        \Log::info('=== HANDLE BASE64 IMAGE FOR PRODUCT ===');

        if (strpos($base64Image, 'data:image') !== 0) {
            \Log::info('Not base64, returning as is');
            return $base64Image;
        }

        $imageData = explode(',', $base64Image);
        if (count($imageData) < 2) {
            \Log::warning('Invalid base64 format');
            return $base64Image;
        }

        $imageString = base64_decode($imageData[1]);
        if (!$imageString) {
            \Log::warning('Failed to decode base64');
            return $base64Image;
        }

        $extension = 'jpg';
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $extension = $matches[1];
            if ($extension == 'jpeg') $extension = 'jpg';
        }
        \Log::info('Extension: ' . $extension);

        $fileName = 'product_' . Str::uuid() . '.' . $extension;
        $path = 'products/' . $fileName;

        \Log::info('File name: ' . $fileName);
        \Log::info('Path: ' . $path);

        $storagePath = Storage::disk('public')->path($path);
        \Log::info('Storage path: ' . $storagePath);

        $directory = dirname($storagePath);
        if (!is_dir($directory)) {
            \Log::info('Creating directory: ' . $directory);
            mkdir($directory, 0755, true);
        }

        $result = file_put_contents($storagePath, $imageString);
        \Log::info('File saved, bytes written: ' . $result);

        if (Storage::disk('public')->exists($path)) {
            \Log::info('File exists: ' . $path);
            \Log::info('File size: ' . Storage::disk('public')->size($path));
        } else {
            \Log::error('File NOT found after save: ' . $path);
        }

        // возвращаем с "products/"
        return 'products/' . $fileName;

    } catch (\Exception $e) {
        \Log::error('Image upload error: ' . $e->getMessage());
        return $base64Image;
    }
}
    /**
     * Удаление изображения
     */
    private function deleteImage($fileName)
    {
        try {
            \Log::info('Deleting image: ' . $fileName);
            $path = 'products/' . $fileName;

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                \Log::info('Image deleted: ' . $path);
            } else {
                \Log::warning('Image not found: ' . $path);
            }
        } catch (\Exception $e) {
            \Log::error('Image delete error: ' . $e->getMessage());
        }
    }
}