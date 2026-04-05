<?php

namespace App\Http\Controllers;

use App\Models\AdCustomerHasBusiness;
use App\Models\CmCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiCustomerController extends Controller
{
    public function createCustomer(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            // cm_customer fields
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:cm_customer,phone',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'customer_type' => 'required|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'created_by' => 'nullable|integer',
            // ad_customer_has_business fields
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email|max:255',
            'b2b_customer_type' => 'nullable|integer',
            'payment_terms' => 'nullable|integer',
            'visit_schedule' => 'nullable|integer',
            'preferred_time' => 'nullable',
            'preferred_visit_days' => 'nullable|string',
            'credit_limit' => 'nullable|numeric',
            'payment_terms_days' => 'nullable|integer',
            'allow_credit' => 'nullable',
            'agent_id' => 'nullable|integer',
            'route_id' => 'nullable|integer',
            'stop_sequence' => 'nullable|integer',
            'special_instructions' => 'nullable|string',
            'delivery_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'business_name' => 'nullable|string|max:255',
            'customer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'master_customer_id' => 'nullable|integer|exists:cm_customer,id',
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

            // 2. Handle Image Upload
            $imagePath = null;
            if ($request->hasFile('customer_image')) {
                $file = $request->file('customer_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/customers'), $filename);
                $imagePath = 'uploads/customers/' . $filename;
            }

            // 3. Create or Link Customer in cm_customer
            if ($request->master_customer_id) {
                $customer = CmCustomer::find($request->master_customer_id);
                // Optionally update master customer details if they changed? 
                // For now, let's just link it.
            } else {
                $customer = CmCustomer::create([
                    'name' => $request->name,
                    'customer_type' => $request->customer_type,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'created_by' => $request->created_by ?? auth()->id() ?? 1,
                    'updated_by' => auth()->id() ?? 1,
                ]);
            }

            // 4. Create Business Details in ad_customer_has_business
            $agentId =  $this->getAgentId();

            $business = AdCustomerHasBusiness::create([
                'customer_id' => $customer->id,
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'contact_person_email' => $request->contact_person_email,
                'b2b_customer_type' => $request->b2b_customer_type ?? 1,
                'payment_terms' => $request->payment_terms ?? 4,
                'visit_schedule' => $request->visit_schedule ?? 1,
                'preferred_time' => $request->preferred_time,
                'preferred_visit_days' => $request->preferred_visit_days,
                'credit_limit' => $request->credit_limit,
                'payment_terms_days' => $request->payment_terms_days,
                'allow_credit' => $request->allow_credit === 'true' || $request->allow_credit === '1',
                'agent_id' => $agentId,
                'route_id' => $request->route_id,
                'stop_sequence' => $request->stop_sequence,
                'special_instructions' => $request->special_instructions,
                'delivery_instructions' => $request->delivery_instructions,
                'notes' => $request->notes,
                'business_name' => $request->business_name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'sm_superviser_id' => $this->getSupervisorId(),
                'customer_image' => $imagePath, // Store the public path
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Customer created successfully',
                'data' => [
                    'customer' => $customer,
                    'business_details' => $business
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Creation Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function searchMasterCustomers(Request $request)
    {
        $query = $request->input('query', '');

        $customers = CmCustomer::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
        })
            ->limit(20)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $customers
        ]);
    }

    public function updateCustomer(Request $request, $id)
    {
        // 1. Find Business Record
        $business = AdCustomerHasBusiness::find($id);
        if (!$business) {
            return response()->json([
                'status' => false,
                'message' => 'Customer business record not found'
            ], 404);
        }

        $customer = $business->customer;

        // 2. Validation
        $validator = Validator::make($request->all(), [
            // cm_customer fields
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:cm_customer,phone,' . $customer->id,
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'customer_type' => 'required|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            // ad_customer_has_business fields
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email|max:255',
            'b2b_customer_type' => 'nullable|integer',
            'payment_terms' => 'nullable|integer',
            'visit_schedule' => 'nullable|integer',
            'preferred_time' => 'nullable',
            'preferred_visit_days' => 'nullable|string',
            'credit_limit' => 'nullable|numeric',
            'payment_terms_days' => 'nullable|integer',
            'allow_credit' => 'nullable',
            'route_id' => 'nullable|integer',
            'stop_sequence' => 'nullable|integer',
            'special_instructions' => 'nullable|string',
            'delivery_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'business_name' => 'nullable|string|max:255',
            'customer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            // 3. Handle Image Upload
            if ($request->hasFile('customer_image')) {
                // Delete old image if it exists
                if ($business->customer_image && file_exists(public_path($business->customer_image))) {
                    @unlink(public_path($business->customer_image));
                }

                $file = $request->file('customer_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/customers'), $filename);
                $business->customer_image = 'uploads/customers/' . $filename;
            }

            // 4. Update CmCustomer
            if ($customer) {
                $customer->update([
                    'name' => $request->name,
                    'customer_type' => $request->customer_type,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'updated_by' => auth()->id() ?? 1,
                ]);
            }

            // 5. Update AdCustomerHasBusiness
            $business->update([
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'contact_person_email' => $request->contact_person_email,
                'b2b_customer_type' => $request->b2b_customer_type ?? $business->b2b_customer_type,
                'payment_terms' => $request->payment_terms ?? $business->payment_terms,
                'visit_schedule' => $request->visit_schedule ?? $business->visit_schedule,
                'preferred_time' => $request->preferred_time,
                'preferred_visit_days' => $request->preferred_visit_days,
                'credit_limit' => $request->credit_limit,
                'payment_terms_days' => $request->payment_terms_days,
                'allow_credit' => $request->allow_credit === 'true' || $request->allow_credit === '1',
                'route_id' => $request->route_id,
                'stop_sequence' => $request->stop_sequence,
                'special_instructions' => $request->special_instructions,
                'delivery_instructions' => $request->delivery_instructions,
                'notes' => $request->notes,
                'business_name' => $request->business_name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Customer updated successfully',
                'data' => [
                    'customer' => $customer,
                    'business_details' => $business
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Update Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update customer: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getCustomerForEdit($id)
    {
        try {
            $business = AdCustomerHasBusiness::with(['customer'])->find($id);

            if (!$business) {
                return response()->json([
                    'status' => false,
                    'message' => 'Customer business record not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $business
            ]);
        } catch (\Exception $e) {
            Log::error('Fetch Customer For Edit Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch customer details'
            ], 500);
        }
    }
}
