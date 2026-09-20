<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('supplierProducts')
            ->orderBy('company_name', 'asc')
            ->paginate(15);

        return view('suppliers.index', compact('suppliers'));
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['supplierProducts.product', 'performances.purchaseOrder']);
        $products = Product::where('is_active', true)->get();

        return view('suppliers.show', compact('supplier', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:suppliers,code|max:50',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string|max:500',
            'tax_id' => 'nullable|string|max:50',
        ]);

        $supplier = Supplier::create($validated);
        AuditLoggerService::log('CREATE_SUPPLIER', 'SupplierManagement', null, $supplier->toArray());

        return redirect()->route('suppliers.index')->with('success', "Supplier '{$supplier->company_name}' registered successfully!");
    }

    public function addProduct(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'unit_price' => 'required|numeric|min:0.01',
            'lead_time_days' => 'required|integer|min:1',
            'minimum_order_qty' => 'required|integer|min:1',
        ]);

        $sp = SupplierProduct::updateOrCreate(
            ['supplier_id' => $supplier->id, 'product_id' => $validated['product_id']],
            [
                'unit_price' => $validated['unit_price'],
                'lead_time_days' => $validated['lead_time_days'],
                'minimum_order_qty' => $validated['minimum_order_qty'],
            ]
        );

        return redirect()->route('suppliers.show', $supplier->id)->with('success', "Product added to supplier catalog.");
    }
}
