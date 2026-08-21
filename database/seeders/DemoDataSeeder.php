<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Отключаем проверку внешних ключей
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Получаем все таблицы
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
              // Не трогаем служебные таблицы, сессии, кэш и пользователей
            if (!in_array($tableName, [
                'migrations',
                'personal_access_tokens',
                'failed_jobs',
                'password_reset_tokens',
                'users',
                'sessions',          // <--- НЕ ОЧИЩАЕМ
                'cache',             // <--- НЕ ОЧИЩАЕМ
                'cache_locks',       // <--- НЕ ОЧИЩАЕМ
                'jobs',              // <--- НЕ ОЧИЩАЕМ
                'job_batches',       // <--- НЕ ОЧИЩАЕМ
            ])) {
                DB::table($tableName)->truncate();
            }
        }
        
        // Включаем проверку внешних ключей
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Импортируем дамп
        $sql = file_get_contents(database_path('seeders/current_data.sql'));
        
        // Разбиваем на отдельные запросы и выполняем
        $queries = array_filter(explode(';', $sql));
        foreach ($queries as $query) {
            if (trim($query)) {
                DB::statement(trim($query));
            }
        }
        
        $this->command->info('✅ Демо-данные восстановлены из дампа!');
    }
}