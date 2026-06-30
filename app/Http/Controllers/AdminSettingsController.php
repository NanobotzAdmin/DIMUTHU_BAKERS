<?php

namespace App\Http\Controllers;

use App\Models\PlnDepartment;
use App\Models\UmBranch;
use App\Models\UmBranchType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $users = \App\Models\UmUser::all(); // Or use pagination if needed: UmUser::paginate(10);
        $roles = \App\Models\PmUserRole::all();

        return view('adminSettings.index', compact('users', 'roles'));
    }

    public function saveSettings(Request $request)
    {
        try {
            $settingsPath = public_path('system_config.json');
            $currentSettings = [];
            if (File::exists($settingsPath)) {
                $currentSettings = json_decode(File::get($settingsPath), true) ?? [];
            }

            $imageFields = [
                'logos.primary' => 'primary_logo',
                'logos.white' => 'white_logo',
                'logos.login' => 'login_logo',
                'logos.favicon' => 'favicon',
            ];

            // Exclude image fields from the data that will be merged into JSON
            $excludeFields = array_merge(['_token', '_method'], array_values($imageFields));
            $data = $request->except($excludeFields);

            // Clean up any previously corrupted flat keys in current settings
            foreach (array_values($imageFields) as $corruptKey) {
                if (isset($currentSettings[$corruptKey])) {
                    unset($currentSettings[$corruptKey]);
                }
            }

            foreach ($imageFields as $jsonKey => $requestKey) {
                if ($request->hasFile($requestKey)) {
                    $file = $request->file($requestKey);
                    $filename = $requestKey.'_'.time().'.'.$file->getClientOriginalExtension();
                    $destinationPath = public_path('images/systemImage');

                    if (! File::exists($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true);
                    }

                    $file->move($destinationPath, $filename);

                    // Update the data array for deep nested key
                    $keys = explode('.', $jsonKey);
                    $temp = &$data;
                    foreach ($keys as $key) {
                        if (! isset($temp[$key])) {
                            $temp[$key] = [];
                        }
                        $temp = &$temp[$key];
                    }
                    $data['logos'][explode('.', $jsonKey)[1]] = 'images/systemImage/'.$filename;
                }
            }

            // Merge data into current settings
            // We'll use a recursive merge to preserve structure
            $updatedSettings = array_replace_recursive($currentSettings, $data);

            File::put($settingsPath, json_encode($updatedSettings, JSON_PRETTY_PRINT));

            return back()->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update settings: '.$e->getMessage());
        }
    }

    public function fetchBranchTypes()
    {
        $types = UmBranchType::where('status', 1)->get();

        return response()->json($types);
    }

    public function fetchDepartments()
    {
        $departments = PlnDepartment::where('status', 1)->get();

        return response()->json($departments);
    }

    public function fetchBranches()
    {
        $branches = UmBranch::with(['type', 'departments'])->orderBy('id', 'desc')->get();

        // Manual Warehouse Object
        $warehouseIds = \Illuminate\Support\Facades\DB::table('um_branch_has_department')
            ->whereNull('um_branch_id')
            ->pluck('department_id');

        $warehouseDepts = PlnDepartment::whereIn('id', $warehouseIds)->get();

        $warehouse = [
            'id' => -1,
            'code' => 'WH-001',
            'name' => 'Warehouse',
            'status' => 1,
            'is_default' => 0,
            'type' => ['name' => 'Main Stock', 'id' => 0],
            'departments' => $warehouseDepts,
            'street_address' => 'Warehouse Address',
            'city' => 'Warehouse City',
            'province' => 'Warehouse Province',
            'contact_person' => 'Warehouse Contact Person',
            'contact_person_phone' => 'Warehouse Contact Person Phone',
            'cash_account' => 1,
            'bank_account' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ];

        // Return collection with Warehouse prepended
        $branches->prepend($warehouse);

        return response()->json($branches);
    }

    public function assignDepartments(Request $request)
    {
        // Adjust validation to allow -1
        $request->validate([
            'branch_id' => 'required', // Removed exists check to allow -1
            'department_ids' => 'array',
            'department_ids.*' => 'exists:pln_departments,id',
        ]);

        if ($request->branch_id == -1) {
            // Handle Warehouse (NULL branch_id)
            $newIds = $request->department_ids ?? [];

            // 1. Delete existing NULL mappings NOT in the new list
            \Illuminate\Support\Facades\DB::table('um_branch_has_department')
                ->whereNull('um_branch_id')
                ->whereNotIn('department_id', $newIds)
                ->delete();

            // 2. Insert new mappings that don't exist
            $existing = \Illuminate\Support\Facades\DB::table('um_branch_has_department')
                ->whereNull('um_branch_id')
                ->pluck('department_id')
                ->toArray();

            $toInsert = array_diff($newIds, $existing);
            $insertData = [];
            foreach ($toInsert as $deptId) {
                $insertData[] = [
                    'um_branch_id' => null,
                    'department_id' => $deptId,
                ];
            }

            if (! empty($insertData)) {
                \Illuminate\Support\Facades\DB::table('um_branch_has_department')->insert($insertData);
            }
        } else {
            // Check existence manually since we removed 'exists' validation
            $branch = UmBranch::find($request->branch_id);
            if (! $branch) {
                return response()->json(['errors' => ['branch_id' => ['Invalid branch selected']]], 422);
            }
            $branch->departments()->sync($request->department_ids ?? []);
        }

        return response()->json(['message' => 'Departments assigned successfully']);
    }

    public function storeBranch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:um_branch,code',
            'name' => 'required|string|max:100',
            'type_id' => 'required|exists:um_branch_type,id',
            'address.street' => 'required|string|max:255',
            'address.city' => 'required|string|max:100',
            'address.province' => 'required|string|max:100',
            'contact.person' => 'required|string|max:100',
            'contact.phone' => 'nullable|string|max:20',
            'gl_accounts.cash_account' => 'nullable|integer',
            'gl_accounts.inventory_account' => 'nullable|integer', // Not in DB currently
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $branch = new UmBranch;
        $branch->code = $request->code;
        $branch->name = $request->name;
        $branch->um_branch_type_id = $request->type_id;
        $branch->street_address = $request->input('address.street');
        $branch->city = $request->input('address.city');
        $branch->province = $request->input('address.province');
        $branch->contact_person = $request->input('contact.person');
        $branch->contact_person_phone = $request->input('contact.phone');
        $branch->cash_account = $request->input('gl_accounts.cash_account');
        $branch->bank_account = $request->input('gl_accounts.inventory_account');

        $branch->status = 1; // Active by default

        // Assuming Auth::id() is valid for um_user or we need a fallback/mapping
        // If strict foreign key, this might fail if Auth::user is not in um_user.
        // For now, using Auth::id().
        $branch->created_by = Auth::id() ?? 1;
        $branch->updated_by = Auth::id() ?? 1;

        $branch->save();

        return response()->json(['message' => 'Location added successfully', 'branch' => $branch], 201);
    }

    public function storeBranchType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:um_branch_type,name',
            'icon' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $branchType = new UmBranchType;
        $branchType->name = $request->name;
        $branchType->icon = $request->icon;
        $branchType->status = 1; // Active by default
        $branchType->save();

        return response()->json(['message' => 'Branch Type added successfully', 'branchType' => $branchType], 201);
    }

    public function setDefaultRaw(Request $request)
    {
        $branchId = $request->input('id');

        // Reset all branches to not default
        UmBranch::where('id', '!=', $branchId)->update(['is_default' => 0]);

        // Set the selected branch to default
        $branch = UmBranch::find($branchId);
        if ($branch) {
            $branch->is_default = 1;
            $branch->save();

            return response()->json(['message' => 'Default branch updated successfully']);
        }

        return response()->json(['message' => 'Branch not found'], 404);
    }

    public function toggleStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:um_branch,id',
            'status' => 'required|boolean',
        ]);

        $branch = UmBranch::find($request->id);
        $branch->status = $request->status;
        $branch->save();

        return response()->json(['message' => 'Branch status updated successfully']);
    }

    public function getProcessEmails()
    {
        try {
            $processes = \App\CommonVariables::$businessProcesses;
            // Only load users who have a valid email format in their email column and exclude roles 8 and 10
            $users = \App\Models\UmUser::where('is_active', 1)
                ->where('email', 'like', '%@%')
                ->whereNotIn('user_role_id', [8, 10])
                ->select('id', 'first_name', 'last_name', 'user_name', 'email')
                ->get();
            $configs = \App\Models\EmProcessHasEmailAddress::with('user:id,first_name,last_name,user_name,email')->get();

            return response()->json([
                'status' => true,
                'processes' => $processes,
                'users' => $users,
                'configs' => $configs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to load process email configurations: '.$e->getMessage(),
            ], 500);
        }
    }

    public function saveProcessEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|integer',
            'process_id' => 'required|integer',
            'um_user_id' => 'required|integer',
            'email_address' => 'required|string|max:150',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $statusVal = 1;
            if ($request->has('status')) {
                $statusVal = filter_var($request->status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }

            $config = \App\Models\EmProcessHasEmailAddress::updateOrCreate(
                [
                    'process_id' => $request->process_id,
                    'um_user_id' => $request->um_user_id,
                ],
                [
                    'email_address' => $request->email_address,
                    'status' => $statusVal,
                    'created_by' => Auth::id() ?? 1,
                    'updated_by' => Auth::id() ?? 1,
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Configuration saved successfully',
                'data' => $config->load('user:id,first_name,last_name,user_name,email'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to save configuration: '.$e->getMessage(),
            ], 500);
        }
    }

    public function deleteProcessEmail($id)
    {
        try {
            $config = \App\Models\EmProcessHasEmailAddress::findOrFail($id);
            $config->delete();

            return response()->json([
                'status' => true,
                'message' => 'Configuration deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete configuration: '.$e->getMessage(),
            ], 500);
        }
    }
}
