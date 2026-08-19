<?php

namespace App\Http\Controllers\Api;

use App\Models\WorkReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkReportController extends BaseApiController
{
    public function index()
    {
        $reports = WorkReport::with('employee')->get();
        
        $reports->transform(function ($report) {
            if ($report->employee) {
                $report->employee_name = $report->employee->full_name;
                $report->specialty = $report->employee->position;
            }
            return $report;
        });
        
        return $this->success($reports, 'Список отчётов');
    }

    public function show($id)
    {
        $report = WorkReport::with('employee')->find($id);
        if (!$report) {
            return $this->error('Отчёт не найден', 404);
        }
        
        if ($report->employee) {
            $report->employee_name = $report->employee->full_name;
            $report->specialty = $report->employee->position;
        }
        
        return $this->success($report, 'Детали отчёта');
    }

    public function store(Request $request)
    {
        try {
            \Log::info('=== STORE WORK REPORT ===');
            \Log::info('Request data:', $request->all());
            
            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'task' => 'nullable|string',
                'report' => 'nullable|string',
                'date' => 'nullable|date',
                'image' => 'nullable|string',
            ]);

            if (!empty($validated['image']) && strpos($validated['image'], 'data:image') === 0) {
                \Log::info('Processing base64 image');
                $validated['image'] = $this->handleBase64Image($validated['image']);
            } elseif (!empty($validated['image'])) {
                \Log::info('Image is just a filename: ' . $validated['image']);
            }

            $report = WorkReport::create($validated);
            \Log::info('Report created:', $report->toArray());
            
            return $this->success($report, 'Отчёт создан', 201);
            
        } catch (\Exception $e) {
            \Log::error('Store WorkReport error:', [
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
            \Log::info('=== UPDATE WORK REPORT ===');
            \Log::info('ID: ' . $id);
            \Log::info('Request data:', $request->all());
            
            $report = WorkReport::find($id);
            if (!$report) {
                return $this->error('Отчёт не найден', 404);
            }

            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'task' => 'nullable|string',
                'report' => 'nullable|string',
                'date' => 'nullable|date',
                'image' => 'nullable|string',
            ]);

            if (!empty($validated['image']) && strpos($validated['image'], 'data:image') === 0) {
                \Log::info('Processing new base64 image');
                
                if ($report->image) {
                    \Log::info('Deleting old image: ' . $report->image);
                    $this->deleteImage($report->image);
                }
                
                $validated['image'] = $this->handleBase64Image($validated['image']);
            } elseif (!empty($validated['image'])) {
                \Log::info('Image is just a filename: ' . $validated['image']);
            }

            $report->update($validated);
            \Log::info('Report updated:', $report->toArray());
            
            return $this->success($report, 'Отчёт обновлён');
            
        } catch (\Exception $e) {
            \Log::error('Update WorkReport error:', [
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
            \Log::info('=== DELETE WORK REPORT ===');
            \Log::info('ID: ' . $id);
            
            $report = WorkReport::find($id);
            if (!$report) {
                return $this->error('Отчёт не найден', 404);
            }

            if ($report->image) {
                \Log::info('Deleting image: ' . $report->image);
                $this->deleteImage($report->image);
            }

            $report->delete();
            \Log::info('Report deleted');
            
            return $this->success([], 'Отчёт удалён');
            
        } catch (\Exception $e) {
            \Log::error('Delete WorkReport error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return $this->error('Ошибка: ' . $e->getMessage(), 500);
        }
    }

    private function handleBase64Image($base64Image)
    {
        try {
            \Log::info('=== HANDLE BASE64 IMAGE ===');
            
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

            $fileName = 'work_report_' . Str::uuid() . '.' . $extension;
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

            return $fileName;

        } catch (\Exception $e) {
            \Log::error('Image upload error: ' . $e->getMessage());
            return $base64Image;
        }
    }

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