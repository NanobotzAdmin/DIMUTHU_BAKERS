<?php

namespace App\Http\Controllers;

use App\Models\AdAgent;
use App\Models\CmCustomer;
use App\Models\AdDailyLoad;
use App\Models\AdDailyLoadItem;
use App\Models\AdRoute;
use App\Models\DmDriver;
use App\Models\PmProductItem;
use App\Models\SmSuperviser;
use App\Models\StmBranchStock;
use App\Models\VmVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\StmBarcode;
use App\Models\StmBarcodesHistory;
use App\Models\AdCubusinessHasInvoice;
use App\Models\AdCubusinessInvoicePayments;
use App\Models\AdCustomerHasBusiness;
use App\Models\AdCubusinessHasProductItem;
use App\Models\AdDailyLoadHasCustomer;
use App\Models\AdCubusinessHasReturnProductItem;
use App\Models\AdCubusinessInvoicePaymentsHasInvoice;
use App\Models\AdAgentMonthlyTarget;
use App\Models\UmUser;
use App\Models\AdCreditNote;
use App\Models\AdCreditNoteHasProduct;
use App\Models\AdReturnProductStock;
use Illuminate\Support\Facades\Hash;

class ApiManagementController extends Controller
{
    // private function getAgentId()
    // {
    //     $user = auth()->user();
    //     if ($user && $user->user_role_id == 8) {
    //         $agent = AdAgent::where('user_id', $user->id)->first();
    //         return $agent ? $agent->id : null;
    //     }
    //     return null;
    // }

    // private function getSupervisorId()
    // {
    //     $user = auth()->user();
    //     if ($user && $user->user_role_id == 10) {
    //         $supervisor = SmSuperviser::where('user_id', $user->id)->first();
    //         return $supervisor ? $supervisor->id : null;
    //     }
    //     return null;
    // }

    // Driver Management
    public function getDrivers(Request $request)
    {
        try {
            $agentId = $this->getAgentId();
            $query = DmDriver::where('agent_id', $agentId);
            if ($request->query('available')) {
                $query->where(function ($q) {
                    $q->where('is_added', false)->orWhereNull('is_added');
                });
            }
            $drivers = $query->get();
            return response()->json([
                'status' => true,
                'data' => $drivers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch drivers'
            ], 500);
        }
    }

