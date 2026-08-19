<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;  // ВСЕ МОГУТ СОЗДАВАТЬ
    }

    public function update(User $user, Order $order): bool
    {
        return true;  // ВСЕ МОГУТ РЕДАКТИРОВАТЬ
    }

    public function delete(User $user, Order $order): bool
    {
        return true;  // ВСЕ МОГУТ УДАЛЯТЬ
    }
}