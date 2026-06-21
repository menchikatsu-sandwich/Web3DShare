<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function models()
    {
        return $this->belongsToMany(Model3D::class, 'model_tags', 'tag_id', 'model_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
