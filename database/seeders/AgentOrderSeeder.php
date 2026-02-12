<?php

namespace Database\Seeders;

use App\CommonVariables;
use App\Models\AdAgent;
use App\Models\CmCustomer;
use App\Models\PmProductItem;
use App\Models\StmOrderRequest;
use App\Models\StmOrderRequestHasProduct;
use App\Models\UmBranch;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AgentOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get Agents
        $agents = AdAgent::where('status', 1)->get();
        if ($agents->isEmpty()) {
            $this->command->info('No active agents found. Please seed AdAgent table first.');

            return;
        }

        // 2. Get Customers
        $customers = CmCustomer::all();
        if ($customers->isEmpty()) {
            $this->command->info('No customers found. Creating dummy customers...');
            for ($i = 0; $i < 5; $i++) {
                CmCustomer::create([
                    'name' => 'Customer '.($i + 1),
                    'customer_type' => 1,
                    'phone' => '077'.rand(1000000, 9999999),
                    'address' => 'Generated Address '.($i + 1),
                    'created_by' => 1,
                ]);
            }
            $customers = CmCustomer::all();
        }

        // 3. Get Products
        $products = PmProductItem::where('status', 1)->get();
        if ($products->isEmpty()) {
            $this->command->info('No active products found. Please seed PmProductItem table first.');

            return;
        }

        // 4. Get Branches
        $branches = UmBranch::where('status', 1)->get();
        $defaultBranchId = $branches->isNotEmpty() ? $branches->first()->id : 1;

        // 5. Create Random Orders
        $numberOfOrders = 20;

        for ($i = 0; $i < $numberOfOrders; $i++) {
            $agent = $agents->random();
            $customer = $customers->random();

            // Randomly select a branch from available branches or default
            $branchId = $branches->isNotEmpty() ? $branches->random()->id : $defaultBranchId;

            // Create Order Request
            $order = StmOrderRequest::create([
                'order_number' => 'ORD-'.strtoupper(uniqid()),
                'branch_id' => $branchId,
                'customer_id' => $customer->id,
                'agent_id' => $agent->id,
                'order_type' => CommonVariables::$orderTypeAgentOrder ?? 4, // Fallback if constant not accessible for some reason
                'delivery_type' => CommonVariables::$deliveryTypeDelivery ?? 2,
                'delivery_date' => Carbon::now()->addDays(rand(1, 5)),
                'status' => CommonVariables::$orderRequestPendingApproval ?? 0,
                'grand_total' => 0,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            $grandTotal = 0;

            // Pick random products
            $itemsCount = rand(1, 5);
            $randomProducts = $products->count() > 1
                ? $products->random($itemsCount > $products->count() ? $products->count() : $itemsCount)
                : $products;

            // Ensure collection
            if (! ($randomProducts instanceof \Illuminate\Support\Collection)) {
                $randomProducts = collect([$randomProducts]);
            }

            foreach ($randomProducts as $product) {
                $qty = rand(1, 20);
                // Use selling_price if available, otherwise random
                $price = ($product->selling_price && $product->selling_price > 0) ? $product->selling_price : rand(100, 1000);
                $subtotal = $qty * $price;

                StmOrderRequestHasProduct::create([
                    'stm_order_request_id' => $order->id,
                    'pm_product_item_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $grandTotal += $subtotal;
            }

            // Update grand total
            $order->update(['grand_total' => $grandTotal]);
        }

        $this->command->info("Successfully created {$numberOfOrders} agent orders.");
    }
}