    public function createDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_name' => 'required|string|max:255',
            'licence_number' => 'required|string|max:255',
            'licences_expire_date' => 'required|date',
            'contact_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $agentId = $this->getAgentId();
            $driver = DmDriver::create([
                'agent_id' => $agentId,
                'driver_name' => $request->driver_name,
                'licence_number' => $request->licence_number,
                'licences_expire_date' => $request->licences_expire_date,
                'contact_number' => $request->contact_number,
                'status' => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Driver created successfully',
                'data' => $driver
            ], 201);
        } catch (\Exception $e) {
            Log::error('Driver Creation Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create driver'
            ], 500);
        }
    }

    public function updateDriver(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'driver_name' => 'required|string|max:255',
            'licence_number' => 'required|string|max:255',
            'licences_expire_date' => 'required|date',
            'contact_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $agentId = $this->getAgentId();
            $driver = DmDriver::where('id', $id)->where('agent_id', $agentId)->first();

            if (!$driver) {
                return response()->json([
                    'status' => false,
                    'message' => 'Driver not found'
                ], 404);
            }

            $driver->update([
                'driver_name' => $request->driver_name,
                'licence_number' => $request->licence_number,
                'licences_expire_date' => $request->licences_expire_date,
                'contact_number' => $request->contact_number,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Driver updated successfully',
                'data' => $driver
            ], 200);
        } catch (\Exception $e) {
            Log::error('Driver Update Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update driver'
            ], 500);
        }
    }

    // Supervisor Management
    public function getSupervisors(Request $request)
    {
        try {
            $agentId = $this->getAgentId();
            $query = SmSuperviser::where('agent_id', $agentId);
            if ($request->query('available')) {
                $query->where(function ($q) {
                    $q->where('is_added', false)->orWhereNull('is_added');
                });
            }
            $supervisors = $query->get();
            return response()->json([
                'status' => true,
                'data' => $supervisors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch supervisors'
            ], 500);
        }
    }

    public function createSupervisor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'superviser_code' => 'required|string|max:255|unique:sm_superviser,superviser_code',
            'superviser_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'nic_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create user account first
            $defaultPassword = 123456;
            $userName = strtolower(str_replace(' ', '', $request->superviser_code));

            // Check if username exists, append number if needed
            $baseUserName = $userName;
            $counter = 1;
            while (UmUser::where('user_name', $userName)->exists()) {
                $userName = $baseUserName . $counter;
                $counter++;
            }

            $user = UmUser::create([
                'first_name' => $request->superviser_name,
                'last_name' => '',
                'user_name' => $userName,
                'user_password' => Hash::make($defaultPassword),
                'contact_no' => $request->contact_number,
                'user_role_id' => 10,
                'is_active' => 1,
            ]);

            $agentId = $this->getAgentId();
            $supervisor = SmSuperviser::create([
                'user_id' => $user->id,
                'agent_id' => $agentId,
                'superviser_code' => $request->superviser_code,
                'superviser_name' => $request->superviser_name,
                'contact_number' => $request->contact_number,
                'nic_number' => $request->nic_number,
                'address' => $request->address,
                'status' => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Supervisor created successfully',
                'data' => $supervisor
            ], 201);
        } catch (\Exception $e) {
            Log::error('Supervisor Creation Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create supervisor'
            ], 500);
        }
    }

    public function updateSupervisor(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'superviser_code' => 'required|string|max:255|unique:sm_superviser,superviser_code,' . $id,
            'superviser_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'nic_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $agentId = $this->getAgentId();
            $supervisor = SmSuperviser::where('id', $id)->where('agent_id', $agentId)->first();

            if (!$supervisor) {
                return response()->json([
                    'status' => false,
                    'message' => 'Supervisor not found'
                ], 404);
            }

            $supervisor->update([
                'superviser_code' => $request->superviser_code,
                'superviser_name' => $request->superviser_name,
                'contact_number' => $request->contact_number,
                'nic_number' => $request->nic_number,
                'address' => $request->address,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Supervisor updated successfully',
                'data' => $supervisor
            ], 200);
        } catch (\Exception $e) {
            Log::error('Supervisor Update Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update supervisor'
            ], 500);
        }
    }

    // Vehicle Management
    public function getVehicles(Request $request)
    {
        try {
            $agentId = $this->getAgentId();
            $query = VmVehicle::where('agent_id', $agentId);
            if ($request->query('available')) {
                $query->where(function ($q) {
                    $q->where('is_added', false)->orWhereNull('is_added');
                });
            }
            $vehicles = $query->get();
            return response()->json([
                'status' => true,
                'data' => $vehicles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch vehicles'
            ], 500);
        }
    }

    public function createVehicle(Request $request)
    {
        Log::info('Starting vehicle creation', [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'has_file' => $request->hasFile('vehicle_image'),
            'file_error' => $request->file('vehicle_image') ? $request->file('vehicle_image')->getError() : 'none',
            'file_error_msg' => $request->file('vehicle_image') ? $request->file('vehicle_image')->getErrorMessage() : 'none',
        ]);

        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required|string|max:255',
            'engine_number' => 'nullable|string|max:255',
            'chassis_number' => 'nullable|string|max:255',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            Log::warning('Vehicle Creation Validation Failed', ['errors' => $validator->errors()->all(), 'request' => $request->all()]);
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Creating vehicle', ['request' => $request->except('vehicle_image'), 'has_image' => $request->hasFile('vehicle_image')]);
            $imagePath = null;
            if ($request->hasFile('vehicle_image')) {
                $file = $request->file('vehicle_image');
                Log::info('Processing vehicle image', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ]);
                $path = public_path('uploads/vehicles');

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);
                $imagePath = 'uploads/vehicles/' . $filename;
            }

            $agentId = $this->getAgentId();
            $vehicle = VmVehicle::create([
                'agent_id' => $agentId,
                'vehicle_number' => $request->vehicle_number,
                'engine_number' => $request->engine_number,
                'chassis_number' => $request->chassis_number,
                'vehicle_image' => $imagePath,
                'status' => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Vehicle created successfully',
                'data' => $vehicle
            ], 201);
        } catch (\Exception $e) {
            Log::error('Vehicle Creation Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateVehicle(Request $request, $id)
    {
        Log::info('Starting vehicle update', [
            'id' => $id,
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'has_file' => $request->hasFile('vehicle_image'),
            'file_error' => $request->file('vehicle_image') ? $request->file('vehicle_image')->getError() : 'none',
            'file_error_msg' => $request->file('vehicle_image') ? $request->file('vehicle_image')->getErrorMessage() : 'none',
        ]);

        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required|string|max:255',
            'engine_number' => 'nullable|string|max:255',
            'chassis_number' => 'nullable|string|max:255',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            Log::warning('Vehicle Update Validation Failed', ['id' => $id, 'errors' => $validator->errors()->all(), 'request' => $request->all()]);
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Updating vehicle', ['id' => $id, 'request' => $request->except('vehicle_image'), 'has_image' => $request->hasFile('vehicle_image')]);
            $agentId = $this->getAgentId();
            $vehicle = VmVehicle::where('id', $id)->where('agent_id', $agentId)->first();

            if (!$vehicle) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vehicle not found'
                ], 404);
            }

            if ($request->hasFile('vehicle_image')) {
                $file = $request->file('vehicle_image');
                Log::info('Processing updated vehicle image', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ]);
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = public_path('uploads/vehicles');

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $file->move($path, $filename);
                $vehicle->vehicle_image = 'uploads/vehicles/' . $filename;
            }

            $vehicle->vehicle_number = $request->vehicle_number;
            $vehicle->engine_number = $request->engine_number;
            $vehicle->chassis_number = $request->chassis_number;
            $vehicle->save();

            return response()->json([
                'status' => true,
                'message' => 'Vehicle updated successfully',
                'data' => $vehicle
            ], 200);
        } catch (\Exception $e) {
            Log::error('Vehicle Update Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    // Route Management
    public function getRoutes(Request $request)
    {
        try {
            $agentId = $this->getAgentId();
            $query = AdRoute::where('agent_id', $agentId);
            if ($request->query('available')) {
                $query->where(function ($q) {
                    $q->where('is_added', false)->orWhereNull('is_added');
                });
            }
            $routes = $query
                ->withCount('customers')
                ->with([
                    'latestDailyLoad' => function ($q) {
                        $q->with(['supervisor', 'driver', 'vehicle'])->where('load_date', date('Y-m-d'));
                    }
                ])
                ->when($this->getSupervisorId(), function ($q, $supervisorId) {
                    return $q->where('sm_superviser_id', $supervisorId);
                })
                ->get()
                ->map(function ($route) {
                    $load = $route->latestDailyLoad;
                    $route->supervisor_name = $load && $load->supervisor ? $load->supervisor->superviser_name : null;
                    $route->driver_name = $load && $load->driver ? $load->driver->driver_name : null;
                    $route->vehicle_number = $load && $load->vehicle ? $load->vehicle->vehicle_number : null;
                    unset($route->latestDailyLoad);
                    return $route;
                });

            return response()->json([
                'status' => true,
                'data' => $routes
            ]);
        } catch (\Exception $e) {
            Log::error('Routes Fetch Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch routes'
            ], 500);
        }
    }

    public function getRoute($id)
    {
        try {
            $agentId = $this->getAgentId();
            $route = AdRoute::where('id', $id)
                ->where('agent_id', $agentId)
                ->with(['customers.customer'])
                ->first();

            if (!$route) {
                return response()->json([
                    'status' => false,
                    'message' => 'Route not found'
                ], 404);
            }

            // Flatten customers to include business link IDs and underlying customer info
            $assignedCustomers = $route->customers->map(function ($biz) {
                // Fetch outstanding balance from B2B invoices
                $outstanding = \App\Models\AdCubusinessHasInvoice::where('ad_customer_has_business_id', $biz->id)
                    ->selectRaw('SUM(net_price - total_amount_paid) as balance')
                    ->first()->balance ?? 0;

                // Fetch last order amount from B2B invoices
                $lastOrder = \App\Models\AdCubusinessHasInvoice::where('ad_customer_has_business_id', $biz->id)
                    ->orderBy('created_at', 'desc')
                    ->value('net_price') ?? 0;

                return [
                    'id' => $biz->id, // This is ad_customer_has_business.id
                    'name' => $biz->business_name ?: ($biz->customer->name ?? 'N/A'),
                    'business_name' => $biz->business_name ?? '',
                    'phone' => $biz->contact_person_phone ?: ($biz->customer->phone ?? ''),
                    'address' => $biz->address ?: ($biz->customer->address ?? ''),
                    'latitude' => (float) ($biz->latitude ?: ($biz->customer->latitude ?? 0)),
                    'longitude' => (float) ($biz->longitude ?: ($biz->customer->longitude ?? 0)),
                    'contact_name' => $biz->contact_person_name ?: ($biz->customer->contact_person_name ?? ''),
                    'contact_phone' => $biz->contact_person_phone ?: ($biz->customer->phone ?? ''),
                    'image' => $biz->customer_image ? asset($biz->customer_image) : null,
                    'outstanding_amount' => (float) $outstanding,
                    'last_order_amount' => (float) $lastOrder,
                    'pivot' => [
                        'stop_sequence' => $biz->pivot->stop_sequence,
                        'distance_km' => $biz->pivot->distance_km,
                        'duration_minutes' => $biz->pivot->duration_minutes,
                    ]
                ];
            });

            // Load latest daily load with assignment details
            $latestLoad = $route->latestDailyLoad()
                ->with(['supervisor', 'driver', 'vehicle'])
                ->first();

            // Convert route to array and inject flattened customers
            $data = $route->toArray();
            $data['customers'] = $assignedCustomers;
            $data['latest_daily_load'] = $latestLoad;

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Route Fetch Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch route'
            ], 500);
        }
    }

    public function createRoute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_duration_hours' => 'nullable|numeric',
            'sm_superviser_id' => 'nullable|integer|exists:sm_superviser,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $agentId = $this->getAgentId();
            $supervisorId = $request->sm_superviser_id ?? $this->getSupervisorId();
            $route = AdRoute::create([
                'agent_id' => $agentId,
                'sm_superviser_id' => $supervisorId,
                'route_name' => $request->route_name,
                'description' => $request->description,
                'target_distance_km' => $request->target_distance_km,
                'target_duration_hours' => $request->target_duration_hours,
                'status' => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Route created successfully',
                'data' => $route
            ], 201);
        } catch (\Exception $e) {
            Log::error('Route Creation Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create route'
            ], 500);
        }
    }

    public function updateRoute(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_distance_km' => 'nullable|numeric',
            'target_duration_hours' => 'nullable|numeric',
            'status' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $agentId = $this->getAgentId();
            $route = AdRoute::where('id', $id)->where('agent_id', $agentId)->first();

            if (!$route) {
                return response()->json([
                    'status' => false,
                    'message' => 'Route not found'
                ], 404);
            }

            $route->update([
                'route_name' => $request->route_name,
                'description' => $request->description,
                'target_distance_km' => $request->target_distance_km,
                'target_duration_hours' => $request->target_duration_hours,
                'status' => $request->status ?? $route->status,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Route updated successfully',
                'data' => $route
            ], 200);
        } catch (\Exception $e) {
            Log::error('Route Update Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update route'
            ], 500);
        }
    }

    public function assignRouteResources(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $agentId = $this->getAgentId();
            $route = AdRoute::where('id', $id)->where('agent_id', $agentId)->first();

            if (!$route) {
                return response()->json([
                    'status' => false,
                    'message' => 'Route not found'
                ], 404);
            }

            // Sync customers with stop sequences
            $customerData = [];
            foreach ($request->customer_ids as $index => $customerId) {
                $customerData[$customerId] = ['stop_sequence' => $index + 1];
            }
            $route->customers()->sync($customerData);

            // Add to active daily load if route is_added is 1
            if ($route->is_added == 1) {
                $dailyLoad = $route->latestDailyLoad;
                if ($dailyLoad) {
                    foreach ($request->customer_ids as $index => $customerId) {
                        AdDailyLoadHasCustomer::updateOrCreate(
                            [
                                'daily_load_id' => $dailyLoad->id,
                                'ad_customer_has_business_id' => $customerId,
                            ],
                            [
                                'stop_sequence' => $index + 1,
                                'status' => 0,
                            ]
                        );
                    }
                    
                    // Remove customers from daily load that are no longer in the route
                    AdDailyLoadHasCustomer::where('daily_load_id', $dailyLoad->id)
                        ->whereNotIn('ad_customer_has_business_id', $request->customer_ids)
                        ->delete();
                }
            }

            // Update route_id and stop_sequence in ad_customer_has_business for assigned customers
            foreach ($request->customer_ids as $index => $customerId) {
                \App\Models\AdCustomerHasBusiness::where('id', $customerId)
                    ->update([
                        'route_id' => $id,
                        'stop_sequence' => $index + 1,
                    ]);
            }

            // Reload with relationships
            $route->load('customers');

            return response()->json([
                'status' => true,
                'message' => 'Route customers updated successfully',
                'data' => $route
            ], 200);
        } catch (\Exception $e) {
            Log::error('Route Customer Assignment Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to assign route customers'
            ], 500);
        }
    }

    // Daily Load Management
    public function getDailyLoads()
    {
        try {
            $agentId = $this->getAgentId();
            $loads = AdDailyLoad::where('agent_id', $agentId)
                ->with(['route', 'supervisor', 'driver', 'vehicle'])
                ->withCount('items')
                ->orderBy('load_date', 'desc')
                ->get()
                ->map(function ($load) {
                    return [
                        'id' => $load->id,
                        'load_date' => $load->load_date->format('Y-m-d'),
                        'route_name' => $load->route ? $load->route->route_name : null,
                        'route_code' => $load->route ? $load->route->route_code : null,
                        'supervisor_name' => $load->supervisor ? $load->supervisor->superviser_name : null,
                        'driver_name' => $load->driver ? $load->driver->driver_name : null,
                        'vehicle_number' => $load->vehicle ? $load->vehicle->vehicle_number : null,
                        'starting_mileage' => $load->starting_mileage,
                        'status' => $load->status,
                        'load_status' => $load->load_status,
                        'is_mark_as_loaded' => $load->is_mark_as_loaded,
                        'items_count' => $load->items_count,
                        'notes' => $load->notes,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $loads
            ]);
        } catch (\Exception $e) {
            Log::error('Daily Loads Fetch Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch daily loads'
            ], 500);
        }
    }

    public function getDailyLoad($id)
    {
        try {
            $agentId = $this->getAgentId();
            $load = AdDailyLoad::where('id', $id)
                ->where('agent_id', $agentId)
                ->with(['route', 'supervisor', 'driver', 'vehicle', 'items.product'])
                ->first();

            if (!$load) {
                return response()->json([
                    'status' => false,
                    'message' => 'Daily load not found'
                ], 404);
            }

            // Calculate return_qty for each item
            $invoiceIds = AdCubusinessHasInvoice::where('ad_daily_load_id', $load->id)->pluck('id');

            foreach ($load->items as $item) {
                $returnQty = AdCubusinessHasReturnProductItem::whereIn('ad_new_invoice_id', $invoiceIds)
                    ->where('pm_product_item_id', $item->product_item_id)
                    ->sum('return_quantity');

                $item->return_qty = (float) $returnQty;
            }

            // Get detailed return items for this load
            $returns = AdCubusinessHasReturnProductItem::whereIn('ad_new_invoice_id', $invoiceIds)
                ->with(['product', 'invoice.business.customer'])
                ->get();

            $load->returns = $returns;

            return response()->json([
                'status' => true,
                'data' => $load
            ]);
        } catch (\Exception $e) {
            Log::error('Daily Load Fetch Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch daily load'
            ], 500);
        }
    }

    public function createDailyLoad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route_id' => 'required|integer',
            'supervisor_id' => 'nullable|integer',
            'driver_id' => 'nullable|integer',
            'vehicle_id' => 'nullable|integer',
            'load_date' => 'required|date',
            'starting_mileage' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.product_item_id' => 'required_with:items|integer',
            'items.*.quantity' => 'required_with:items|numeric|min:0.001',
            'items.*.price' => 'required_with:items|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                $agentId = $this->getAgentId();
                $load = AdDailyLoad::create([
                    'agent_id' => $agentId,
                    'route_id' => $request->route_id,
                    'supervisor_id' => $request->supervisor_id,
                    'driver_id' => $request->driver_id,
                    'vehicle_id' => $request->vehicle_id,
                    'load_date' => $request->load_date,
                    'starting_mileage' => $request->starting_mileage,
                    'notes' => $request->notes,
                    'status' => 1,
                    'load_status' => 1, // 1: Loading
                    'is_mark_as_loaded' => false,
                ]);

                // Add product items if provided
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $item) {
                        $productItemId = $item['product_item_id'];
                        $requestedQty = $item['quantity'];

                        // 1. Find Branch Stock with available quantity
                        $branchStocks = StmBranchStock::where('agent_id', $agentId)
                            ->where('pm_product_item_id', $productItemId)
                            ->where('quantity', '>', 0)
                            ->get();

                        $totalAvailableQty = $branchStocks->sum('quantity');

                        if ($totalAvailableQty < $requestedQty) {
                            $productName = PmProductItem::find($productItemId)->product_name ?? 'Product';
                            throw new \Exception("Insufficient stock for $productName. Available: $totalAvailableQty");
                        }

                        $remainingQtyToDeduct = $requestedQty;
                        $branchStockIdsUsed = [];

                        // 2. Reduce Branch Stock across available records
                        foreach ($branchStocks as $stockRecord) {
                            if ($remainingQtyToDeduct <= 0)
                                break;

                            $qtyToDeduct = min($stockRecord->quantity, $remainingQtyToDeduct);
                            $stockRecord->decrement('quantity', $qtyToDeduct);
                            $remainingQtyToDeduct -= $qtyToDeduct;
                            $branchStockIdsUsed[] = $stockRecord->id;
                        }

                        // 3. Create Daily Load Item
                        // Note: Using the first branch stock ID used for relation if needed
                        AdDailyLoadItem::create([
                            'daily_load_id' => $load->id,
                            'product_item_id' => $productItemId,
                            'stm_branch_stock_id' => $branchStockIdsUsed[0] ?? null,
                            'loaded_qty' => $requestedQty,
                            'available_quantity' => $requestedQty,
                            'price' => $item['price'],
                            'total_value' => $requestedQty * $item['price'],
                        ]);

                        // 4. Update Barcodes
                        $barcodes = StmBarcode::where('agent_id', $agentId)
                            ->where('pm_product_item_id', $productItemId)
                            ->whereNull('ad_daily_load_id')
                            ->where('is_sold', false)
                            ->limit($requestedQty)
                            ->get();

                        foreach ($barcodes as $barcode) {
                            $barcode->update(['ad_daily_load_id' => $load->id]);

                            // 5. Record Barcode History
                            StmBarcodesHistory::create([
                                'barcode_id' => $barcode->id,
                                'created_by' => auth()->id(),
                                'action' => 'DAILY_LOAD_ASSIGNED',
                                'description' => "Assigned to Daily Load #{$load->id} (Date: {$load->load_date})",
                            ]);
                        }
                    }
                }

                // Mark related resources as is_added = true
                if ($request->route_id) {
                    AdRoute::where('id', $request->route_id)->update(['is_added' => true]);
                }
                if ($request->supervisor_id) {
                    SmSuperviser::where('id', $request->supervisor_id)->update(['is_added' => true]);
                }
                if ($request->driver_id) {
                    DmDriver::where('id', $request->driver_id)->update(['is_added' => true]);
                }
                if ($request->vehicle_id) {
                    VmVehicle::where('id', $request->vehicle_id)->update(['is_added' => true]);
                }

                $load->load(['route', 'supervisor', 'driver', 'vehicle', 'items.product']);

                return response()->json([
                    'status' => true,
                    'message' => 'Daily load created successfully',
                    'data' => $load
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Daily Load Creation Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create daily load'
            ], 500);
        }
    }

    public function updateDailyLoad(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'route_id' => 'nullable|integer',
            'supervisor_id' => 'nullable|integer',
            'driver_id' => 'nullable|integer',
            'vehicle_id' => 'nullable|integer',
            'load_date' => 'nullable|date',
            'starting_mileage' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'status' => 'nullable|integer',
            'load_status' => 'nullable|integer',
            'is_mark_as_loaded' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $agentId = $this->getAgentId();
            $load = AdDailyLoad::where('id', $id)->where('agent_id', $agentId)->first();

            if (!$load) {
                return response()->json([
                    'status' => false,
                    'message' => 'Daily load not found'
                ], 404);
            }

            $updateData = array_filter([
                'route_id' => $request->route_id,
                'supervisor_id' => $request->supervisor_id,
                'driver_id' => $request->driver_id,
                'vehicle_id' => $request->vehicle_id,
                'load_date' => $request->load_date,
                'starting_mileage' => $request->starting_mileage,
                'notes' => $request->notes,
            ], function ($v) {
                return !is_null($v);
            });

            if ($request->has('status')) {
                $updateData['status'] = $request->status;
            }
            if ($request->has('load_status')) {
                $updateData['load_status'] = $request->load_status;
            }
            if ($request->has('is_mark_as_loaded')) {
                $updateData['is_mark_as_loaded'] = $request->is_mark_as_loaded;
                // If marked as loaded, update load_status to 2 (Loaded) if it was 1 (Loading)
                if ($request->is_mark_as_loaded && $load->load_status == 1) {
                    $updateData['load_status'] = 2;
                }
            }

            // Handle is_added flag changes when resources are reassigned
            if ($request->has('route_id') && $load->route_id != $request->route_id) {
                if ($load->route_id) {
                    AdRoute::where('id', $load->route_id)->update(['is_added' => false]);
                }
                if ($request->route_id) {
                    AdRoute::where('id', $request->route_id)->update(['is_added' => true]);
                }
            }
            if ($request->has('supervisor_id') && $load->supervisor_id != $request->supervisor_id) {
                if ($load->supervisor_id) {
                    SmSuperviser::where('id', $load->supervisor_id)->update(['is_added' => false]);
                }
                if ($request->supervisor_id) {
                    SmSuperviser::where('id', $request->supervisor_id)->update(['is_added' => true]);
                }
            }
            if ($request->has('driver_id') && $load->driver_id != $request->driver_id) {
                if ($load->driver_id) {
                    DmDriver::where('id', $load->driver_id)->update(['is_added' => false]);
                }
                if ($request->driver_id) {
                    DmDriver::where('id', $request->driver_id)->update(['is_added' => true]);
                }
            }
            if ($request->has('vehicle_id') && $load->vehicle_id != $request->vehicle_id) {
                if ($load->vehicle_id) {
                    VmVehicle::where('id', $load->vehicle_id)->update(['is_added' => false]);
                }
                if ($request->vehicle_id) {
                    VmVehicle::where('id', $request->vehicle_id)->update(['is_added' => true]);
                }
            }

            $load->update($updateData);

            return response()->json([
                'status' => true,
                'message' => 'Daily load updated successfully',
                'data' => $load
            ], 200);
        } catch (\Exception $e) {
            Log::error('Daily Load Update Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update daily load'
            ], 500);
        }
    }

    public function addDailyLoadItems(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.product_item_id' => 'required|integer',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request, $id) {
                $agentId = $this->getAgentId();
                $load = AdDailyLoad::where('id', $id)->where('agent_id', $agentId)->first();

                if (!$load) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Daily load not found'
                    ], 404);
                }

                // Restore stock and clear barcode links for existing items before re-creating
                $existingItems = $load->items;
                foreach ($existingItems as $oldItem) {
                    if ($oldItem->stm_branch_stock_id) {
                        StmBranchStock::where('id', $oldItem->stm_branch_stock_id)->increment('quantity', $oldItem->loaded_qty);
                    }
                }

                // Clear barcode links and history for this daily load
                StmBarcode::where('ad_daily_load_id', $load->id)->update(['ad_daily_load_id' => null]);
                // (Note: History is usually additive, so we don't delete history, but if needed we could add another record)

                // Delete existing items
                $load->items()->delete();

                foreach ($request->items as $item) {
                    $productItemId = $item['product_item_id'];
                    $requestedQty = $item['quantity'];

                    $branchStock = StmBranchStock::where('agent_id', $agentId)
                        ->where('pm_product_item_id', $productItemId)
                        ->first();

                    if (!$branchStock || $branchStock->quantity < $requestedQty) {
                        $productName = PmProductItem::find($productItemId)->product_name ?? 'Product';
                        throw new \Exception("Insufficient stock for $productName. Available: " . ($branchStock->quantity ?? 0));
                    }

                    $availableQty = $branchStock->quantity;
                    $branchStock->decrement('quantity', $requestedQty);

                    AdDailyLoadItem::create([
                        'daily_load_id' => $load->id,
                        'product_item_id' => $productItemId,
                        'stm_branch_stock_id' => $branchStock->id,
                        'loaded_qty' => $requestedQty,
                        'available_quantity' => $availableQty,
                        'price' => $item['price'],
                        'total_value' => $requestedQty * $item['price'],
                    ]);

                    // Update Barcodes
                    $barcodes = StmBarcode::where('agent_id', $agentId)
                        ->where('pm_product_item_id', $productItemId)
                        ->whereNull('ad_daily_load_id')
                        ->where('is_sold', false)
                        ->limit($requestedQty)
                        ->get();

                    foreach ($barcodes as $barcode) {
                        $barcode->update(['ad_daily_load_id' => $load->id]);

                        StmBarcodesHistory::create([
                            'barcode_id' => $barcode->id,
                            'created_by' => auth()->id(),
                            'action' => 'DAILY_LOAD_ASSIGNED',
                            'description' => "Re-assigned to Daily Load #{$load->id} (Date: {$load->load_date})",
                        ]);
                    }
                }

                $load->load('items.product');

                return response()->json([
                    'status' => true,
                    'message' => 'Daily load items updated successfully',
                    'data' => $load
                ], 200);
            });
        } catch (\Exception $e) {
            Log::error('Daily Load Items Update Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update daily load items'
            ], 500);
        }
    }

    public function getProductItems()
    {
        try {
            $agentId = $this->getAgentId();
            // Get products that have stock for this agent
            $products = PmProductItem::whereIn('id', function ($query) use ($agentId) {
                $query->select('pm_product_item_id')
                    ->from('stm_branch_stock')
                    ->where('agent_id', $agentId)
                    ->groupBy('pm_product_item_id')
                    ->havingRaw('SUM(quantity) > 0');
            })
                ->get()
                ->map(function ($product) use ($agentId) {
                    $stockQty = StmBranchStock::where('agent_id', $agentId)
                        ->where('pm_product_item_id', $product->id)
                        ->sum('quantity');

                    return [
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'reference_number' => $product->reference_number,
                        'selling_price' => (float) ($product->selling_price ?? 0),
                        'wholesale_price' => (float) ($product->wholesale_price ?? 0),
                        'stock_quantity' => (float) $stockQty,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('Product Items Fetch Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch product items'
            ], 500);
        }
    }

    // ─── Customer Management ──────────────────────────────────────

    public function getCustomers()
    {
        try {
            $agentId = $this->getAgentId();

            // Join with ad_customer_has_business to get the business link ID
            // and filter by the current agent
            $customers = \App\Models\AdCustomerHasBusiness::with('customer:id,name,phone,address')
                ->where('agent_id', $agentId)
                ->when($this->getSupervisorId(), function ($q, $supervisorId) {
                    return $q->where('sm_superviser_id', $supervisorId);
                })
                ->get()
                ->map(function ($item) {
                    $lastOrderDate = \App\Models\AdCubusinessHasInvoice::where('ad_customer_has_business_id', $item->id)
                        ->orderBy('created_at', 'desc')
                        ->value('created_at');

                    return [
                        'id' => $item->id, // This is the ad_customer_has_business.id
                        'name' => $item->business_name ?: ($item->customer->name ?? 'N/A'),
                        'business_name' => $item->business_name ?? '',
                        'phone' => $item->contact_person_phone ?: ($item->customer->phone ?? ''),
                        'address' => $item->address ?: ($item->customer->address ?? ''),
                        'latitude' => (float) ($item->latitude ?: ($item->customer->latitude ?? 0)),
                        'longitude' => (float) ($item->longitude ?: ($item->customer->longitude ?? 0)),
                        'contact_name' => $item->contact_person_name ?: ($item->customer->contact_person_name ?? ''),
                        'contact_phone' => $item->contact_person_phone ?: ($item->customer->phone ?? ''),
                        'image' => $item->customer_image ? asset($item->customer_image) : null,
                        'last_order_date' => $lastOrderDate ? \Carbon\Carbon::parse($lastOrderDate)->format('Y-m-d') : null,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $customers
            ]);
        } catch (\Exception $e) {
            Log::error('Customers Fetch Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch customers'
            ], 500);
        }
    }

    public function syncRouteCustomers(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customers' => 'required|array',
            'customers.*.customer_id' => 'required|integer',
            'customers.*.stop_sequence' => 'nullable|integer',
            'customers.*.distance_km' => 'nullable|numeric',
            'customers.*.duration_minutes' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $agentId = $this->getAgentId();
            $route = AdRoute::where('id', $id)->where('agent_id', $agentId)->first();

            if (!$route) {
                return response()->json([
                    'status' => false,
                    'message' => 'Route not found'
                ], 404);
            }

            return DB::transaction(function () use ($request, $route, $id) {
                // Build sync data: [customer_id => pivot_data]
                $syncData = [];
                foreach ($request->customers as $item) {
                    $syncData[$item['customer_id']] = [
                        'stop_sequence' => $item['stop_sequence'] ?? null,
                        'distance_km' => $item['distance_km'] ?? null,
                        'duration_minutes' => $item['duration_minutes'] ?? null,
                    ];
                }

                $changes = $route->customers()->sync($syncData);

                // Add to active daily load if route is_added is 1
                if ($route->is_added == 1) {
                    $dailyLoad = $route->latestDailyLoad;
                    if ($dailyLoad) {
                        $customerIds = array_keys($syncData);
                        foreach ($request->customers as $item) {
                            AdDailyLoadHasCustomer::updateOrCreate(
                                [
                                    'daily_load_id' => $dailyLoad->id,
                                    'ad_customer_has_business_id' => $item['customer_id'],
                                ],
                                [
                                    'stop_sequence' => $item['stop_sequence'] ?? 1,
                                    'status' => 0,
                                    'distance_km' => $item['distance_km'] ?? null,
                                ]
                            );
                        }
                        
                        // Remove customers from daily load that are no longer in the route
                        AdDailyLoadHasCustomer::where('daily_load_id', $dailyLoad->id)
                            ->whereNotIn('ad_customer_has_business_id', $customerIds)
                            ->delete();
                    }
                }

                // Update route_id and stop_sequence in ad_customer_has_business for assigned customers
                foreach ($request->customers as $item) {
                    AdCustomerHasBusiness::where('id', $item['customer_id'])
                        ->update([
                            'route_id' => $id,
                            'stop_sequence' => $item['stop_sequence'] ?? null,
                        ]);
                }

                // Nullify route_id and stop_sequence for detached customers
                if (!empty($changes['detached'])) {
                    AdCustomerHasBusiness::whereIn('id', $changes['detached'])
                        ->update([
                            'route_id' => null,
                            'stop_sequence' => null,
                        ]);
                }

                $route->load('customers.customer');

                // Flatten for response
                $route->assigned_customers = $route->customers->map(function ($biz) {
                    return [
                        'id' => $biz->id,
                        'name' => $biz->business_name ?: ($biz->customer->name ?? 'N/A'),
                        'phone' => $biz->contact_person_phone ?: ($biz->customer->phone ?? ''),
                        'address' => $biz->address ?: ($biz->customer->address ?? ''),
                        'pivot' => [
                            'stop_sequence' => $biz->pivot->stop_sequence,
                            'distance_km' => $biz->pivot->distance_km,
                            'duration_minutes' => $biz->pivot->duration_minutes,
                        ]
                    ];
                });
                $route->customers = $route->assigned_customers;
                unset($route->assigned_customers);

                return response()->json([
                    'status' => true,
                    'message' => 'Route customers updated successfully',
                    'data' => $route
                ], 200);
            });
        } catch (\Exception $e) {
            Log::error('Route Customers Sync Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update route customers'
            ], 500);
        }
    }

    public function startTrip(Request $request, $id)
    {
        try {
            $supervisorId = $this->getSupervisorId();
            if (!$supervisorId) {
                return response()->json(['status' => false, 'message' => 'Supervisor not found'], 403);
            }

            $dailyLoad = AdDailyLoad::where('id', $id)
                ->where('supervisor_id', $supervisorId)
                ->first();

            if (!$dailyLoad) {
                return response()->json(['status' => false, 'message' => 'Daily load not found'], 404);
            }

            if ($dailyLoad->load_status != 2 && $dailyLoad->load_status != 3) {
                return response()->json(['status' => false, 'message' => 'Invalid load status to start trip. Must be accepted (2)'], 400);
            }

            if ($dailyLoad->load_status == 2) {
                $dailyLoad->load_status = 3; // Trip started
                $dailyLoad->save();

                if ($dailyLoad->route) {
                    $routeCustomers = \Illuminate\Support\Facades\DB::table('ad_route_has_customers')
                        ->where('route_id', $dailyLoad->route_id)
                        ->get();

                    foreach ($routeCustomers as $routeCustomer) {
                        \App\Models\AdDailyLoadHasCustomer::firstOrCreate(
                            [
                                'daily_load_id' => $dailyLoad->id,
                                'ad_customer_has_business_id' => $routeCustomer->ad_customer_has_business_id,
                            ],
                            [
                                'stop_sequence' => $routeCustomer->stop_sequence ?? 0,
                                'status' => 0, // 0 = pending
                                'distance_km' => $routeCustomer->distance_km ?? null,
                            ]
                        );
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Trip started successfully'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Start trip error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to start trip'], 500);
        }
    }

    /**
     * Get agent dashboard statistics and data.
     */
    public function getAgentDashboard()
    {
        try {
            $agentId = $this->getAgentId();
            if (!$agentId) {
                return response()->json(['status' => false, 'message' => 'Agent not found'], 403);
            }

            $today = date('Y-m-d');
            $year = date('Y');
            $month = date('n');

            $agent = AdAgent::find($agentId);

            // 1. Today's Sales
            $todaySales = AdCubusinessHasInvoice::where('created_at', '>=', $today)
                ->whereHas('business', function ($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })
                ->sum('net_price');

            // 2. Total Customers
            $totalCustomers = AdCustomerHasBusiness::where('agent_id', $agentId)->count();

            // 3. Monthly Stats (Target & Commission)
            $monthlyTarget = AdAgentMonthlyTarget::where('agent_id', $agentId)
                ->where('target_year', $year)
                ->where('target_month', $month)
                ->first();

            $targetAmount = $monthlyTarget ? (float) $monthlyTarget->monthly_sales_target : 0;
            $commissionAmount = $monthlyTarget ? (float) $monthlyTarget->monthly_commission : 0;

            // Calculate achieved sales for this month
            $monthStart = date('Y-m-01');
            $achievedSales = AdCubusinessHasInvoice::where('created_at', '>=', $monthStart)
                ->whereHas('business', function ($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })
                ->sum('net_price');

            // 4. Recent Visits (Latest 5 invoices as a proxy for visits)
            $recentVisits = AdCubusinessHasInvoice::whereHas('business', function ($q) use ($agentId) {
                $q->where('agent_id', $agentId);
            })
                ->with('business.customer')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'customer_name' => $invoice->business->business_name ?: ($invoice->business->customer->name ?? 'N/A'),
                        'amount' => (float) $invoice->net_price,
                        'time' => $invoice->created_at->format('h:i A'),
                        'date' => $invoice->created_at->format('Y-m-d'),
                        'status' => 'Order placed'
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => [
                    'agent_id' => (int) $agentId,
                    'stats' => [
                        'today_sales' => (float) $todaySales,
                        'total_customers' => $totalCustomers,
                        'commission' => (float) $commissionAmount,
                    ],
                    'target' => [
                        'monthly_target' => $targetAmount,
                        'achieved_sales' => (float) $achievedSales,
                        'progress_percentage' => $targetAmount > 0 ? min(100, round(($achievedSales / $targetAmount) * 100)) : 0,
                    ],
                    'credit' => [
                        'credit_limit' => $agent ? (float) $agent->credit_limit : 0,
                        'outstanding_balance' => $agent ? (float) $agent->outstanding_balance : 0,
                        'credit_period_days' => $agent ? (int) $agent->credit_period_days : 0,
                        'agent_type' => $agent ? (int) $agent->agent_type : 0,
                    ],
                    'recent_visits' => $recentVisits
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Fetch agent dashboard error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch dashboard data'], 500);
        }
    }

    public function getSupervisorDashboard()
    {
        try {
            $supervisorId = $this->getSupervisorId();
            if (!$supervisorId) {
                return response()->json(['status' => false, 'message' => 'Supervisor not found'], 403);
            }

            // Get active load for supervisor
            $activeLoad = AdDailyLoad::where('supervisor_id', $supervisorId)
                ->where('status', 1)
                ->whereIn('load_status', [2, 3])
                ->with(['route'])
                ->first();

            $loadCount = 0;
            $loadStatus = 0;
            $dailyLoadId = null;

            if ($activeLoad) {
                $loadStatus = (int) $activeLoad->load_status;
                $dailyLoadId = $activeLoad->id;
                $loadCount = AdDailyLoadItem::where('daily_load_id', $activeLoad->id)
                    ->sum('available_quantity');
            }

            // Format customers for dashboard
            $nextCustomers = [];
            if ($activeLoad && $loadStatus >= 3) {
                $dailyLoadCustomers = \App\Models\AdDailyLoadHasCustomer::where('daily_load_id', $activeLoad->id)
                    ->orderBy('stop_sequence')
                    ->with('business.customer')
                    ->get();

                foreach ($dailyLoadCustomers as $dlc) {
                    // Assuming outstanding balance needs to be fetched for the customer via the business relationship
                    $outstanding = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $dlc->ad_customer_has_business_id)
                        ->selectRaw('SUM(net_price - total_amount_paid) as balance')
                        ->first()->balance ?? 0;

                    $nextCustomers[] = [
                        'id' => $dlc->ad_customer_has_business_id,
                        'name' => $dlc->business->business_name ?: ($dlc->business->customer->name ?? 'N/A'),
                        'photo' => $dlc->business->customer_image ? asset($dlc->business->customer_image) : 'https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?w=200&h=200&fit=crop',
                        'address' => $dlc->business->address ?: ($dlc->business->customer->address ?? ''),
                        'outstanding' => (float) $outstanding,
                        'status' => $dlc->status == 0 ? 'pending' : ($dlc->status == 1 ? 'completed' : 'skipped'),
                        'sequence' => $dlc->stop_sequence,
                    ];
                }
            }

            // Real Stats Calculation
            $achieved = 0;
            $completedVisits = 0;
            $totalVisits = 0;
            $collection = 0;

            if ($activeLoad) {
                $achieved = \App\Models\AdCubusinessHasInvoice::where('ad_daily_load_id', $activeLoad->id)
                    ->sum('net_price');

                $totalVisits = \App\Models\AdDailyLoadHasCustomer::where('daily_load_id', $activeLoad->id)->count();
                $completedVisits = \App\Models\AdDailyLoadHasCustomer::where('daily_load_id', $activeLoad->id)
                    ->where('status', 1)
                    ->count();

                $invoiceIds = \App\Models\AdCubusinessHasInvoice::where('ad_daily_load_id', $activeLoad->id)
                    ->pluck('id');
                $collection = \App\Models\AdCubusinessInvoicePayments::whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)
                    ->sum('amount');
            }

            $todayStats = [
                'target' => 50000, // Still mocked as no target table found
                'achieved' => (float) $achieved,
                'visits' => [
                    'completed' => $completedVisits,
                    'total' => $totalVisits
                ],
                'collection' => (float) $collection,
                'returns' => 0 // Mocked for now
            ];

            // Total unique customers assigned to all routes of this supervisor
            $allRouteIds = AdRoute::where('sm_superviser_id', $supervisorId)->pluck('id');
            $totalCustomerCount = DB::table('ad_route_has_customers')
                ->whereIn('route_id', $allRouteIds)
                ->distinct('ad_customer_has_business_id')
                ->count();

            return response()->json([
                'status' => true,
                'data' => [
                    'activeLoadId' => $dailyLoadId,
                    'loadStatus' => $loadStatus,
                    'todayStats' => $todayStats,
                    'nextCustomers' => array_slice($nextCustomers, 0, 5),
                    'quickActions' => [
                        'load_count' => (int) $loadCount,
                        'route_count' => $activeLoad ? 1 : 0,
                        'customer_count' => $totalCustomerCount
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('fetch supervisor dashboard: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch dashboard data'], 500);
        }
    }

    public function getSupervisorRoute()
    {
        try {
            $supervisorId = $this->getSupervisorId();
            if (!$supervisorId) {
                return response()->json(['status' => false, 'message' => 'Supervisor not found'], 403);
            }

            // Get active load for supervisor
            $activeLoad = AdDailyLoad::where('supervisor_id', $supervisorId)
                ->where('status', 1)
                ->whereIn('load_status', [2, 3])
                ->with(['route'])
                ->first();

            if (!$activeLoad) {
                return response()->json(['status' => false, 'message' => 'No active daily load found'], 404);
            }

            $routeStats = [
                'total' => 0,
                'completed' => 0,
                'pending' => 0,
                'skipped' => 0
            ];

            $customers = [];

            if ($activeLoad->load_status >= 3) {
                // If trip started, fetch from snapshot
                $dailyLoadCustomers = \App\Models\AdDailyLoadHasCustomer::where('daily_load_id', $activeLoad->id)
                    ->orderBy('stop_sequence')
                    ->with('business.customer')
                    ->get();

                $routeStats['total'] = $dailyLoadCustomers->count();
                $routeStats['completed'] = $dailyLoadCustomers->where('status', 1)->count();
                $routeStats['pending'] = $dailyLoadCustomers->where('status', 0)->count();
                $routeStats['skipped'] = $dailyLoadCustomers->where('status', 2)->count();

                foreach ($dailyLoadCustomers as $dlc) {
                    $outstanding = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $dlc->ad_customer_has_business_id)
                        ->selectRaw('SUM(net_price - total_amount_paid) as balance')
                        ->first()->balance ?? 0;

                    $customers[] = [
                        'id' => $dlc->ad_customer_has_business_id,
                        'name' => $dlc->business->business_name ?: ($dlc->business->customer->name ?? 'N/A'),
                        'photo' => $dlc->business->customer_image ? asset($dlc->business->customer_image) : 'https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?w=200&h=200&fit=crop',
                        'address' => $dlc->business->address ?: ($dlc->business->customer->address ?? 'Unknown Address'),
                        'phone' => $dlc->business->contact_person_phone ?: ($dlc->business->customer->phone ?? '+94 00 000 0000'),
                        'outstanding' => (float) $outstanding,
                        'lastOrder' => 0, // Mocked for now
                        'status' => $dlc->status == 0 ? 'pending' : ($dlc->status == 1 ? 'completed' : 'skipped'),
                        'sequence' => $dlc->stop_sequence,
                        'distance' => ($dlc->distance_km ?? 0) . ' km',
                        'estimatedTime' => '10 min', // Mocked estimation
                    ];
                }
            } else if ($activeLoad->route) {
                // Trip not started, preview from route template
                $routeCustomers = \Illuminate\Support\Facades\DB::table('ad_route_has_customers')
                    ->join('ad_customer_has_business', 'ad_route_has_customers.ad_customer_has_business_id', '=', 'ad_customer_has_business.id')
                    ->join('cm_customer', 'ad_customer_has_business.customer_id', '=', 'cm_customer.id')
                    ->where('route_id', $activeLoad->route_id)
                    ->orderBy('ad_route_has_customers.stop_sequence')
                    ->select(
                        'ad_route_has_customers.*',
                        'cm_customer.name',
                        'cm_customer.address as original_address',
                        'cm_customer.phone as original_phone',
                        'cm_customer.id as parent_customer_id',
                        'ad_customer_has_business.address as business_address',
                        'ad_customer_has_business.contact_person_phone',
                        'ad_customer_has_business.business_name',
                        'ad_customer_has_business.customer_image'
                    )
                    ->get();

                $routeStats['total'] = $routeCustomers->count();
                $routeStats['pending'] = $routeCustomers->count();

                foreach ($routeCustomers as $rc) {
                    $outstanding = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $rc->ad_customer_has_business_id)
                        ->selectRaw('SUM(net_price - total_amount_paid) as balance')
                        ->first()->balance ?? 0;

                    $customers[] = [
                        'id' => $rc->ad_customer_has_business_id,
                        'name' => $rc->business_name ?: ($rc->name ?? 'N/A'),
                        'photo' => $rc->customer_image ? asset($rc->customer_image) : 'https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?w=200&h=200&fit=crop',
                        'address' => $rc->business_address ?: ($rc->original_address ?? 'Unknown Address'),
                        'phone' => $rc->contact_person_phone ?: ($rc->original_phone ?? '+94 00 000 0000'),
                        'outstanding' => (float) $outstanding,
                        'lastOrder' => 0,
                        'status' => 'pending',
                        'sequence' => $rc->stop_sequence ?? 0,
                        'distance' => ($rc->distance_km ?? 0) . ' km',
                        'estimatedTime' => '10 min',
                    ];
                }
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'dailyLoadId' => $activeLoad->id,
                    'loadStatus' => $activeLoad->load_status,
                    'routeStats' => $routeStats,
                    'customers' => $customers
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('fetch supervisor route error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch route data'], 500);
        }
    }

    public function getCustomerDetail($id)
    {
        try {
            $business = AdCustomerHasBusiness::with(['customer'])
                ->where('id', $id)
                ->first();

            if (!$business) {
                return response()->json([
                    'status' => false,
                    'message' => 'Customer business details not found'
                ], 404);
            }

            // Fetch outstanding balance
            $outstanding = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $business->id)
                ->selectRaw('SUM(net_price - total_amount_paid) as balance')
                ->first()->balance ?? 0;

            // Fetch recent invoices with items count and returns
            $recentInvoices = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $business->id)
                ->withCount('items')
                ->with(['newReturnItems.product', 'newReturnItems.invoice'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Stats
            $totalInvoices = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $business->id)->count();
            $avgInvoiceValue = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $business->id)->avg('net_price') ?? 0;

            // Check if assigned to active daily load for supervisor
            $supervisorId = $this->getSupervisorId();
            $isAssignedToLoad = false;
            if ($supervisorId) {
                // Find active daily load for this supervisor
                $activeLoad = AdDailyLoad::where('supervisor_id', $supervisorId)
                    ->where('status', 1) // Active
                    ->where('load_status', 3) // start
                    ->first();
                if ($activeLoad) {
                    $isAssignedToLoad = AdDailyLoadHasCustomer::where('daily_load_id', $activeLoad->id)
                        ->where('ad_customer_has_business_id', $business->id)
                        ->exists();
                }
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $business->id,
                    'name' => $business->business_name ?: ($business->customer->name ?? 'N/A'),
                    'type' => $business->b2b_customer_type == 1 ? 'Retailer' : 'Wholesaler', // Example mapping
                    'image' => $business->customer_image ? asset($business->customer_image) : 'https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?w=400&h=400&fit=crop',
                    'address' => $business->address ?: ($business->customer->address ?? 'N/A'),
                    'contact_person_name' => $business->contact_person_name ?: ($business->customer->contact_person_name ?? 'N/A'),
                    'phone' => $business->contact_person_phone ?: ($business->customer->phone ?? 'N/A'),
                    'rating' => 4.8, // Mocked rating
                    'latitude' => $business->latitude,
                    'longitude' => $business->longitude,
                    'since' => $business->created_at->format('Y'),
                    'outstanding' => (float) $outstanding,
                    'creditLimit' => (float) $business->credit_limit,
                    'is_assigned_to_active_load' => $isAssignedToLoad,
                    'stats' => [
                        'totalOrders' => $totalInvoices,
                        'avgOrderValue' => (float) $avgInvoiceValue,
                        'lastOrder' => $recentInvoices->first() ? $recentInvoices->first()->created_at->diffForHumans() : 'No orders yet',
                        'returnRate' => '0%', // Mocked
                    ],
                    'recentOrders' => $recentInvoices->map(function ($invoice) {
                        return [
                            'id' => $invoice->id,
                            'invoice_number' => $invoice->invoice_number,
                            'date' => $invoice->created_at->format('M d, Y'),
                            'items' => $invoice->items_count ?? 0,
                            'total' => (float) $invoice->invoice_price,
                            'status' => $invoice->status ?? 'completed',
                            'created_at' => $invoice->created_at,
                            'payment_status' => $invoice->payment_status,
                            'invoice_price' => (float) $invoice->invoice_price,
                            'return_price' => (float) $invoice->return_price,
                            'net_price' => (float) $invoice->net_price,
                            'total_amount_paid' => (float) $invoice->total_amount_paid,
                            'return_items' => $invoice->newReturnItems->map(function ($rItem) {
                                return [
                                    'id' => $rItem->id,
                                    'product_name' => $rItem->product->product_name ?? 'N/A',
                                    'quantity' => (float) $rItem->return_quantity,
                                    'old_invoice_number' => $rItem->invoice->invoice_number ?? 'N/A',
                                ];
                            }),
                        ];
                    }),
                    'recentPayments' => AdCubusinessInvoicePayments::where('ad_customer_has_business_id', $business->id)
                        ->with('items.invoice')
                        ->latest()
                        ->take(10)
                        ->get()
                        ->map(function ($payment) {
                            return [
                                'id' => $payment->id,
                                'receipt_number' => $payment->receipt_number,
                                'date' => $payment->payment_date,
                                'amount' => (float) $payment->amount,
                                'type' => $payment->payment_type == 1 ? 'Cash' : ($payment->payment_type == 2 ? 'Cheque' : 'Bank'),
                                'details' => $payment->items->map(function ($item) {
                                    return [
                                        'invoice_number' => $item->invoice->invoice_number ?? 'N/A',
                                        'applied_amount' => (float) $item->amount,
                                    ];
                                }),
                            ];
                        }),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Fetch Customer Detail Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch customer details'
            ], 500);
        }
    }

    public function getCustomerInvoices($customerId)
    {
        try {
            $invoices = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $customerId)
                ->where('status', 1)
                ->whereIn('inoice_type', [1, 2]) // Sale invoices
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $invoices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch customer invoices'
            ], 500);
        }
    }

    public function getCustomerReturns($customerId)
    {
        try {
            $today = date('Y-m-d');
            $returns = AdCubusinessHasInvoice::with(['newReturnItems.product'])
                ->where('ad_customer_has_business_id', $customerId)
                ->where('status', 1)
                ->where('inoice_type', 3) // Return-only
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $returns
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch customer returns'
            ], 500);
        }
    }

    public function getInvoiceItems($invoiceId)
    {
        try {
            $items = AdCubusinessHasProductItem::with('product_item')
                ->where('ad_cubusiness_has_invoice_id', $invoiceId)
                ->get();

            $returnItems = AdCubusinessHasReturnProductItem::with(['product', 'invoice'])
                ->where('ad_new_invoice_id', $invoiceId)
                ->get();

            return response()->json([
                'status' => true,
                'items' => $items,
                'return_items' => $returnItems->map(function ($rItem) {
                    return [
                        'id' => $rItem->id,
                        'product' => $rItem->product,
                        'return_quantity' => (float) $rItem->return_quantity,
                        'unit_price' => (float) $rItem->unit_price,
                        'total_price' => (float) $rItem->total_price,
                        'reason' => $rItem->reason,
                        'old_invoice_number' => $rItem->invoice->invoice_number ?? 'N/A',
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch invoice items'
            ], 500);
        }
    }

    public function createB2BInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ad_customer_has_business_id' => 'required|exists:ad_customer_has_business,id',
            'items' => 'required|array|min:1',
            'items.*.product_item_id' => 'required|exists:pm_product_item,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric',
            'payment_method' => 'required|string',
            'payments' => 'nullable|array',
            'payments.*.type' => 'required|string|in:cash,cheque,bank_transfer',
            'payments.*.amount' => 'required|numeric|min:0',
            'payments.*.cheque_number' => 'nullable|string',
            'payments.*.cheque_date' => 'nullable|date',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'payment_amount' => 'nullable|numeric|min:0',
            'returns' => 'nullable|array',
            'returns.*.product_item_id' => 'required|exists:pm_product_item,id',
            'returns.*.quantity' => 'required|numeric|min:0.001',
            'returns.*.unit_price' => 'required|numeric',
            'returns.*.stm_branch_stock_id' => 'required',
            'returns.*.reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $supervisorId = $this->getSupervisorId();
        if (!$supervisorId) {
            return response()->json(['status' => false, 'message' => 'Supervisor not found'], 403);
        }

        // Find active daily load for this supervisor
        $dailyLoad = AdDailyLoad::where('supervisor_id', $supervisorId)
            ->where('status', 1) // Active
            ->latest()
            ->first();

        if (!$dailyLoad) {
            return response()->json(['status' => false, 'message' => 'No active daily load found'], 404);
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $discountPercent = $request->discount_percent ?? 0;
            $discountAmount = $subtotal * ($discountPercent / 100);

            $returnPrice = 0;
            if ($request->has('returns') && is_array($request->returns)) {
                foreach ($request->returns as $returnItem) {
                    $returnPrice += $returnItem['quantity'] * $returnItem['unit_price'];
                }
            }

            $netPrice = $subtotal - $discountAmount - $returnPrice;

            $invoiceNumber = 'B2B-' . date('Ymd') . '-' . str_pad(AdCubusinessHasInvoice::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create Invoice with 0 paid first
            $invoice = AdCubusinessHasInvoice::create([
                'ad_customer_has_business_id' => $request->ad_customer_has_business_id,
                'ad_daily_load_id' => $dailyLoad->id,
                'invoice_number' => $invoiceNumber,
                'inoice_type' => $request->payment_method == 'cash' ? 1 : 2, // 1: Cash, 2: Credit
                'invoice_price' => $subtotal,
                'net_price' => $netPrice,
                'return_price' => $returnPrice,
                'total_amount_paid' => 0,
                'payment_status' => AdCubusinessHasInvoice::PAYMENT_STATUS_PENDING,
                'status' => 1,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Process Items
            foreach ($request->items as $itemData) {
                // Find stock item in daily load
                $dailyLoadItem = AdDailyLoadItem::where('daily_load_id', $dailyLoad->id)
                    ->where('product_item_id', $itemData['product_item_id'])
                    ->first();

                if (!$dailyLoadItem || $dailyLoadItem->available_quantity < $itemData['quantity']) {
                    throw new \Exception("Insufficient stock for product ID: " . $itemData['product_item_id']);
                }

                // Create Invoice Item
                AdCubusinessHasProductItem::create([
                    'ad_cubusiness_has_invoice_id' => $invoice->id,
                    'pm_product_item_id' => $itemData['product_item_id'],
                    'stm_branch_stock_id' => $dailyLoadItem->stm_branch_stock_id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['quantity'] * $itemData['unit_price'],
                    'status' => 1,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
                // Deduct stock
                $dailyLoadItem->available_quantity -= $itemData['quantity'];
                $dailyLoadItem->save();

                // Update barcodes as sold
                $barcodes = StmBarcode::where('ad_daily_load_id', $dailyLoad->id)
                    ->where('pm_product_item_id', $itemData['product_item_id'])
                    ->where('is_sold', 0)
                    ->where('is_return', 0)
                    ->limit((int) $itemData['quantity'])
                    ->get();

                foreach ($barcodes as $barcode) {
                    $barcode->is_sold = 1;
                    $barcode->cubusiness_has_invoice_id = $invoice->id;
                    $barcode->save();

                    StmBarcodesHistory::create([
                        'barcode_id' => $barcode->id,
                        'created_by' => auth()->id(),
                        'action' => 'Sold',
                        'description' => 'Sold via B2B Invoice: ' . $invoiceNumber,
                    ]);
                }
            }

            // Update Visitation Status
            AdDailyLoadHasCustomer::where('daily_load_id', $dailyLoad->id)
                ->where('ad_customer_has_business_id', $request->ad_customer_has_business_id)
                ->update(['status' => 1]); // Completed

            // Process Returns
            if ($request->has('returns') && is_array($request->returns)) {
                foreach ($request->returns as $returnItem) {
                    AdCubusinessHasReturnProductItem::create([
                        'ad_cubusiness_has_invoice_id' => $returnItem['previous_invoice_id'],
                        'ad_new_invoice_id' => $invoice->id,
                        'pm_product_item_id' => $returnItem['product_item_id'],
                        'stm_branch_stock_id' => $returnItem['stm_branch_stock_id'],
                        'return_quantity' => $returnItem['quantity'],
                        'unit_price' => $returnItem['unit_price'],
                        'total_price' => $returnItem['quantity'] * $returnItem['unit_price'],
                        'reason' => $returnItem['reason'] ?? null,
                        'status' => 1,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    // Update barcodes: mark as returned
                    $returnedBarcodes = StmBarcode::where('cubusiness_has_invoice_id', $returnItem['previous_invoice_id'])
                        ->where('pm_product_item_id', $returnItem['product_item_id'])
                        ->where('is_return', 0)
                        ->limit((int) $returnItem['quantity'])
                        ->get();

                    foreach ($returnedBarcodes as $barcode) {
                        $barcode->is_return = 1;
                        $barcode->save();

                        StmBarcodesHistory::create([
                            'barcode_id' => $barcode->id,
                            'created_by' => auth()->id(),
                            'action' => 'Returned',
                            'description' => 'Returned from Invoice: ' . $returnItem['previous_invoice_id'] . '. Reason: ' . ($returnItem['reason'] ?? 'N/A'),
                        ]);
                    }

                    // Record in separate Return Stock table
                    $branchStock = StmBranchStock::find($returnItem['stm_branch_stock_id']);

                    AdReturnProductStock::create([
                        'stm_stock_id' => $branchStock ? $branchStock->stm_stock_id : null,
                        'stm_branch_stock_id' => $returnItem['stm_branch_stock_id'],
                        'pm_product_item_id' => $returnItem['product_item_id'],
                        'ad_daily_load_id' => $dailyLoad->id,
                        'ad_customer_has_business_id' => $request->ad_customer_has_business_id,
                        'quantity' => $returnItem['quantity'],
                        'unit_price' => $returnItem['unit_price'],
                        'reason' => $returnItem['reason'] ?? null,
                        'status' => 1,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    /* 
                    // REMOVED: No longer adding returns to normal sellable stock
                    if (isset($returnItem['stm_branch_stock_id'])) {
                        StmBranchStock::where('id', $returnItem['stm_branch_stock_id'])
                            ->increment('quantity', $returnItem['quantity']);

                        // Update daily load item to reflect in supervisor stock (van stock)
                        $dlItem = AdDailyLoadItem::where('daily_load_id', $dailyLoad->id)
                            ->where('product_item_id', $returnItem['product_item_id'])
                            ->first();
                        if ($dlItem) {
                            $dlItem->increment('available_quantity', $returnItem['quantity']);
                        }
                    }
                    */

                }
            }

            // Process Payments
            $totalAmountPaid = 0;
            if ($request->has('payments') && is_array($request->payments)) {
                foreach ($request->payments as $paymentData) {
                    $paymentType = 1; // Default Cash
                    if ($paymentData['type'] == 'cheque') $paymentType = 2;
                    if ($paymentData['type'] == 'bank_transfer') $paymentType = 3;

                    $receiptNumber = 'REC-' . date('Ymd') . '-' . str_pad(AdCubusinessInvoicePayments::count() + 1, 4, '0', STR_PAD_LEFT);

                    // Create Master Payment Record
                    $masterPayment = AdCubusinessInvoicePayments::create([
                        'receipt_number' => $receiptNumber,
                        'payment_type' => $paymentType,
                        'ad_customer_has_business_id' => $request->ad_customer_has_business_id,
                        'ad_cubusiness_has_invoice_id' => $invoice->id, // Single association for compatibility
                        'payment_date' => date('Y-m-d'),
                        'cheque_date' => $paymentData['cheque_date'] ?? null,
                        'cheque_number' => $paymentData['cheque_number'] ?? null,
                        'amount' => $paymentData['amount'],
                        'status' => 1,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    // Create Pivot Record
                    AdCubusinessInvoicePaymentsHasInvoice::create([
                        'ad_cubusiness_invoice_payments_id' => $masterPayment->id,
                        'ad_cubusiness_has_invoice_id' => $invoice->id,
                        'amount' => $paymentData['amount'],
                        'status' => 1,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    $totalAmountPaid += $paymentData['amount'];
                }
            }

            // Final Invoice Total Paid and Payment Status Update
            $invoice->total_amount_paid = $totalAmountPaid;

            // Recalculate Payment Status correctly
            if ($invoice->total_amount_paid >= $invoice->net_price) {
                $invoice->payment_status = AdCubusinessHasInvoice::PAYMENT_STATUS_COMPLETE;
            } elseif ($invoice->total_amount_paid > 0) {
                $invoice->payment_status = AdCubusinessHasInvoice::PAYMENT_STATUS_PARTIAL;
            } else {
                $invoice->payment_status = AdCubusinessHasInvoice::PAYMENT_STATUS_PENDING;
            }

            // Handle edge case: if net price is 0 or negative due to returns
            if ($invoice->net_price <= 0) {
                $invoice->payment_status = AdCubusinessHasInvoice::PAYMENT_STATUS_COMPLETE;
            }

            $invoice->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Invoice created successfully',
                'data' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create B2B Invoice Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process a standalone return (without creating a new sale order).
     * Does NOT create a new invoice as per user request.
     * Updates original invoices and stock.
     */
    public function processStandaloneReturn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ad_customer_has_business_id' => 'required|exists:ad_customer_has_business,id',
            'returns' => 'required|array|min:1',
            'returns.*.product_item_id' => 'required|exists:pm_product_item,id',
            'returns.*.quantity' => 'required|numeric|min:0.001',
            'returns.*.unit_price' => 'required|numeric',
            'returns.*.stm_branch_stock_id' => 'required',
            'returns.*.previous_invoice_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $supervisorId = $this->getSupervisorId();
            if (!$supervisorId) {
                return response()->json(['status' => false, 'message' => 'Supervisor not found'], 403);
            }

            // Find active daily load for this supervisor to update van stock
            $dailyLoad = AdDailyLoad::where('supervisor_id', $supervisorId)
                ->whereIn('status', [1, 2, 3])
                ->latest()
                ->first();

            if (!$dailyLoad) {
                return response()->json(['status' => false, 'message' => 'No active daily load found'], 404);
            }

            // Process Return Items
            $totalRefundAmount = 0;
            $updatedInvoiceNumbers = [];

            foreach ($request->returns as $returnItem) {
                $itemTotal = $returnItem['quantity'] * $returnItem['unit_price'];
                $totalRefundAmount += $itemTotal;

                AdCubusinessHasReturnProductItem::create([
                    'ad_cubusiness_has_invoice_id' => $returnItem['previous_invoice_id'],
                    'ad_new_invoice_id' => null, // No new invoice created
                    'pm_product_item_id' => $returnItem['product_item_id'],
                    'stm_branch_stock_id' => $returnItem['stm_branch_stock_id'],
                    'return_quantity' => $returnItem['quantity'],
                    'unit_price' => $returnItem['unit_price'],
                    'total_price' => $itemTotal,
                    'status' => 1,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                // Record in separate Return Stock table
                $branchStock = StmBranchStock::find($returnItem['stm_branch_stock_id']);

                AdReturnProductStock::create([
                    'stm_stock_id' => $branchStock ? $branchStock->stm_stock_id : null,
                    'stm_branch_stock_id' => $returnItem['stm_branch_stock_id'],
                    'pm_product_item_id' => $returnItem['product_item_id'],
                    'ad_daily_load_id' => $dailyLoad->id,
                    'ad_customer_has_business_id' => $request->ad_customer_has_business_id,
                    'quantity' => $returnItem['quantity'],
                    'unit_price' => $returnItem['unit_price'],
                    'reason' => $returnItem['reason'] ?? null,
                    'status' => 1,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                /*
                // REMOVED: No longer adding returns to normal sellable stock
                if (isset($returnItem['stm_branch_stock_id'])) {
                    StmBranchStock::where('id', $returnItem['stm_branch_stock_id'])
                        ->increment('quantity', $returnItem['quantity']);

                    // Update daily load item (van stock)
                    $dlItem = AdDailyLoadItem::where('daily_load_id', $dailyLoad->id)
                        ->where('product_item_id', $returnItem['product_item_id'])
                        ->first();
                    if ($dlItem) {
                        $dlItem->increment('available_quantity', $returnItem['quantity']);
                    }
                }
                */

                // Update the original invoice
                $originalInvoice = AdCubusinessHasInvoice::find($returnItem['previous_invoice_id']);
                if ($originalInvoice) {
                    $originalInvoice->return_price += $itemTotal;
                    $originalInvoice->net_price -= $itemTotal;

                    // If net price becomes negative or zero relative to paid amount, update status
                    if ($originalInvoice->total_amount_paid >= $originalInvoice->net_price) {
                        $originalInvoice->payment_status = AdCubusinessHasInvoice::PAYMENT_STATUS_COMPLETE;
                    }
                    $originalInvoice->save();
                    $updatedInvoiceNumbers[] = $originalInvoice->invoice_number;
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Return processed successfully and applied to original invoices',
                'data' => [
                    'invoice_id' => null,
                    'invoice_number' => 'N/A (Applied to: ' . implode(', ', array_unique($updatedInvoiceNumbers)) . ')',
                    'refund_amount' => $totalRefundAmount
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Process Standalone Return Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to process return: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Collect payment from a customer and apply it to unpaid invoices.
     */
    public function collectPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ad_customer_has_business_id' => 'required|exists:ad_customer_has_business,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|string|in:cash,cheque,bank_transfer',
            'payment_date' => 'required|date',
            'cheque_number' => 'nullable|string',
            'cheque_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $totalAmount = floatval($request->amount);
            $remainingAmount = $totalAmount;

            $paymentType = $paymentTypeMap[$request->payment_type] ?? 1;

            // NEW STRUCTURE: Create 1 Master Payment for the entire collection in the existing table
            $masterReceiptNumber = 'COLL-' . date('Ymd') . '-' . str_pad(AdCubusinessInvoicePayments::count() + 1, 4, '0', STR_PAD_LEFT);
            $masterPayment = AdCubusinessInvoicePayments::create([
                'receipt_number' => $masterReceiptNumber,
                'payment_type' => $paymentType,
                'ad_customer_has_business_id' => $request->ad_customer_has_business_id,
                'ad_cubusiness_has_invoice_id' => null, // Master collection not tied to single invoice at top level
                'payment_date' => $request->payment_date,
                'cheque_date' => $request->cheque_date ?? null,
                'cheque_number' => $request->cheque_number ?? null,
                'amount' => $totalAmount,
                'status' => 1,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Fetch unpaid or partially paid invoices for this customer
            $invoices = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $request->ad_customer_has_business_id)
                ->where('status', 1)
                ->whereRaw('net_price > total_amount_paid')
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($invoices as $invoice) {
                if ($remainingAmount <= 0)
                    break;

                $dueAmount = $invoice->net_price - $invoice->total_amount_paid;
                $paymentForThisInvoice = min($remainingAmount, $dueAmount);

                if ($paymentForThisInvoice > 0) {
                    // Create Pivot Record linking to the master collection
                    AdCubusinessInvoicePaymentsHasInvoice::create([
                        'ad_cubusiness_invoice_payments_id' => $masterPayment->id,
                        'ad_cubusiness_has_invoice_id' => $invoice->id,
                        'amount' => $paymentForThisInvoice,
                        'status' => 1,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    $invoice->total_amount_paid += $paymentForThisInvoice;

                    // Update payment status
                    if ($invoice->total_amount_paid >= $invoice->net_price) {
                        $invoice->payment_status = AdCubusinessHasInvoice::PAYMENT_STATUS_COMPLETE;
                    } else {
                        $invoice->payment_status = AdCubusinessHasInvoice::PAYMENT_STATUS_PARTIAL;
                    }

                    $invoice->save();

                    $remainingAmount -= $paymentForThisInvoice;
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Payment collected and applied successfully',
                'data' => [
                    'total_collected' => $totalAmount,
                    'applied_amount' => $totalAmount - $remainingAmount,
                    'excess_amount' => $remainingAmount,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Collect Payment Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to collect payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active daily load details for route completion.
     */
    public function getActiveDailyLoadDetails()
    {
        try {
            $supervisorId = $this->getSupervisorId();
            if (!$supervisorId) {
                return response()->json(['status' => false, 'message' => 'Supervisor not found'], 403);
            }

            $activeLoad = AdDailyLoad::where('supervisor_id', $supervisorId)
                ->where('status', 1) // Active
                ->whereIn('load_status', [2, 3])
                ->with(['route.customers.customer', 'vehicle', 'driver', 'items.product', 'returns.product'])
                ->first();

            if (!$activeLoad) {
                return response()->json(['status' => false, 'message' => 'No active daily load found'], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $activeLoad
            ]);
        } catch (\Exception $e) {
            Log::error('Get Active Daily Load Details Failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch active load details'], 500);
        }
    }

    /**
     * Complete the route and record unloading details.
     */
    public function completeRoute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'daily_load_id' => 'required|exists:ad_daily_loads,id',
            'ending_mileage' => 'required|numeric',
            'unload_time' => 'required|date_format:Y-m-d H:i:s',
            'items' => 'required|array',
            'items.*.product_item_id' => 'required|exists:pm_product_item,id',
            'items.*.unload_qty' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $load = AdDailyLoad::find($request->daily_load_id);
            $load->update([
                'ending_mileage' => $request->ending_mileage,
                'unload_time' => $request->unload_time,
                'load_status' => 4, // 4: Completed/Unloaded
                'status' => 0,      // Mark daily load as inactive
            ]);

            foreach ($request->items as $itemData) {
                AdDailyLoadItem::where('daily_load_id', $load->id)
                    ->where('product_item_id', $itemData['product_item_id'])
                    ->update(['unload_qty' => $itemData['unload_qty']]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Route completed successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Complete Route Failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to complete route: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Finalize the daily load: update status and release resources.
     */
    public function finishDailyLoad(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $agentId = $this->getAgentId();
                $load = AdDailyLoad::where('id', $id)->where('agent_id', $agentId)->first();

                if (!$load) {
                    return response()->json(['status' => false, 'message' => 'Daily load not found'], 404);
                }

                // 1. Update Agent Stock based on unload_qty
                $items = AdDailyLoadItem::where('daily_load_id', $load->id)->get();
                foreach ($items as $item) {
                    if ($item->unload_qty > 0) {
                        if ($item->stm_branch_stock_id) {
                            StmBranchStock::where('id', $item->stm_branch_stock_id)
                                ->increment('quantity', $item->unload_qty);
                        }

                        // 2. Release barcodes for the unloaded quantities
                        $barcodesToRelease = StmBarcode::where('ad_daily_load_id', $load->id)
                            ->where('pm_product_item_id', $item->product_item_id)
                            ->where('is_sold', false)
                            ->limit((int) $item->unload_qty)
                            ->get();

                        foreach ($barcodesToRelease as $barcode) {
                            $barcode->update(['ad_daily_load_id' => null]);

                            StmBarcodesHistory::create([
                                'barcode_id' => $barcode->id,
                                'created_by' => auth()->id(),
                                'action' => 'DAILY_LOAD_RELEASED',
                                'description' => "Released from Daily Load #{$load->id} due to unloading",
                            ]);
                        }
                    }
                }

                // Update load status as requested: status 1, load_status 5
                $load->status = 1;
                $load->load_status = 5;
                $load->save();

                Log::info("Finishing Daily Load #{$id}. Route ID: {$load->route_id}, Supervisor ID: {$load->supervisor_id}, Driver ID: {$load->driver_id}, Vehicle ID: {$load->vehicle_id}");

                // Release resources (set is_added = false)
                if ($load->route_id) {
                    $updated = DB::table('ad_routes')->where('id', $load->route_id)->update(['is_added' => false]);
                    Log::info("AdRoute #{$load->route_id} is_added set to false. Rows updated: {$updated}");
                }
                if ($load->supervisor_id) {
                    $updated = DB::table('sm_superviser')->where('id', $load->supervisor_id)->update(['is_added' => false]);
                    Log::info("SmSuperviser #{$load->supervisor_id} is_added set to false. Rows updated: {$updated}");
                }
                if ($load->driver_id) {
                    $updated = DB::table('dm_driver')->where('id', $load->driver_id)->update(['is_added' => false]);
                    Log::info("DmDriver #{$load->driver_id} is_added set to false. Rows updated: {$updated}");
                }
                if ($load->vehicle_id) {
                    $updated = DB::table('vm_vehicle')->where('id', $load->vehicle_id)->update(['is_added' => false]);
                    Log::info("VmVehicle #{$load->vehicle_id} is_added set to false. Rows updated: {$updated}");
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Daily load finished and resources released successfully',
                    'data' => $load
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Finish Daily Load Failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to finish daily load: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Get returns list for the current agent or supervisor.
     */
    public function getReturns()
    {
        try {
            $agentId = $this->getAgentId();
            $supervisorId = $this->getSupervisorId();

            $query = \App\Models\AdReturnProductStock::with(['product', 'dailyLoad.route', 'business.customer'])
                ->orderBy('created_at', 'desc');

            if ($agentId) {
                // Agent sees all returns for their routes
                $routeIds = AdRoute::where('agent_id', $agentId)->pluck('id');
                $query->whereHas('dailyLoad', function($q) use ($routeIds) {
                    $q->whereIn('route_id', $routeIds);
                });
            } elseif ($supervisorId) {
                // Supervisor sees all returns for their routes
                $routeIds = AdRoute::where('sm_superviser_id', $supervisorId)->pluck('id');
                $query->whereHas('dailyLoad', function($q) use ($routeIds) {
                    $q->whereIn('route_id', $routeIds);
                });
            } else {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
            }

            $returns = $query->get();

            return response()->json([
                'status' => true,
                'data' => $returns
            ]);
        } catch (\Exception $e) {
            Log::error('Get Returns Failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch returns'], 500);
        }
    }

    /**
     * Get returns for a specific daily load.
     */
    public function getDailyLoadReturns($id)
    {
        try {
            $returns = \App\Models\AdReturnProductStock::where('ad_daily_load_id', $id)
                ->with(['product', 'business.customer'])
                ->get();

            return response()->json([
                'status' => true,
                'data' => $returns
            ]);
        } catch (\Exception $e) {
            Log::error('Get Daily Load Returns Failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch load returns'], 500);
        }
    }
    /**
     * Get daily sales summary for an agent.
     */
    public function getDailySalesSummary(Request $request)
    {
        try {
            $date = $request->query('date', date('Y-m-d'));
            $agentId = $this->getAgentId();
            $loadId = $request->query('load_id');
            
            if (!$agentId) {
                return response()->json(['status' => false, 'message' => 'Agent not found'], 403);
            }

            // Get all routes for this agent
            $routeIds = AdRoute::where('agent_id', $agentId)->pluck('id');
            $userId = auth()->id();
            
            // 1. Sales Summary - Include both route-based and creator-based invoices
            $invoicesQuery = AdCubusinessHasInvoice::whereDate('created_at', $date);
            
            if ($loadId) {
                $invoicesQuery->where('ad_daily_load_id', $loadId);
            } else {
                $invoicesQuery->where(function($query) use ($routeIds, $userId) {
                    $query->whereHas('business', function($q) use ($routeIds) {
                        $q->whereIn('route_id', $routeIds);
                    })
                    ->orWhere('created_by', $userId);
                });
            }

            $invoices = $invoicesQuery->with(['business', 'items.product'])->get();

            Log::info("Daily Summary for Agent $agentId on $date: Found " . $invoices->count() . " invoices. Routes: " . implode(',', $routeIds->toArray()));

            // 1. Cost - Total value of Order Requests for this agent on this date
            // Note: Cost might not reflect individual load perfectly if load_id is provided
            $totalCost = \App\Models\StmOrderRequest::where('agent_id', $agentId)
                ->whereDate('created_at', $date)
                ->sum('grand_total');

            // 2. Sales - Total value of Invoices created today
            $totalSales = $invoices->sum('net_price');
            $itemCount = $invoices->sum(function ($invoice) {
                return $invoice->items->sum('quantity');
            });

            // We do not need gross profit / net profit in the new logic, but keeping them zero for response structure compatibility
            $grossProfit = 0;
            $netProfit = 0;
            $returnProfitLoss = 0;

            // 3. Returns - Customer returned total using AdCubusinessHasReturnProductItem
            // Get all invoice IDs created today to find their returns
            $invoiceIds = $invoices->pluck('id');
            
            $returns = \App\Models\AdCubusinessHasReturnProductItem::whereIn('ad_new_invoice_id', $invoiceIds)
                ->get();

            $totalReturnsValue = $returns->sum('total_price');

            // 4. Payment Breakdown & Credit
            $paymentsQuery = AdCubusinessInvoicePayments::whereDate('created_at', $date);
            if ($loadId) {
                $paymentsQuery->whereIn('ad_cubusiness_has_invoice_id', $invoiceIds);
            } else {
                $paymentsQuery->whereHas('business', function ($q) use ($routeIds) {
                    $q->whereIn('route_id', $routeIds);
                });
            }
            $payments = $paymentsQuery->get();

            $totalCredit = $invoices->sum(function ($inv) {
                return max(0, $inv->net_price - $inv->total_amount_paid);
            });

            $paymentBreakdown = [
                'cash' => $payments->where('payment_type', 1)->sum('amount'),
                'credit' => $totalCredit,
                'cheque' => $payments->where('payment_type', 2)->sum('amount'),
                'bank_transfer' => $payments->where('payment_type', 3)->sum('amount'),
                'total_collected' => $payments->sum('amount'),
            ];

            // 4. Daily Loads for this date
            $loadsQuery = AdDailyLoad::whereDate('load_date', $date);
            if ($loadId) {
                $loadsQuery->where('id', $loadId);
            } else {
                $loadsQuery->whereIn('route_id', $routeIds);
            }
            $loads = $loadsQuery->with(['route', 'vehicle', 'driver'])
                ->get()
                ->map(function($load) {
                    return [
                        'id' => $load->id,
                        'route_name' => $load->route->route_name,
                        'vehicle' => $load->vehicle->vehicle_number,
                        'status' => $load->load_status,
                    ];
                });

            $loadTransactions = [];
            foreach ($invoices as $invoice) {
                $salesItems = [];
                foreach ($invoice->items as $item) {
                    $salesItems[] = [
                        'product_name' => $item->product->product_name ?? 'N/A',
                        'quantity' => (float)$item->quantity,
                        'unit_price' => (float)$item->unit_price,
                        'total_price' => (float)$item->total_price,
                    ];
                }

                $returnItems = [];
                $invoiceReturns = $returns->where('ad_new_invoice_id', $invoice->id);
                foreach ($invoiceReturns as $ret) {
                    $returnItems[] = [
                        'product_name' => $ret->product->product_name ?? 'N/A',
                        'quantity' => (float)$ret->return_quantity,
                        'unit_price' => (float)$ret->unit_price,
                        'total_price' => (float)$ret->total_price,
                    ];
                }

                $loadTransactions[] = [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'business_name' => $invoice->business->business_name ?? 'Walk-in Customer',
                    'sales_amount' => (float)$invoice->net_price,
                    'return_amount' => (float)$invoiceReturns->sum('total_price'),
                    'sales_items' => $salesItems,
                    'return_items' => $returnItems,
                ];
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'date' => $date,
                    'summary' => [
                        'total_sales' => (float)$totalSales,
                        'total_cost' => (float)$totalCost,
                        'gross_profit' => (float)$grossProfit,
                        'item_count' => (float)$itemCount,
                    ],
                    'returns' => [
                        'total_value' => (float)$totalReturnsValue,
                        'count' => $returns->count(),
                        'profit_impact' => (float)$returnProfitLoss
                    ],
                    'profit' => [
                        'net_profit' => (float)$netProfit,
                        'margin_percentage' => $totalSales > 0 ? round(($netProfit / $totalSales) * 100, 2) : 0
                    ],
                    'payments' => $paymentBreakdown,
                    'loads' => $loads,
                    'load_transactions' => $loadTransactions
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get Daily Sales Summary Failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch summary: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get available returns and agent stock for bakery return process.
     */
    public function getAvailableForBakeryReturn(Request $request)
    {
        try {
            $agentId = $this->getAgentId();
            if (!$agentId) {
                return response()->json(['status' => false, 'message' => 'Agent not found'], 403);
            }

            // 1. Physical Stock (from stm_branch_stock)
            $physicalStock = StmBranchStock::where('agent_id', $agentId)
                ->where('quantity', '>', 0)
                ->with(['productItem'])
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'physical',
                        'id' => $item->id,
                        'product_id' => $item->pm_product_item_id,
                        'product_name' => $item->productItem->product_name ?? 'Unknown',
                        'quantity' => $item->quantity,
                        'stm_stock_id' => $item->stm_stock_id,
                        'distributor_price' => $item->productItem->distributor_price ?? 0,
                        'wholesale_price' => $item->productItem->wholesale_price ?? 0,
                        'retail_price' => $item->productItem->selling_price ?? 0,
                    ];
                });

            // 2. Return Stock (from ad_return_product_stocks) - filter those already returned to bakery
            $returnedToBakeryIds = AdCreditNoteHasProduct::whereNotNull('return_stock_id')->pluck('return_stock_id');
            
            $returnStockQuery = AdReturnProductStock::whereHas('dailyLoad.route', function($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })
                ->whereNotIn('id', $returnedToBakeryIds)
                ->whereRaw('quantity > credit_note_added_qty'); // Only show items with remaining quantity

            if ($request->has('ad_customer_has_business_id')) {
                $returnStockQuery->where('ad_customer_has_business_id', $request->ad_customer_has_business_id);
            }

            $returnStock = $returnStockQuery->with(['product'])
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'return_stock',
                        'id' => $item->id,
                        'product_id' => $item->pm_product_item_id,
                        'product_name' => $item->product->product_name ?? 'Unknown',
                        'quantity' => $item->quantity,
                        'stm_stock_id' => $item->stm_stock_id,
                        'branch_stock_id' => $item->stm_branch_stock_id,
                        'distributor_price' => $item->unit_price, 
                        'wholesale_price' => $item->product->wholesale_price ?? 0,
                        'retail_price' => $item->product->selling_price ?? 0,
                        'reason' => $item->reason,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => [
                    'physical' => $physicalStock,
                    'returns' => $returnStock
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get Available For Bakery Return Failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch available items'], 500);
        }
    }

    /**
     * Create a bakery return / credit note request.
     */
    public function createBakeryReturn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'note_type' => 'required|integer|in:1,2', 
            'ad_customer_has_business_id' => 'nullable|exists:ad_customer_has_business,id',
            'reason' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer', 
            'items.*.product_id' => 'required|exists:pm_product_item,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.distributor_price' => 'required|numeric',
            'items.*.wholesale_price' => 'required|numeric',
            'items.*.retail_price' => 'required|numeric',
            'items.*.stm_stock_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $agentId = $this->getAgentId();
            if (!$agentId) {
                return response()->json(['status' => false, 'message' => 'Agent not found'], 403);
            }

            $creditNoteNumber = 'CN-' . date('Ymd') . '-' . str_pad(AdCreditNote::count() + 1, 4, '0', STR_PAD_LEFT);

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['distributor_price'];
            }

            $creditNote = AdCreditNote::create([
                'agent_id' => $agentId,
                'credit_note_number' => $creditNoteNumber,
                'credit_note_date' => date('Y-m-d'),
                'note_type' => $request->note_type,
                'ad_customer_has_business_id' => $request->ad_customer_has_business_id,
                'total_amount' => $totalAmount,
                'status' => 0, 
                'reason' => $request->reason,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            foreach ($request->items as $itemData) {
                $branchStockId = $request->note_type == 1 ? $itemData['id'] : null;
                $returnStockId = $request->note_type == 2 ? $itemData['id'] : null;

                AdCreditNoteHasProduct::create([
                    'credit_note_id' => $creditNote->id,
                    'product_id' => $itemData['product_id'],
                    'return_stock_id' => $returnStockId,
                    'stm_stock_id' => $itemData['stm_stock_id'],
                    'branch_stock_id' => $branchStockId,
                    'qty' => $itemData['quantity'],
                    'distributor_price' => $itemData['distributor_price'],
                    'wholesale_price' => $itemData['wholesale_price'],
                    'retail_price' => $itemData['retail_price'],
                    'total' => $itemData['quantity'] * $itemData['distributor_price'],
                    'reason' => $itemData['reason'] ?? null,
                    'status' => 0,
                ]);

                if ($request->note_type == 1) {
                    $stock = StmBranchStock::find($itemData['id']);
                    if ($stock) {
                        if ($stock->quantity < $itemData['quantity']) {
                            throw new \Exception("Insufficient stock for product ID: " . $itemData['product_id']);
                        }
                        $stock->decrement('quantity', $itemData['quantity']);
                    }
                }

                if ($request->note_type == 2) {
                    $returnStock = AdReturnProductStock::find($itemData['id']);
                    if ($returnStock) {
                        $returnStock->increment('credit_note_added_qty', $itemData['quantity']);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Bakery return request created successfully',
                'data' => $creditNote
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create Bakery Return Failed: ' . $e.getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to create return: ' . $e.getMessage()], 500);
        }
    }

    /**
     * Get credit notes for the current agent.
     */
    public function getCreditNotes(Request $request)
    {
        try {
            $agentId = $this->getAgentId();
            if (!$agentId) {
                return response()->json(['status' => false, 'message' => 'Agent not found'], 403);
            }

            $status = $request->query('status');

            $query = AdCreditNote::where('agent_id', $agentId);

            if ($status !== null) {
                $query->where('status', $status);
            }

            $notes = $query->with(['products.product'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $notes
            ]);
        } catch (\Exception $e) {
            Log::error('Get Credit Notes Failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch credit notes'], 500);
        }
    }
}
