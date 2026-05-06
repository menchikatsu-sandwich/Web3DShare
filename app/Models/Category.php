<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function models()
    {
        return $this->hasMany(Model3D::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}