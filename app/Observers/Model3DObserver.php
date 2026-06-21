<?php

namespace App\Observers;

use App\Models\Model3D;
use App\Services\SupabaseStorage;

class Model3DObserver
{
    public function deleting(Model3D $model)
    {
        SupabaseStorage::deleteAll($model);
    }
}
