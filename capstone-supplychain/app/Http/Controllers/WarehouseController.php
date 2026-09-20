<?php

namespace App\Http\Controllers;

use App\Models\StorageLocation;
use App\Models\Warehouse;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with(['storageLocations.inventories.product'])
            ->withCount('storageLocations')
            ->get();

        return view('warehouse.index', compact('warehouses'));
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['storageLocations.inventories.product']);
        return view('warehouse.show', compact('warehouse'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:warehouses,code|max:50',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'total_capacity_m3' => 'required|numeric|min:1',
        ]);

        $wh = Warehouse::create($validated);
        AuditLoggerService::log('CREATE_WAREHOUSE', 'SmartWarehousing', null, $wh->toArray());

        return redirect()->route('warehouse.index')->with('success', "Warehouse '{$wh->name}' created successfully!");
    }

    public function storeLocation(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:storage_locations,code|max:50',
            'zone' => 'required|string|max:100',
            'aisle' => 'required|string|max:50',
            'rack' => 'required|string|max:50',
            'shelf' => 'required|string|max:50',
            'max_weight_kg' => 'required|numeric|min:1',
            'max_volume_m3' => 'required|numeric|min:0.1',
        ]);

        $validated['warehouse_id'] = $warehouse->id;
        $loc = StorageLocation::create($validated);

        AuditLoggerService::log('CREATE_STORAGE_LOCATION', 'SmartWarehousing', null, $loc->toArray());

        return redirect()->route('warehouse.show', $warehouse->id)->with('success', "Storage Location '{$loc->code}' created!");
    }
}
