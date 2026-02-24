<?php

namespace App\Http\Controllers;

use App\Models\AdAgent;
use App\Models\DmDriver;
use App\Models\SmSuperviser;
use App\Models\VmVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiManagementController extends Controller
{
    private function getAgentId()
    {
        $user = auth()->user();
        if ($user->user_role_id == 8) {
            $agent = AdAgent::where('user_id', $user->id)->first();
            return $agent ? $agent->id : null;
        }
        return null;
    }

    // Driver Management
    public function getDrivers()
    {
        try {
            $agentId = $this->getAgentId();
            $drivers = DmDriver::where('agent_id', $agentId)->get();
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

    // Supervisor Management
    public function getSupervisors()
    {
        try {
            $agentId = $this->getAgentId();
            $supervisors = SmSuperviser::where('agent_id', $agentId)->get();
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
            $agentId = $this->getAgentId();
            $supervisor = SmSuperviser::create([
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

    // Vehicle Management
    public function getVehicles()
    {
        try {
            $agentId = $this->getAgentId();
            $vehicles = VmVehicle::where('agent_id', $agentId)->get();
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
        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required|string|max:255',
            'engine_number' => 'nullable|string|max:255',
            'chassis_number' => 'nullable|string|max:255',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $imagePath = null;
            if ($request->hasFile('vehicle_image')) {
                $file = $request->file('vehicle_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = public_path('uploads/vehicles');

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

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
}
