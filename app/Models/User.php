<?php

namespace App\Models;

use App\Services\SupabaseStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'upload_tier',
        'nickname',
        'profile_image_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'profile_image_url',
    ];

    public function models()
    {
        return $this->hasMany(Model3D::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function stars()
    {
        return $this->hasMany(Star::class);
    }

    public function starredModels()
    {
        return $this->belongsToMany(Model3D::class, 'stars', 'user_id', 'model_id');
    }

    public function reportsSubmitted()
    {
        return $this->hasMany(Report::class, 'reported_by');
    }

    public function reviewedReports()
    {
        return $this->hasMany(Report::class, 'reviewed_by');
    }

    public function verificationRequests()
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public function reviewedVerificationRequests()
    {
        return $this->hasMany(VerificationRequest::class, 'reviewed_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isModerator()
    {
        return $this->role === 'moderator';
    }

    public function isVerifiedUploader()
    {
        return $this->upload_tier === 'verified';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'moderator'], true);
    }

    public function hasUnlimitedUploads(): bool
    {
        return $this->isVerifiedUploader() || $this->isStaff();
    }

    public function profileImageUrl()
    {
        if (! $this->profile_image_path) {
            return null;
        }

        return SupabaseStorage::getProfileUrl($this->profile_image_path);
    }

    public function getProfileImageUrlAttribute()
    {
        return $this->profileImageUrl();
    }
}
