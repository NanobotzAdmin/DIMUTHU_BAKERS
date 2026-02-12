<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UmUser;
use App\Models\PmUserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\CommonVariables;
use App\Models\UmBranch;
use App\Models\PlnDepartment;

class UserManagementController extends Controller
{

    public function configurationIndex()
    {
        return view('userManagement.configuration');
    }
    public function userManageIndex()
    {
        $users = UmUser::join('pm_user_role', 'um_user.user_role_id', '=', 'pm_user_role.id')
            ->select('um_user.*', 'pm_user_role.user_role_name')
            ->orderBy('um_user.id', 'desc')
            ->paginate(10);

        $roles = PmUserRole::all();

        return view('userManagement.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:um_user,user_name', // Check uniqueness on user_name
            'user_role_id' => 'required|exists:pm_user_role,id',
            'contact_no' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $user = new UmUser();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->user_name = $request->email; // Map email to user_name
            // $user->email field removed

            $user->user_role_id = $request->user_role_id;
            $user->contact_no = $request->contact_no;
            $user->user_password = Hash::make($request->password ?? '12345678');
            $user->is_active = CommonVariables::$Active ?? 1;
            $user->created_by = session('user_id') ?? 1;
            $user->save();

            return response()->json(['success' => true, 'message' => 'User created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error creating user: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $user = UmUser::find($id);
        if ($user) {
            return response()->json(['success' => true, 'data' => $user]);
        }
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:um_user,user_name,' . $id, // Ignore current user for unique check
            'user_role_id' => 'required|exists:pm_user_role,id',
            'contact_no' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $user = UmUser::find($id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->user_name = $request->email;
            $user->user_role_id = $request->user_role_id;
            $user->contact_no = $request->contact_no;
            // Password update is usually separate, or optional. We'll skip it here unless requested.
            $user->updated_by = session('user_id') ?? 1;
            $user->save();

            return response()->json(['success' => true, 'message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating user: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:um_user,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data provided.'], 422);
        }

        try {
            $user = UmUser::find($request->id);
            $user->is_active = $request->is_active;
            $user->updated_by = session('user_id') ?? 1;
            $user->save();

            $statusText = $user->is_active ? 'activated' : 'deactivated';
            return response()->json(['success' => true, 'message' => "User successfully {$statusText}."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating status: ' . $e->getMessage()], 500);
        }
    }
    // --- User Roles Management ---

    public function fetchUserRoles()
    {
        $roles = PmUserRole::orderBy('id', 'desc')->get();
        return response()->json($roles);
    }

    public function storeUserRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_role_name' => 'required|string|max:255|unique:pm_user_role,user_role_name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $role = new PmUserRole();
            $role->user_role_name = $request->user_role_name;
            $role->remark1 = $request->description; // Mapping description to remark1 as per plan
            $role->created_by = session('user_id') ?? 1;
            $role->save();

            return response()->json(['success' => true, 'message' => 'User Role created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error creating user role: ' . $e->getMessage()], 500);
        }
    }

    public function updateUserRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:pm_user_role,id',
            'user_role_name' => 'required|string|max:255|unique:pm_user_role,user_role_name,' . $request->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $role = PmUserRole::find($request->id);
            $role->user_role_name = $request->user_role_name;
            $role->remark1 = $request->description;
            $role->updated_by = session('user_id') ?? 1;
            $role->save();

            return response()->json(['success' => true, 'message' => 'User Role updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating user role: ' . $e->getMessage()], 500);
        }
    }

    public function deleteUserRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:pm_user_role,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data.'], 422);
        }

        try {
            // Optional: Check if role is in use before deleting
            $isUsed = UmUser::where('user_role_id', $request->id)->exists();
            if ($isUsed) {
                return response()->json(['success' => false, 'message' => 'Cannot delete role because it is assigned to users.'], 400);
            }

            $role = PmUserRole::find($request->id);
            $role->delete();

            return response()->json(['success' => true, 'message' => 'User Role deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting user role: ' . $e->getMessage()], 500);
        }
    }
    // --- User Assignments (Branches & Departments) ---

    public function getAssignments($id)
    {
        $user = UmUser::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Fetch all active branches and departments
        $branches = UmBranch::where('status', 1)->get(['id', 'name']); // Assuming 'status' 1 is active
        $departments = PlnDepartment::where('status', 1)->get(['id', 'name']); // Assuming 'status' 1 is active

        // Fetch user's current assignments
        // We need to query the pivot tables directly or define relationships in UmUser model.
        // Since relationships might not exist in UmUser yet, let's query the tables directly for now to be safe, 
        // or better, defined relationships if they don't exist? 
        // Checking the plan, I can query DB tables or add relationships. 
        // Let's use DB facade for pivot tables if models relationships specifically for 'assignments' aren't there,
        // BUT `UmUser` might not have them. Let's look at UmUser model first? 
        // Creating relationships is cleaner. 
        // Wait, I didn't check UmUser model. I should check it.
        // For now, I'll use DB facade in the controller for the pivot data to avoid modifying UmUser model if not needed yet, 
        // OR just add the methods using DB.

        $assignedBranches = \Illuminate\Support\Facades\DB::table('um_user_has_branch')
            ->where('um_user_id', $id)
            ->pluck('um_branch_id')
            ->toArray();

        $assignedDepartments = \Illuminate\Support\Facades\DB::table('um_user_has_department')
            ->where('um_user_id', $id)
            ->pluck('department_id') // Correct column name from migration
            ->toArray();

        return response()->json([
            'success' => true,
            'branches' => $branches,
            'departments' => $departments,
            'assigned_branches' => $assignedBranches,
            'assigned_departments' => $assignedDepartments
        ]);
    }

    public function updateAssignments(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'branches' => 'array',
            'branches.*' => 'exists:um_branch,id',
            'departments' => 'array',
            'departments.*' => 'exists:pln_departments,id', // Table name from migration
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Sync Branches
            \Illuminate\Support\Facades\DB::table('um_user_has_branch')->where('um_user_id', $id)->delete();
            if ($request->has('branches')) {
                $branchInserts = [];
                foreach ($request->branches as $branchId) {
                    $branchInserts[] = [
                        'um_user_id' => $id,
                        'um_branch_id' => $branchId,
                        'created_by' => session('user_id') ?? 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($branchInserts)) {
                    \Illuminate\Support\Facades\DB::table('um_user_has_branch')->insert($branchInserts);
                }
            }

            // Sync Departments
            \Illuminate\Support\Facades\DB::table('um_user_has_department')->where('um_user_id', $id)->delete();
            if ($request->has('departments')) {
                $deptInserts = [];
                foreach ($request->departments as $deptId) {
                    $deptInserts[] = [
                        'um_user_id' => $id,
                        'department_id' => $deptId,
                        'created_by' => session('user_id') ?? 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($deptInserts)) {
                    \Illuminate\Support\Facades\DB::table('um_user_has_department')->insert($deptInserts);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['success' => true, 'message' => 'Assignments updated successfully']);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error updating assignments: ' . $e->getMessage()], 500);
        }
    }
}
