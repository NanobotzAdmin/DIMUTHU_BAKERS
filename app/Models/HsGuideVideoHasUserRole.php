<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HsGuideVideoHasUserRole extends Model
{
    protected $table = 'hs_guide_video_has_user_roles';

    protected $fillable = [
        'hs_guide_video_id',
        'pm_user_role_id',
    ];

    public function guideVideo()
    {
        return $this->belongsTo(HsGuideVideo::class, 'hs_guide_video_id');
    }

    public function userRole()
    {
        return $this->belongsTo(PmUserRole::class, 'pm_user_role_id');
    }
}
