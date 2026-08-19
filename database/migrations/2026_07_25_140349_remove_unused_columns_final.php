<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Удаляем столбцы из salaries
        Schema::table('salaries', function (Blueprint $table) {
            if (Schema::hasColumn('salaries', 'employee_name')) {
                $table->dropColumn('employee_name');
            }
            if (Schema::hasColumn('salaries', 'specialty')) {
                $table->dropColumn('specialty');
            }
        });

        // Удаляем столбцы из work_reports
        Schema::table('work_reports', function (Blueprint $table) {
            if (Schema::hasColumn('work_reports', 'employee_name')) {
                $table->dropColumn('employee_name');
            }
            if (Schema::hasColumn('work_reports', 'work_class')) {
                $table->dropColumn('work_class');
            }
        });

        // Удаляем specialty из employees (если есть)
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'specialty')) {
                $table->dropColumn('specialty');
            }
        });
    }

    public function down(): void
    {
        // Восстанавливаем столбцы (если нужно)
        Schema::table('salaries', function (Blueprint $table) {
            $table->string('employee_name')->nullable();
            $table->string('specialty')->nullable();
        });

        Schema::table('work_reports', function (Blueprint $table) {
            $table->string('employee_name')->nullable();
            $table->string('work_class')->nullable();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->string('specialty')->nullable();
        });
    }
};