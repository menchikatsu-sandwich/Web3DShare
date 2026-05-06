<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model3D extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'models';

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'model_path',
        'thumbnail_path',
        'download_count',
        'stars_count',
        'view_count'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'model_tags', 'model_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'model_id');
    }

    public function stars()
    {
        return $this->hasMany(Star::class, 'model_id');
    }

    public function downloads()
    {
        return $this->hasMany(Download::class, 'model_id');
    }

    public function views()
    {
        return $this->hasMany(ModelView::class, 'model_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'model_id');
    }

    public function scopeMostViewed($q)
    {
        return $q->orderByDesc('view_count');
    }

    public function scopeMostDownloaded($q)
    {
        return $q->orderByDesc('download_count');
    }

    public function scopeMostStarred($q)
    {
        return $q->orderByDesc('stars_count');
    }

    public function modelUrl()
    {
        return \App\Services\SupabaseStorage::getModelUrl($this->model_path);
    }

    public function thumbnailUrl()
    {
        return \App\Services\SupabaseStorage::getThumbnailUrl($this->thumbnail_path);
    }
}