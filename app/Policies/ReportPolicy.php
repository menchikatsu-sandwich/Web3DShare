<?php

namespace App\Policies;

use App\Models\Model3D;
use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function create(User $user, Model3D $model): bool
    {
        return $user->id !== $model->user_id;
    }

    public function update(User $user, Report $report): bool
    {
        return in_array($user->role, ['admin', 'moderator'], true);
    }
}
