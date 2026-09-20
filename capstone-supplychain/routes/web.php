<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login/otp', [AuthController::class, 'showOtpForm'])->name('login.otp');
Route::post('/login/otp', [AuthController::class, 'verifyOtp'])->name('login.otp.verify');
Route::post('/login/otp/resend', [AuthController::class, 'resendOtp'])->name('login.otp.resend');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
Route::post('/change-password/send-code', [AuthController::class, 'sendChangePasswordCode'])->name('change_password.send_code');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change_password');
Route::get('/captcha/image', [CaptchaController::class, 'generateImage'])->name('captcha.image');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products & Catalog Management
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::post('/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

    // Smart Warehousing System (SWS)
    Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    Route::post('/warehouse', [WarehouseController::class, 'store'])->name('warehouse.store');
    Route::get('/warehouse/{warehouse}', [WarehouseController::class, 'show'])->name('warehouse.show');
    Route::post('/warehouse/{warehouse}/location', [WarehouseController::class, 'storeLocation'])->name('warehouse.storeLocation');

    // Inventory Management System (IMS)
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/transactions', [InventoryController::class, 'transactions'])->name('inventory.transactions');
    Route::post('/inventory/recommend-location', [InventoryController::class, 'recommendLocation'])->name('inventory.recommendLocation');

    // Stock Movement Operations (Warehouse Staff & Admin only)
    Route::middleware([RoleMiddleware::class . ':warehouse_staff,admin'])->group(function () {
        Route::post('/inventory/stock-in', [InventoryController::class, 'stockIn'])->name('inventory.stockIn');
        Route::post('/inventory/stock-out', [InventoryController::class, 'stockOut'])->name('inventory.stockOut');
        Route::post('/inventory/transfer', [InventoryController::class, 'transfer'])->name('inventory.transfer');
    });

    // Procurement & Sourcing Management (PSM)
    Route::get('/procurement', [ProcurementController::class, 'index'])->name('procurement.index');
    Route::post('/procurement', [ProcurementController::class, 'store'])->name('procurement.store');
    
    Route::middleware([RoleMiddleware::class . ':procurement_staff,management,admin'])->group(function () {
        Route::post('/procurement/{requestModel}/approve', [ProcurementController::class, 'approve'])->name('procurement.approve');
    });

    // Supplier / Vendor Management
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::post('/suppliers/{supplier}/product', [SupplierController::class, 'addProduct'])->name('suppliers.addProduct');

    // Purchase Order Management
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    
    Route::middleware([RoleMiddleware::class . ':procurement_staff,management,admin'])->group(function () {
        Route::post('/purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
    });

    Route::middleware([RoleMiddleware::class . ':warehouse_staff,admin'])->group(function () {
        Route::post('/purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    });

    // Document Tracking & Logistics Records System (DTRS)
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Analytics & Reports (Management & Admin)
    Route::middleware([RoleMiddleware::class . ':management,admin'])->group(function () {
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    });

    // Administration & User Management (Admin Only)
    Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');

        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
