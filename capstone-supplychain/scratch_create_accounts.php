<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$adminRole = Role::where('name', 'admin')->first();
$whRole = Role::where('name', 'warehouse_staff')->first();
$procRole = Role::where('name', 'procurement_staff')->first();
$mgmtRole = Role::where('name', 'management')->first();

$acc1 = User::firstOrCreate(
    ['email' => 'admin.new@supplychain.test'],
    [
        'name' => 'New Administrator Account',
        'password' => Hash::make('password123'),
        'role_id' => $adminRole->id,
        'is_active' => true,
    ]
);

$acc2 = User::firstOrCreate(
    ['email' => 'warehouse.new@supplychain.test'],
    [
        'name' => 'New Warehouse Staff Account',
        'password' => Hash::make('password123'),
        'role_id' => $whRole->id,
        'is_active' => true,
    ]
);

$acc3 = User::firstOrCreate(
    ['email' => 'procurement.new@supplychain.test'],
    [
        'name' => 'New Procurement Staff Account',
        'password' => Hash::make('password123'),
        'role_id' => $procRole->id,
        'is_active' => true,
    ]
);

$acc4 = User::firstOrCreate(
    ['email' => 'executive.new@supplychain.test'],
    [
        'name' => 'New Executive Manager Account',
        'password' => Hash::make('password123'),
        'role_id' => $mgmtRole->id,
        'is_active' => true,
    ]
);

echo "SUCCESS: Created accounts for:\n";
echo "1. Admin: {$acc1->email} (Role: {$adminRole->display_name})\n";
echo "2. Warehouse Staff: {$acc2->email} (Role: {$whRole->display_name})\n";
echo "3. Procurement Staff: {$acc3->email} (Role: {$procRole->display_name})\n";
echo "4. Executive: {$acc4->email} (Role: {$mgmtRole->display_name})\n";
