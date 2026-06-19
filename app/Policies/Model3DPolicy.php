<?php

namespace App\Policies;

use App\Models\Model3D;
use App\Models\User;

class Model3DPolicy
{
    public function update(User $user, Model3D $model): bool
    {
        return $user->id === $model->user_id;
    }

    public function delete(User $user, Model3D $model): bool
    {
        return $user->id === $model->user_id || in_array($user->role, ['admin', 'moderator'], true);
    }
}
