<?php

namespace App\Models;

// 1. Extend Authenticatable instead of Model
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UmUser extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // Check if user has permission
    public function hasPermission($componentId)
    {
        // Find the component by its ID string (e.g., 'create_module')
        $component = \App\Models\PmInterfaceComponent::where('component_id', $componentId)->first();

        if (!$component) {
            return false;
        }

        // Check for User specific permission
        $userPermission = \App\Models\UmUserHasInterfaceComponent::where('um_user_id', $this->id)
            ->where('pm_interface_components_id', $component->id)
            ->where('status', 1)
            ->first();

        if ($userPermission) {
            return true;
        }

        // Check for Role specific permission
        if ($this->user_role_id) {
            $rolePermission = \App\Models\PmUserRoleHasInterfaceComponent::where('pm_user_role_id', $this->user_role_id)
                ->where('pm_interface_components_id', $component->id)
                ->where('status', 1)
                ->first();

            if ($rolePermission) {
                return true;
            }
        }

        return false;
    }

    // 2. Define your specific table name
    protected $table = 'um_user';

    // 3. Allow these columns to be filled (mass assignment)
    protected $fillable = [
        'user_role_id',
        'first_name',
        'last_name',
        'user_name',
        'user_password',
        'contact_no',
        'is_active',
        'created_by',
        'updated_by',
        'current_branch_id',
    ];

    // 4. Hide password from array output
    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    // 5. Override default password column name ('password' -> 'user_password')
    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function branches()
    {
        return $this->belongsToMany(UmBranch::class, 'um_user_has_branch', 'um_user_id', 'um_branch_id');
    }

    public function currentBranch()
    {
        return $this->belongsTo(UmBranch::class, 'current_branch_id');
    }
    public function userRole()
    {
        return $this->belongsTo(PmUserRole::class, 'user_role_id');
    }
}