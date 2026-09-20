<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'inventories.storageLocation.warehouse'])->latest();

        // 1. Search Filter: Name, SKU, Barcode, Brand
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // 2. Category Filter: Category ID or Category Name
        $catParam = $request->input('category_id', $request->input('category'));
        if (!empty($catParam)) {
            if (is_numeric($catParam)) {
                $query->where('category_id', $catParam);
            } else {
                $catSearch = trim($catParam);
                $query->whereHas('category', function ($cQ) use ($catSearch) {
                    $cQ->where('name', $catSearch)
                       ->orWhere('code', $catSearch);
                });
            }
        }

        // 3. Status Filter: Active (is_active = true) or Inactive (is_active = false)
        if ($request->filled('status')) {
            $statusVal = strtolower(trim($request->status));
            if ($statusVal === 'active' || $statusVal === '1') {
                $query->where('is_active', true);
            } elseif ($statusVal === 'inactive' || $statusVal === '0') {
                $query->where('is_active', false);
            }
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'unit_of_measure' => 'required|string|max:50',
            'min_stock_level' => 'required|integer|min:0',
            'max_stock_level' => 'required|integer|gte:min_stock_level',
            'reorder_point' => 'required|integer|min:0',
            'safety_stock' => 'required|integer|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'weight_kg' => 'required|numeric|min:0',
            'volume_m3' => 'required|numeric|min:0',
            'is_active' => 'nullable',
        ]);

        $newActiveStatus = filter_var($request->input('is_active', 1), FILTER_VALIDATE_BOOLEAN);

        $product = Product::create([
            'category_id' => $request->category_id,
            'sku' => strtoupper($request->sku),
            'barcode' => $request->barcode,
            'name' => $request->name,
            'brand' => $request->brand,
            'unit_of_measure' => $request->unit_of_measure,
            'min_stock_level' => $request->min_stock_level,
            'max_stock_level' => $request->max_stock_level,
            'reorder_point' => $request->reorder_point,
            'safety_stock' => $request->safety_stock,
            'unit_cost' => $request->unit_cost,
            'weight_kg' => $request->weight_kg,
            'volume_m3' => $request->volume_m3,
            'is_active' => $newActiveStatus,
        ]);

        AuditLoggerService::log('CREATE_PRODUCT', 'ProductCatalog', null, $product->toArray());

        return redirect()->route('products.index')->with('success', "Product '{$product->name}' (SKU: {$product->sku}) created successfully!");
    }

    public function show(Product $product)
    {
        $product->load(['category', 'inventories.storageLocation.warehouse', 'supplierProducts.supplier', 'demandHistories']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sku' => ['required', 'string', 'max:100', Rule::unique('products')->ignore($product->id)],
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('products')->ignore($product->id)],
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'unit_of_measure' => 'required|string|max:50',
            'min_stock_level' => 'required|integer|min:0',
            'max_stock_level' => 'required|integer|gte:min_stock_level',
            'reorder_point' => 'required|integer|min:0',
            'safety_stock' => 'required|integer|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'weight_kg' => 'required|numeric|min:0',
            'volume_m3' => 'required|numeric|min:0',
            'is_active' => 'nullable',
        ]);

        $old = $product->toArray();
        $newActiveStatus = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);

        $product->update([
            'category_id' => $request->category_id,
            'sku' => strtoupper($request->sku),
            'barcode' => $request->barcode,
            'name' => $request->name,
            'brand' => $request->brand,
            'unit_of_measure' => $request->unit_of_measure,
            'min_stock_level' => $request->min_stock_level,
            'max_stock_level' => $request->max_stock_level,
            'reorder_point' => $request->reorder_point,
            'safety_stock' => $request->safety_stock,
            'unit_cost' => $request->unit_cost,
            'weight_kg' => $request->weight_kg,
            'volume_m3' => $request->volume_m3,
            'is_active' => $newActiveStatus,
        ]);

        AuditLoggerService::log('UPDATE_PRODUCT', 'ProductCatalog', $old, $product->toArray());

        return redirect()->route('products.index')->with('success', "Product '{$product->name}' updated successfully!");
    }

    public function toggleStatus(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        $statusStr = $product->is_active ? 'ACTIVATED' : 'DEACTIVATED';
        AuditLoggerService::log('TOGGLE_PRODUCT_STATUS', 'ProductCatalog', null, ['product_id' => $product->id, 'new_status' => $statusStr]);

        return redirect()->route('products.index')->with('success', "Product '{$product->name}' has been {$statusStr}.");
    }
}
