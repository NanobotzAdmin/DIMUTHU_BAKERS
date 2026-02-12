<?php

namespace App\Http\Controllers;


use App\Models\PmInterfaceTopic;
use App\Models\UmUser;
use App\Models\PmUserRole;
use App\Models\UmUserHasInterfaceComponent;
use App\Models\PmUserRoleHasInterfaceComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PrivilegeManagementController extends Controller
{
    public function index()
    {
        $topics = PmInterfaceTopic::with('interfaces.components')
            ->where('status', 1)
            ->get();

        $users = UmUser::where('is_active', 1)->get();
        $roles = PmUserRole::get(); // Assuming status column exists in pm_user_role based on other tables

        return view('privilegeManagement.index', compact('topics', 'users', 'roles'));
    }

    public function getPrivileges(Request $request)
    {
        $id = $request->id;
        $type = $request->type; // 'user' or 'role'

        if ($type === 'user') {
            $privileges = UmUserHasInterfaceComponent::where('um_user_id', $id)
                ->where('status', 1)
                ->pluck('pm_interface_components_id');
        } else {
            $privileges = PmUserRoleHasInterfaceComponent::where('pm_user_role_id', $id)
                ->where('status', 1)
                ->pluck('pm_interface_components_id');
        }

        return response()->json(['success' => true, 'privileges' => $privileges]);
    }

    public function updatePrivilege(Request $request)
    {
        try {
            $id = $request->id;
            $type = $request->type; // 'user' or 'role'
            $componentIds = $request->component_ids;

            DB::beginTransaction();

            if ($type === 'user') {
                UmUserHasInterfaceComponent::where('um_user_id', $id)->delete();
                if (!empty($componentIds)) {
                    foreach ($componentIds as $compId) {
                        UmUserHasInterfaceComponent::create([
                            'um_user_id' => $id,
                            'pm_interface_components_id' => $compId,
                            'status' => 1,
                            'created_by' => Auth::id() ?? 1,
                            'updated_by' => Auth::id() ?? 1,
                        ]);
                    }
                }
            } else {
                PmUserRoleHasInterfaceComponent::where('pm_user_role_id', $id)->delete();
                if (!empty($componentIds)) {
                    foreach ($componentIds as $compId) {
                        PmUserRoleHasInterfaceComponent::create([
                            'pm_user_role_id' => $id,
                            'pm_interface_components_id' => $compId,
                            'status' => 1,
                            'created_by' => Auth::id() ?? 1,
                            'updated_by' => Auth::id() ?? 1,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Privileges updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to update privileges']);
        }
    }
}
