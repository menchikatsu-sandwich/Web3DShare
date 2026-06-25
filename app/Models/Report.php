<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'model_id',
        'reported_by',
        'reviewed_by',
        'reason',
        'description',
        'owner_message',
        'owner_action',
        'owner_notified_at',
        'report_status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'owner_notified_at' => 'datetime',
    ];

    public function model3d()
    {
        return $this->belongsTo(Model3D::class, 'model_id')->withTrashed();
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
