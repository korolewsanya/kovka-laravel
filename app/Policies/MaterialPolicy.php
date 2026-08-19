<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Material;

class MaterialPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Material $material): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;  // ВСЕ МОГУТ СОЗДАВАТЬ
    }

    public function update(User $user, Material $material): bool
    {
        return true;  // ВСЕ МОГУТ РЕДАКТИРОВАТЬ
    }

    public function delete(User $user, Material $material): bool
    {
        return true;  //ВСЕ МОГУТ УДАЛЯТЬ
    }
}