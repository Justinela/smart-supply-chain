<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DemandHistory;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Role;
use App\Models\StorageLocation;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full system access and security administration.',
        ]);

        $warehouseRole = Role::create([
            'name' => 'warehouse_staff',
            'display_name' => 'Warehouse Staff',
            'description' => 'Manages inventory, stock movements, transfers, and receiving.',
        ]);

        $procurementRole = Role::create([
            'name' => 'procurement_staff',
            'display_name' => 'Procurement Staff',
            'description' => 'Manages procurement requests, sourcing, and purchase orders.',
        ]);

        $managementRole = Role::create([
            'name' => 'management',
            'display_name' => 'Executive Manager',
            'description' => 'Access to executive dashboards, reports, and AI analytics.',
        ]);

        // 2. Seed Users
        $adminUser = User::create([
            'role_id' => $adminRole->id,
            'name' => 'System Administrator',
            'username' => 'admin01',
            'email' => 'admin@supplychain.test',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $warehouseUser = User::create([
            'role_id' => $warehouseRole->id,
            'name' => 'John Warehouse',
            'username' => 'warehouse01',
            'email' => 'warehouse@supplychain.test',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $procurementUser = User::create([
            'role_id' => $procurementRole->id,
            'name' => 'Sarah Procurement',
            'username' => 'procurement01',
            'email' => 'procurement@supplychain.test',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $managementUser = User::create([
            'role_id' => $managementRole->id,
            'name' => 'Executive Manager',
            'username' => 'executive01',
            'email' => 'management@supplychain.test',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        // 3. Seed Categories
        $catElectronics = Category::create(['name' => 'Electronics & Sensors', 'code' => 'CAT-ELEC', 'description' => 'Industrial sensors, microcontrollers, and circuitry modules']);
        $catRawMaterials = Category::create(['name' => 'Raw Metallic Materials', 'code' => 'CAT-RAW', 'description' => 'Aluminum extrusions, steel rods, and copper wiring']);
        $catPackaging = Category::create(['name' => 'Packaging Materials', 'code' => 'CAT-PKG', 'description' => 'Corrugated boxes, bubble wrap, and pallet straps']);
        $catTools = Category::create(['name' => 'Industrial Power Tools', 'code' => 'CAT-TOOL', 'description' => 'Pneumatic drills, soldering stations, and meters']);

        // 4. Seed Warehouses & Storage Locations
        $wh1 = Warehouse::create([
            'code' => 'WH-MAIN-01',
            'name' => 'Central Logistics Distribution Hub',
            'address' => 'Building 4, Industrial Park East, Sector 7',
            'total_capacity_m3' => 2500.00,
            'current_occupancy_m3' => 450.00,
            'is_active' => true,
        ]);

        $wh2 = Warehouse::create([
            'code' => 'WH-NORTH-02',
            'name' => 'Northern Auxiliary Storage Warehouse',
            'address' => 'Complex B, Port Highway, North District',
            'total_capacity_m3' => 1200.00,
            'current_occupancy_m3' => 120.00,
            'is_active' => true,
        ]);

        // Create storage locations for WH1
        $loc1 = StorageLocation::create([
            'warehouse_id' => $wh1->id,
            'code' => 'WH1-A-01-B1',
            'zone' => 'Zone A (Electronics)',
            'aisle' => 'Aisle 01',
            'rack' => 'Rack 01',
            'shelf' => 'Shelf B1',
            'max_weight_kg' => 500.00,
            'max_volume_m3' => 4.5000,
            'occupied_volume_m3' => 1.2000,
            'is_active' => true,
        ]);

        $loc2 = StorageLocation::create([
            'warehouse_id' => $wh1->id,
            'code' => 'WH1-B-02-C3',
            'zone' => 'Zone B (Heavy Metals)',
            'aisle' => 'Aisle 02',
            'rack' => 'Rack 02',
            'shelf' => 'Shelf C3',
            'max_weight_kg' => 2000.00,
            'max_volume_m3' => 10.0000,
            'occupied_volume_m3' => 3.5000,
            'is_active' => true,
        ]);

        $loc3 = StorageLocation::create([
            'warehouse_id' => $wh1->id,
            'code' => 'WH1-C-03-A2',
            'zone' => 'Zone C (Packaging)',
            'aisle' => 'Aisle 03',
            'rack' => 'Rack 03',
            'shelf' => 'Shelf A2',
            'max_weight_kg' => 300.00,
            'max_volume_m3' => 8.0000,
            'occupied_volume_m3' => 2.1000,
            'is_active' => true,
        ]);

        // Storage locations for WH2
        $loc4 = StorageLocation::create([
            'warehouse_id' => $wh2->id,
            'code' => 'WH2-A-01-A1',
            'zone' => 'Zone A (Overflow)',
            'aisle' => 'Aisle 01',
            'rack' => 'Rack 01',
            'shelf' => 'Shelf A1',
            'max_weight_kg' => 800.00,
            'max_volume_m3' => 6.0000,
            'occupied_volume_m3' => 0.8000,
            'is_active' => true,
        ]);

        // 5. Seed Products
        $p1 = Product::create([
            'category_id' => $catElectronics->id,
            'sku' => 'SKU-ELEC-001',
            'name' => 'Industrial Optical Pressure Sensor',
            'unit_of_measure' => 'units',
            'min_stock_level' => 15,
            'max_stock_level' => 200,
            'reorder_point' => 30,
            'unit_cost' => 145.50,
            'weight_kg' => 0.350,
            'volume_m3' => 0.0020,
            'is_active' => true,
        ]);

        $p2 = Product::create([
            'category_id' => $catRawMaterials->id,
            'sku' => 'SKU-RAW-002',
            'name' => 'Structural Aluminum Extrusion Bar (3m)',
            'unit_of_measure' => 'pcs',
            'min_stock_level' => 40,
            'max_stock_level' => 500,
            'reorder_point' => 80,
            'unit_cost' => 38.00,
            'weight_kg' => 4.200,
            'volume_m3' => 0.0450,
            'is_active' => true,
        ]);

        $p3 = Product::create([
            'category_id' => $catPackaging->id,
            'sku' => 'SKU-PKG-003',
            'name' => 'Heavy Duty Corrugated Shipping Box (50x40x40cm)',
            'unit_of_measure' => 'packs',
            'min_stock_level' => 50,
            'max_stock_level' => 1000,
            'reorder_point' => 100,
            'unit_cost' => 12.25,
            'weight_kg' => 0.800,
            'volume_m3' => 0.0800,
            'is_active' => true,
        ]);

        $p4 = Product::create([
            'category_id' => $catTools->id,
            'sku' => 'SKU-TOOL-004',
            'name' => 'Precision Digital Solder Station 80W',
            'unit_of_measure' => 'units',
            'min_stock_level' => 5,
            'max_stock_level' => 50,
            'reorder_point' => 10,
            'unit_cost' => 210.00,
            'weight_kg' => 2.100,
            'volume_m3' => 0.0150,
            'is_active' => true,
        ]);

        // 6. Seed Initial Stock Balances & Transactions
        $inv1 = Inventory::create(['product_id' => $p1->id, 'storage_location_id' => $loc1->id, 'quantity_on_hand' => 85, 'quantity_reserved' => 5]);
        $inv2 = Inventory::create(['product_id' => $p2->id, 'storage_location_id' => $loc2->id, 'quantity_on_hand' => 140, 'quantity_reserved' => 0]);
        $inv3 = Inventory::create(['product_id' => $p3->id, 'storage_location_id' => $loc3->id, 'quantity_on_hand' => 45, 'quantity_reserved' => 0]); // Low stock!
        $inv4 = Inventory::create(['product_id' => $p4->id, 'storage_location_id' => $loc1->id, 'quantity_on_hand' => 12, 'quantity_reserved' => 2]);

        InventoryTransaction::create([
            'product_id' => $p1->id,
            'storage_location_id' => $loc1->id,
            'user_id' => $adminUser->id,
            'type' => 'STOCK_IN',
            'quantity_change' => 85,
            'quantity_before' => 0,
            'quantity_after' => 85,
            'reference_type' => 'INITIAL_SEED',
            'notes' => 'Initial stock seeding for capstone demonstration',
        ]);

        InventoryTransaction::create([
            'product_id' => $p3->id,
            'storage_location_id' => $loc3->id,
            'user_id' => $warehouseUser->id,
            'type' => 'STOCK_IN',
            'quantity_change' => 45,
            'quantity_before' => 0,
            'quantity_after' => 45,
            'reference_type' => 'INITIAL_SEED',
            'notes' => 'Initial stock seeding for corrugated packaging boxes',
        ]);

        // 7. Seed Suppliers & Supplier Products
        $s1 = Supplier::create([
            'code' => 'SUP-APEX-01',
            'company_name' => 'Apex Micro-Industrial Components Inc.',
            'contact_person' => 'Robert Vance',
            'email' => 'sales@apexmicro.test',
            'phone' => '+63 917 555 0192',
            'address' => 'Suite 801, Commerce Tower, Makati City',
            'tax_id' => '234-890-112-000',
            'rating_score' => 4.85,
            'is_active' => true,
        ]);

        $s2 = Supplier::create([
            'code' => 'SUP-METALS-02',
            'company_name' => 'Global Metals & Alloys Supply Corp',
            'contact_person' => 'Elena Rostova',
            'email' => 'orders@globalmetals.test',
            'phone' => '+63 928 444 8821',
            'address' => 'Industrial Zone 3, Valenzuela City',
            'tax_id' => '109-332-901-000',
            'rating_score' => 4.50,
            'is_active' => true,
        ]);

        SupplierProduct::create([
            'supplier_id' => $s1->id,
            'product_id' => $p1->id,
            'supplier_sku' => 'APEX-SENS-99',
            'unit_price' => 140.00,
            'lead_time_days' => 5,
            'minimum_order_qty' => 10,
            'is_preferred' => true,
        ]);

        SupplierProduct::create([
            'supplier_id' => $s2->id,
            'product_id' => $p2->id,
            'supplier_sku' => 'GMA-ALU-3M',
            'unit_price' => 36.50,
            'lead_time_days' => 7,
            'minimum_order_qty' => 20,
            'is_preferred' => true,
        ]);

        // 8. Seed Historical Demand Records (Past 45 days of realistic data for Scikit-Learn training!)
        $products = [$p1, $p2, $p3, $p4];
        $startDate = Carbon::now()->subDays(45);

        foreach ($products as $product) {
            $baseDemand = match($product->id) {
                $p1->id => 12,
                $p2->id => 25,
                $p3->id => 35,
                $p4->id => 4,
                default => 10,
            };

            for ($i = 0; $i < 45; $i++) {
                $currentDate = (clone $startDate)->addDays($i);
                $dayOfWeek = $currentDate->dayOfWeek; // 0 = Sun, 6 = Sat
                $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);

                // Add variance & weekend reduction factor
                $variance = rand(-3, 6);
                $weekendMultiplier = $isWeekend ? 0.3 : 1.0;
                $quantityIssued = max(0, (int)round(($baseDemand + $variance) * $weekendMultiplier));

                DemandHistory::create([
                    'product_id' => $product->id,
                    'date' => $currentDate->format('Y-m-d'),
                    'quantity_issued' => $quantityIssued,
                    'stockouts_recorded' => ($product->id === $p3->id && $i > 38) ? rand(1, 3) : 0,
                    'moving_avg_7d' => round($baseDemand * 0.95, 2),
                    'moving_avg_30d' => round($baseDemand * 0.98, 2),
                    'month' => $currentDate->month,
                    'day_of_week' => $dayOfWeek,
                    'is_weekend' => $isWeekend,
                ]);
            }
        }
    }
}
