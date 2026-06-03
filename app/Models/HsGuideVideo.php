<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HsGuideVideo extends Model
{
    protected $table = 'hs_guide_videos';

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'display_order',
        'status',
        'created_by',
        'updated_by',
    ];

    public function userRoles()
    {
        return $this->belongsToMany(
            PmUserRole::class,
            'hs_guide_video_has_user_roles',
            'hs_guide_video_id',
            'pm_user_role_id'
        );
    }

    public function createdByUser()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
}
