<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends BaseApiController
{
    public function index()
    {
        $files = Storage::disk('public')->files('products');
        $images = array_map(function ($file) {
            return [
                'name' => basename($file),
                'url' => asset('storage/' . $file),
                'size' => Storage::disk('public')->size($file),
                'modified' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
            ];
        }, $files);

        return $this->success($images, 'Список изображений');
    }

    public function upload(Request $request)
    {
        try {
            // 1. Проверяем, пришел ли файл через стандартный метод Laravel
            if (!$request->hasFile('image')) {
                return $this->error('Файл не передан или произошла ошибка загрузки', 400);
            }

            // 2. ВАЛИДАЦИЯ: проверяем, что это картинка и она не больше 2 МБ
            $request->validate([
                'image' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            $file = $request->file('image');

            // 3. Проверяем, что файл загрузился без ошибок
            if (!$file->isValid()) {
                return $this->error('Ошибка загрузки: ' . $file->getErrorMessage(), 400);
            }

            $tags = $request->input('tags', 'image');
            $cleanName = preg_replace('/[^a-zA-Zа-яА-Я0-9._-]/u', '_', $tags);
            if (empty($cleanName)) {
                $cleanName = time();
            }

            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = $cleanName . '.' . $extension;

            // 4. Сохраняем файл через встроенный метод Laravel (в storage/app/public/products)
            $path = $file->storeAs('products', $filename, 'public');

            if ($path) {
                return $this->success([
                    'name' => $filename,
                    'url' => asset('storage/' . $path),
                    'tags' => $tags,
                ], 'Изображение успешно загружено', 201);
            }

            return $this->error('Не удалось сохранить файл', 500);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Exception $e) {
            \Log::error('Upload error:', ['message' => $e->getMessage()]);
            return $this->error('Ошибка: ' . $e->getMessage(), 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $filename = $request->input('filename');

            if (empty($filename)) {
                return $this->error('Имя файла не указано', 400);
            }

            $path = 'products/' . $filename;

            if (!Storage::disk('public')->exists($path)) {
                return $this->error('Файл не найден', 404);
            }

            Storage::disk('public')->delete($path);
            return $this->success([], 'Изображение успешно удалено');

        } catch (\Exception $e) {
            \Log::error('Delete error:', [
                'message' => $e->getMessage(),
                'filename' => $request->input('filename')
            ]);
            return $this->error('Ошибка удаления: ' . $e->getMessage(), 500);
        }
    }

    public function rename(Request $request)
    {
        try {
            $request->validate([
                'old_name' => 'required|string',
                'new_name' => 'required|string',
            ]);

            $oldName = $request->input('old_name');
            $newName = $request->input('new_name');

            $extension = pathinfo($oldName, PATHINFO_EXTENSION);
            if (!empty($extension) && !str_contains($newName, '.')) {
                $newName = $newName . '.' . $extension;
            }

            $oldPath = 'products/' . $oldName;
            $newPath = 'products/' . $newName;

            if (!Storage::disk('public')->exists($oldPath)) {
                return $this->error('Файл не найден', 404);
            }

            if (Storage::disk('public')->exists($newPath)) {
                return $this->error('Файл с таким именем уже существует', 409);
            }

            Storage::disk('public')->move($oldPath, $newPath);

            return $this->success([
                'new_name' => $newName,
                'url' => asset('storage/' . $newPath),
            ], 'Изображение успешно переименовано');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Ошибка валидации: ' . $e->getMessage(), 422);
        } catch (\Exception $e) {
            \Log::error('Rename error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->error('Ошибка переименования: ' . $e->getMessage(), 500);
        }
    }
}
