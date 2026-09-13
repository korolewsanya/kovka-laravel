<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔄 Начинаю сброс демо-данных...');

        try {
            // Очистка таблиц
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $tables = DB::select('SHOW TABLES');
            $clearedCount = 0;
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                if (!in_array($tableName, [
                    'migrations', 'personal_access_tokens', 'failed_jobs',
                    'password_reset_tokens', 'users', 'sessions',
                    'cache', 'cache_locks', 'jobs', 'job_batches'
                ])) {
                    DB::table($tableName)->truncate();
                    $clearedCount++;
                    $this->command->info("  ✅ Очищена: {$tableName}");
                }
            }
            $this->command->info("📊 Очищено таблиц: {$clearedCount}");

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Импорт данных
            $sqlFile = database_path('seeders/current_data.sql');
            $this->command->info("📁 Импорт из: {$sqlFile}");

            if (!file_exists($sqlFile)) {
                $this->command->error("❌ Файл не найден!");
                return;
            }

            $sql = file_get_contents($sqlFile);
            $this->command->info("📄 Размер файла: " . number_format(filesize($sqlFile) / 1024, 2) . " KB");

            // Разбиваем на запросы
            $queries = array_filter(explode(';', $sql));

            $executed = 0;
            $skipped = 0;

            foreach ($queries as $query) {
                $query = trim($query);
                if (empty($query)) continue;

                // Пропускаем CREATE, ALTER, SET и другие DDL
                if (preg_match('/^(CREATE|ALTER|DROP|SET|TRUNCATE|RENAME|COMMENT|ANALYZE|OPTIMIZE)/i', $query)) {
                    $skipped++;
                    continue;
                }

                // Пропускаем INSERT в системные таблицы
                if (preg_match('/INSERT INTO `(migrations|personal_access_tokens|failed_jobs|password_reset_tokens|users|sessions|cache|cache_locks|jobs|job_batches)`/i', $query)) {
                    $skipped++;
                    continue;
                }

                try {
                    DB::statement($query);
                    $executed++;
                } catch (\Exception $e) {
                    // Пропускаем дубликаты
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $skipped++;
                        continue;
                    }
                    $this->command->error("❌ Ошибка: " . $e->getMessage());
                    $this->command->error("   Запрос: " . substr($query, 0, 100) . "...");
                }
            }

            $this->command->info("✅ Выполнено INSERT: {$executed}");
            $this->command->info("⏭️  Пропущено: {$skipped}");

            // Проверка результата
            $ordersCount = DB::table('orders')->count();
            $productsCount = DB::table('products')->count();
            $this->command->info("📊 Заказов в БД: {$ordersCount}");
            $this->command->info("📊 Товаров в БД: {$productsCount}");

            if ($ordersCount > 0 && $productsCount > 0) {
                $this->command->info("✅ Демо-данные успешно восстановлены!");
            } else {
                $this->command->warn("⚠️ Данные не были загружены. Проверьте файл дампа.");
            }

        } catch (\Exception $e) {
            $this->command->error("❌ Критическая ошибка: " . $e->getMessage());
            $this->command->error("📍 Файл: " . $e->getFile() . ":" . $e->getLine());
        }
    }
}
