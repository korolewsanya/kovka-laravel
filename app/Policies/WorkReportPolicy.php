<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkReport;

class WorkReportPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Все видят список отчетов
    }

    public function view(User $user, WorkReport $report): bool
    {
        // Сотрудник видит только свои отчеты
        if ($user->role === 'admin') {
            return true;
        }
        return $report->employee_name === $user->name || $report->employee_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true; // Все могут создавать отчеты
    }

    public function update(User $user, WorkReport $report): bool
    {
        // Редактировать может только автор отчета или админ
        if ($user->role === 'admin') {
            return true;
        }
        return $report->employee_name === $user->name || $report->employee_id === $user->id;
    }

    public function delete(User $user, WorkReport $report): bool
    {
        // Удалять может только автор отчета или админ
        if ($user->role === 'admin') {
            return true;
        }
        return $report->employee_name === $user->name || $report->employee_id === $user->id;
    }
}