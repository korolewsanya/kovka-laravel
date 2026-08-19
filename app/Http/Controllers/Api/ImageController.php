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
            $tags = $request->input('tags', 'image');
            
            $cleanName = preg_replace('/[^a-zA-Zа-яА-Я0-9._-]/u', '_', $tags);
            
            if (empty($cleanName)) {
                $cleanName = time();
            }
            
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $error = $_FILES['image']['error'] ?? 'неизвестно';
                \Log::error('Upload error code: ' . $error);
                return $this->error('Ошибка загрузки файла. Код: ' . $error, 400);
            }

            $file = $_FILES['image'];
            
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (empty($extension)) {
                $extension = 'jpg';
            }
            
            $filename = $cleanName . '.' . $extension;
            
            $destination = storage_path('app/public/products/' . $filename);
            
            if (file_exists($destination)) {
                $filename = $cleanName . '_' . time() . '.' . $extension;
                $destination = storage_path('app/public/products/' . $filename);
            }
            
            $dir = dirname($destination);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return $this->success([
                    'name' => $filename,
                    'url' => asset('storage/products/' . $filename),
                    'tags' => $tags,
                ], 'Изображение успешно загружено', 201);
            }
            
            return $this->error('Не удалось сохранить файл', 500);
            
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