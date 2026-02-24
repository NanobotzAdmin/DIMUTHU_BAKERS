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
    public function createCustomer (Request $request)
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
            'customer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Fixed to accept image files
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

            // 3. Create Customer in cm_customer
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

            // 4. Create Business Details in ad_customer_has_business
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
                'agent_id' => $request->agent_id,
                'route_id' => $request->route_id,
                'stop_sequence' => $request->stop_sequence,
                'special_instructions' => $request->special_instructions,
                'delivery_instructions' => $request->delivery_instructions,
                'notes' => $request->notes,
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
}
