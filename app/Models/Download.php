<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'model_id',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    public function model3d()
    {
        return $this->belongsTo(Model3D::class, 'model_id');
    }
}
