<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\PmProduct;
use App\Models\PmProductType;
use App\Models\PmBrand;
use App\Models\PmVariation;
use App\Models\PmVariationValue;
use App\Models\PmProductItem;
use Illuminate\Support\Facades\DB;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create user for authentication
        $this->user = User::factory()->create();
    }

    /** @test */
    public function can_create_new_product_and_items()
    {
        $this->actingAs($this->user);

        // Setup dependencies
        $brand = PmBrand::create(['brand_name' => 'Test Brand', 'status' => 1]);
        $type = PmProductType::create(['product_type_name' => 'Test Type', 'status' => 1]);
        $variation = PmVariation::create(['variation_name' => 'Test Variation', 'status' => 1]);
        $variationValue = PmVariationValue::create([
            'pm_variation_id' => $variation->id,
            'variation_value' => 'Test Val',
            'unit_of_measurement_id' => 1,
            'status' => 1
        ]);

        $payload = [
            'name' => 'New Test Product',
            'description' => 'Description here',
            'brand' => $brand->id, // Controller uses this top-level brand (wait, line 60 says $request->brand)
            'items' => [
                [
                    'name' => 'Item 1',
                    'variation_id' => $variation->id,
                    'variation_value_id' => $variationValue->id,
                    'reference_number' => 'REF001',
                    'product_types' => [$type->id]
                ]
            ],
        ];

        $response = $this->postJson(route('product.store'), $payload);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('pm_products', ['product_name' => 'New Test Product']);
        $this->assertDatabaseHas('pm_product_item', [
            'product_name' => 'Item 1',
            'reference_number' => 'REF001'
        ]);
    }

    /** @test */
    public function can_view_product_management_index()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('productManagement.index'));
        $response->assertStatus(200);
        $response->assertViewIs('productManagement.productManage');
    }

    /** @test */
    public function can_search_products()
    {
        $this->actingAs($this->user);

        PmProduct::create([
            'product_name' => ' searchable product ',
            'status' => 1
        ]);

        $response = $this->get(route('product.search', ['query' => 'searchable']));

        $response->assertStatus(200)
            ->assertJsonFragment(['product_name' => ' searchable product ']);
    }

    /** @test */
    public function can_fetch_product_items()
    {
        $this->actingAs($this->user);

        $product = PmProduct::create(['product_name' => 'P1', 'status' => 1]);
        $brand = PmBrand::create(['brand_name' => 'B1', 'status' => 1]);

        PmProductItem::create([
            'pm_product_id' => $product->id,
            'pm_brands_id' => $brand->id,
            'product_name' => 'Item 1',
            'status' => 1
        ]);

        $response = $this->get(route('product.items.fetch', [
            'product_id' => $product->id,
            'brand_id' => $brand->id
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment(['product_name' => 'Item 1']);
    }

    /** @test */
    public function can_update_product_status_to_archive()
    {
        $this->actingAs($this->user);

        $product = PmProduct::create(['product_name' => 'P1', 'status' => 1]);
        $item = PmProductItem::create([
            'pm_product_id' => $product->id,
            'product_name' => 'Item To Archive',
            'status' => 1
        ]);

        $response = $this->postJson(route('product.status.update'), ['id' => $item->id]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('pm_product_item', [
            'id' => $item->id,
            'status' => 0
        ]);
    }

    /** @test */
    public function can_manage_product_types()
    {
        $this->actingAs($this->user);

        // Store
        $response = $this->postJson(route('productTypes.store'), ['product_type_name' => 'New Type']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('pm_product_type', ['product_type_name' => 'New Type']);

        $type = PmProductType::where('product_type_name', 'New Type')->first();

        // Update
        $response = $this->postJson(route('productTypes.update'), ['id' => $type->id, 'product_type_name' => 'Updated Type']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('pm_product_type', ['product_type_name' => 'Updated Type']);

        // Delete
        $response = $this->deleteJson(route('productTypes.delete'), ['id' => $type->id]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('pm_product_type', ['id' => $type->id]);
    }

    /** @test */
    public function can_manage_brands()
    {
        $this->actingAs($this->user);

        // Store
        $response = $this->postJson(route('brands.store'), ['brand_name' => 'New Brand']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('pm_brands', ['brand_name' => 'New Brand']);

        $brand = PmBrand::where('brand_name', 'New Brand')->first();

        // Update
        $response = $this->postJson(route('brands.update'), ['id' => $brand->id, 'brand_name' => 'Updated Brand']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('pm_brands', ['brand_name' => 'Updated Brand']);

        // Delete
        $response = $this->deleteJson(route('brands.delete'), ['id' => $brand->id]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('pm_brands', ['id' => $brand->id]);
    }
}
