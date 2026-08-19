<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Order;
use App\Models\Employee;
use App\Models\WorkReport;
use App\Models\Access;
use App\Models\Salary;
use App\Models\Finance;
use App\Models\Material;
use App\Models\Code;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateLegacyData extends Command
{
    protected $signature = 'migrate:legacy';
    
    protected $description = 'Перенос данных из старой базы данных kovka в новую';

    public function handle()
    {
        $this->info('Начинаем перенос данных из старой базы...');

        try {
            DB::connection('old_mysql')->getPdo();
            $this->info('✅ Подключение к старой БД установлено.');
        } catch (\Exception $e) {
            $this->error('❌ Ошибка подключения к старой БД: ' . $e->getMessage());
            return 1;
        }

        $this->info('Очистка новых таблиц...');
        $this->clearTables();

        $this->migrateProducts();
        $this->migrateOrders();
        $this->migrateEmployees();
        $this->migrateWorkReports();
        $this->migrateAccess();
        $this->migrateSalaries();
        $this->migrateFinances();
        $this->migrateMaterials();
        $this->migrateCodes();

        $this->info('✅ Перенос данных завершен!');
        return 0;
    }

    private function clearTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::table('orders')->truncate();
        DB::table('employees')->truncate();
        DB::table('work_reports')->truncate();
        DB::table('access')->truncate();
        DB::table('salaries')->truncate();
        DB::table('finances')->truncate();
        DB::table('materials')->truncate();
        DB::table('codes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info('✅ Таблицы очищены.');
    }

    private function parseDate($date)
    {
        if (empty($date)) return null;

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return $date;
        if (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $date)) {
            try { return \Carbon\Carbon::createFromFormat('d.m.Y', $date)->format('Y-m-d'); } catch (\Exception $e) { return null; }
        }
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            try { return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d'); } catch (\Exception $e) { return null; }
        }
        if (preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $date)) {
            try { return \Carbon\Carbon::createFromFormat('Y/m/d', $date)->format('Y-m-d'); } catch (\Exception $e) { return null; }
        }

        try {
            $cleaned = preg_replace('/[^\d\s\-\.\/:]/', '', $date);
            if (!empty($cleaned)) return \Carbon\Carbon::parse($cleaned)->format('Y-m-d');
        } catch (\Exception $e) {}

        return null;
    }

    private function extractNumber($value)
    {
        if (empty($value)) return 0;
        if (is_numeric($value)) return (float) $value;
        if (is_string($value)) {
            preg_match_all('/\d+\.?\d*/', $value, $matches);
            if (!empty($matches[0])) {
                $lastNumber = end($matches[0]);
                return (float) $lastNumber;
            }
        }
        return 0;
    }

    private function migrateProducts()
    {
        $this->info('Перенос продуктов...');

        $tables = [
            'vorota' => 'vorota',
            'zabor' => 'zabor',
            'mangal' => 'mangal',
            'kozirek' => 'kozirek',
            'lavo4ki' => 'lavo4ki',
            'ogradki' => 'ogradki',
            'reshetki' => 'reshetki',
            'mebel' => 'mebel',
            'melo4i' => 'melo4i',
        ];

        $count = 0;
        foreach ($tables as $table => $category) {
            $oldRecords = DB::connection('old_mysql')->table($table)->get();
            foreach ($oldRecords as $old) {
                Product::create([
                    'name' => $old->izdelie ?? 'Без названия',
                    'category' => $category,
                    'image' => $old->image ?? null,
                    'length' => $old->Dlina ?? null,
                    'width' => $old->Shirina ?? null,
                    'height' => $old->Visota ?? null,
                    'price' => $old->Prise ?? null,
                    'is_active' => true,
                ]);
                $count++;
            }
            $this->info("  - {$table}: " . count($oldRecords) . " записей");
        }

        $this->info("✅ Продуктов перенесено: {$count}");
    }

    private function migrateOrders()
    {
        $this->info('Перенос заказов...');

        $oldOrders = DB::connection('old_mysql')->table('zakaz')->get();
        $count = 0;

        foreach ($oldOrders as $old) {
            $product = null;
            if (!empty($old->izdelie)) {
                $product = Product::where('name', 'LIKE', '%' . $old->izdelie . '%')->first();
            }

            Order::create([
                'product_id' => $product?->id,
                'customer_name' => $old->Name ?? null,
                'customer_phone' => $old->Tel ?? null,
                'customer_email' => $old->Email ?? null,
                'comment' => $old->Coment ?? null,
                'order_date' => $this->parseDate($old->date ?? null),
                'price' => $old->Prise ?? null,
                'paid' => $old->Pay ?? 0,
                'progress' => $old->Proces ?? null,
                'length' => $old->Dlina ?? null,
                'width' => $old->Shirina ?? null,
                'height' => $old->Visota ?? null,
                'status' => 'new',
            ]);
            $count++;
        }

        $this->info("✅ Заказов перенесено: {$count}");
    }

    private function migrateEmployees()
    {
        $this->info('Перенос сотрудников...');

        $oldEmployees = DB::connection('old_mysql')->table('workes')->get();
        $count = 0;

        foreach ($oldEmployees as $old) {
            Employee::create([
                'full_name' => $old->name ?? null,
                'specialty' => $old->spec ?? null,
                'phone' => $old->tel ?? null,
                'email' => $old->email ?? null,
                'address' => $old->adres ?? null,
                'hire_date' => $this->parseDate($old->data ?? null),
                'notes' => $old->proch ?? null,
                'is_active' => true,
            ]);
            $count++;
        }

        $this->info("✅ Сотрудников перенесено: {$count}");
    }

    private function migrateWorkReports()
    {
        $this->info('Перенос отчетов о работе...');

        $oldReports = DB::connection('old_mysql')->table('otchet')->get();
        $count = 0;

        foreach ($oldReports as $old) {
            $employee = null;
            if ($old->name) {
                $employee = Employee::where('full_name', 'LIKE', '%' . $old->name . '%')->first();
            }

            WorkReport::create([
                'employee_id' => $employee?->id,
                'employee_name' => $old->name ?? null,
                'specialty' => $old->prof ?? null,
                'work_class' => $old->class_work ?? null,
                'task' => $old->tz ?? null,
                'report' => $old->otchet ?? null,
                'date' => $this->parseDate($old->date ?? null),
                'image' => $old->image ?? null,
                'access_code' => $old->cod ?? null,
            ]);
            $count++;
        }

        $this->info("✅ Отчетов перенесено: {$count}");
    }

    private function migrateAccess()
    {
        $this->info('Перенос прав доступа...');

        $oldAccess = DB::connection('old_mysql')->table('dostup')->get();
        $count = 0;

        foreach ($oldAccess as $old) {
            Access::create([
                'full_name' => $old->name ?? null,
                'profession' => $old->prof ?? null,
                'work_class' => $old->class_work ?? null,
                'access_code' => $old->cod ?? null,
                'is_active' => true,
            ]);
            $count++;
        }

        $this->info("✅ Прав доступа перенесено: {$count}");
    }

    private function migrateSalaries()
    {
        $this->info('Перенос зарплат...');

        $oldSalaries = DB::connection('old_mysql')->table('zp')->get();
        $count = 0;

        foreach ($oldSalaries as $old) {
            $employee = null;
            if ($old->name) {
                $employee = Employee::where('full_name', 'LIKE', '%' . $old->name . '%')->first();
            }

            Salary::create([
                'employee_id' => $employee?->id,
                'employee_name' => $old->name ?? null,
                'specialty' => $old->spec ?? null,
                'date' => $this->parseDate($old->date ?? null),
                'accrued' => $old->nachis ?? 0,
                'received' => $old->poluch ?? 0,
                'description' => $old->names ?? null,
            ]);
            $count++;
        }

        $this->info("✅ Зарплат перенесено: {$count}");
    }

    private function migrateFinances()
    {
        $this->info('Перенос финансов...');

        $oldFinances = DB::connection('old_mysql')->table('fin')->get();
        $count = 0;

        foreach ($oldFinances as $old) {
            Finance::create([
                'date' => $this->parseDate($old->date ?? null),
                'income' => $old->dohod ?? 0,
                'expense' => $old->rashod ?? 0,
                'profit' => $old->prib ?? 0,
            ]);
            $count++;
        }

        $this->info("✅ Финансов перенесено: {$count}");
    }

    private function migrateMaterials()
    {
        $this->info('Перенос материалов (mater и rashod)...');

        $oldMater = DB::connection('old_mysql')->table('mater')->get();
        $count = 0;

        foreach ($oldMater as $old) {
            Material::create([
                'date' => $this->parseDate($old->date ?? null),
                'name' => $old->name ?? 'Без названия',
                'purchased' => $this->extractNumber($old->kup ?? 0),
                'used' => $this->extractNumber($old->izras ?? 0),
                'balance' => $this->extractNumber($old->ost ?? 0),
                'price_per_unit' => $this->extractNumber($old->prise ?? 0),
                'total_price' => $this->extractNumber($old->itogo ?? 0),
            ]);
            $count++;
        }
        $this->info("  - mater: {$count} записей");

        $oldRashod = DB::connection('old_mysql')->table('rashod')->get();
        $count2 = 0;

        foreach ($oldRashod as $old) {
            Material::create([
                'date' => $this->parseDate($old->date ?? null),
                'name' => $old->name ?? 'Без названия',
                'purchased' => $this->extractNumber($old->kup ?? 0),
                'used' => $this->extractNumber($old->izras ?? 0),
                'balance' => $this->extractNumber($old->ost ?? 0),
                'price_per_unit' => $this->extractNumber($old->prise ?? 0),
                'total_price' => $this->extractNumber($old->itogo ?? 0),
            ]);
            $count2++;
        }
        $this->info("  - rashod: {$count2} записей");

        $this->info("✅ Материалов перенесено: " . ($count + $count2));
    }

    private function migrateCodes()
    {
        $this->info('Перенос кодов доступа...');

        $oldCodes = DB::connection('old_mysql')->table('cod')->get();
        $count = 0;

        foreach ($oldCodes as $old) {
            Code::create([
                'code' => $old->cod ?? 0,
                'description' => $old->description ?? null,
            ]);
            $count++;
        }

        $this->info("✅ Кодов доступа перенесено: {$count}");
    }
}