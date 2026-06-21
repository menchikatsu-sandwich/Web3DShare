<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelView extends Model
{
    protected $table = 'model_views';

    public $timestamps = false;

    protected $fillable = [
        'model_id',
        'user_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function model3d()
    {
        return $this->belongsTo(Model3D::class, 'model_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
