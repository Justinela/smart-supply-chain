<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogFilterAndStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_category_filtering()
    {
        $admin = User::whereHas('role', fn($q)=>$q->where('name', 'admin'))->first();
        $catElectronics = Category::where('name', 'Electronics & Sensors')->first();
        $catMetals = Category::where('name', 'Raw Metallic Materials')->first();

        // 1. Electronics & Sensors filter
        $responseElec = $this->actingAs($admin)->get(route('products.index', ['category_id' => $catElectronics->id]));
        $responseElec->assertStatus(200);
        $responseElec->assertSee('Industrial Optical Pressure Sensor');
        $responseElec->assertDontSee('Structural Aluminum Extrusion Bar (3m)');

        // 2. Raw Metallic Materials filter
        $responseMetal = $this->actingAs($admin)->get(route('products.index', ['category_id' => $catMetals->id]));
        $responseMetal->assertStatus(200);
        $responseMetal->assertSee('Structural Aluminum Extrusion Bar (3m)');
        $responseMetal->assertDontSee('Industrial Optical Pressure Sensor');
    }

    public function test_search_by_name_sku_and_brand()
    {
        $admin = User::whereHas('role', fn($q)=>$q->where('name', 'admin'))->first();

        // Search by name
        $responseName = $this->actingAs($admin)->get(route('products.index', ['search' => 'Aluminum']));
        $responseName->assertStatus(200);
        $responseName->assertSee('Structural Aluminum Extrusion Bar (3m)');
        $responseName->assertDontSee('Precision Digital Solder Station 80W');

        // Search by SKU
        $responseSKU = $this->actingAs($admin)->get(route('products.index', ['search' => 'SKU-TOOL-004']));
        $responseSKU->assertStatus(200);
        $responseSKU->assertSee('Precision Digital Solder Station 80W');
        $responseSKU->assertDontSee('Structural Aluminum Extrusion Bar (3m)');
    }

    public function test_activate_and_deactivate_product_status()
    {
        $admin = User::whereHas('role', fn($q)=>$q->where('name', 'admin'))->first();
        $product = Product::first();

        $this->assertTrue((bool)$product->is_active);

        // 1. Deactivate product
        $responseDeactivate = $this->actingAs($admin)->post(route('products.toggleStatus', $product->id));
        $responseDeactivate->assertRedirect(route('products.index'));
        $product->refresh();
        $this->assertFalse((bool)$product->is_active);

        // 2. Query inactive products filter
        $responseInactive = $this->actingAs($admin)->get(route('products.index', ['status' => 'inactive']));
        $responseInactive->assertStatus(200);
        $responseInactive->assertSee($product->name);

        // 3. Query active products filter - deactivated product must NOT appear
        $responseActive = $this->actingAs($admin)->get(route('products.index', ['status' => 'active']));
        $responseActive->assertStatus(200);
        $responseActive->assertDontSee($product->name);

        // 4. Reactivate product
        $responseReactivate = $this->actingAs($admin)->post(route('products.toggleStatus', $product->id));
        $responseReactivate->assertRedirect(route('products.index'));
        $product->refresh();
        $this->assertTrue((bool)$product->is_active);
    }
}
