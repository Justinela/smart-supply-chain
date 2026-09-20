<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false; // created_at only

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'ip_address',
        'user_agent',
        'old_values_json',
        'new_values_json',
        'created_at',
    ];

    protected $casts = [
        'old_values_json' => 'array',
        'new_values_json' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formattedDescription(): string
    {
        $new = $this->new_values_json ?? [];
        $old = $this->old_values_json ?? [];

        switch ($this->action) {
            case 'TOGGLE_PRODUCT_STATUS':
            case 'PRODUCT_STATUS_UPDATE':
                $pId = $new['product_id'] ?? ($old['product_id'] ?? null);
                $status = $new['new_status'] ?? ($new['is_active'] ?? 'updated');
                $product = $pId ? Product::find($pId) : null;
                $name = $product ? "'{$product->name}' (SKU: {$product->sku})" : "Product #{$pId}";
                $statusBadge = ($status === 'ACTIVATED' || $status === 1 || $status === '1')
                    ? '<span class="badge bg-success-subtle text-success">ACTIVATED</span>'
                    : '<span class="badge bg-danger-subtle text-danger">DEACTIVATED</span>';
                return "Set status of <strong>{$name}</strong> to {$statusBadge}";

            case 'TOGGLE_USER_STATUS':
                $uId = $new['user_id'] ?? null;
                $status = $new['new_status'] ?? '';
                $user = $uId ? User::find($uId) : null;
                $name = $user ? "'{$user->name}' ({$user->email})" : "User #{$uId}";
                $statusBadge = ($status === 'ACTIVATED')
                    ? '<span class="badge bg-success-subtle text-success">ACTIVATED</span>'
                    : '<span class="badge bg-danger-subtle text-danger">DEACTIVATED</span>';
                return "Changed status of <strong>{$name}</strong> to {$statusBadge}";

            case 'CREATE_PRODUCT':
                $name = $new['name'] ?? 'New Product';
                $sku = $new['sku'] ?? '';
                $cost = isset($new['unit_cost']) ? '₱' . number_format($new['unit_cost'], 2) : '';
                return "Created new product <strong>'{$name}'</strong> (SKU: <code>{$sku}</code>, Cost: {$cost})";

            case 'UPDATE_PRODUCT':
                $name = $new['name'] ?? ($old['name'] ?? 'Product');
                return "Updated product details for <strong>'{$name}'</strong>";

            case 'CREATE_PURCHASE_ORDER':
                $poNum = $new['po_number'] ?? 'PO';
                $amt = isset($new['total_amount']) ? '₱' . number_format($new['total_amount'], 2) : '';
                return "Created Purchase Order <strong>{$poNum}</strong> (Total: {$amt})";

            case 'APPROVE_PURCHASE_ORDER':
                $poNum = $new['po_number'] ?? 'PO';
                return "Approved Purchase Order <strong>{$poNum}</strong>";

            case 'RECEIVE_PO_GOODS':
                $poId = $new['purchase_order_id'] ?? '';
                return "Received & stocked-in goods for Purchase Order #" . ($poId ?: 'Record');

            case 'CREATE_USER':
                $email = $new['email'] ?? '';
                return "Created new user account for <strong>{$email}</strong>";

            case 'UPDATE_USER':
                $name = $new['name'] ?? ($old['name'] ?? 'User');
                return "Updated account profile for <strong>{$name}</strong>";

            case 'RESET_USER_PASSWORD':
                $uId = $new['user_id'] ?? null;
                $user = $uId ? User::find($uId) : null;
                $name = $user ? $user->name : "User #{$uId}";
                return "Reset password for account <strong>{$name}</strong>";

            case 'CREATE_SUPPLIER':
                $comp = $new['company_name'] ?? 'Supplier';
                $code = $new['code'] ?? '';
                return "Registered new supplier <strong>'{$comp}'</strong> (Code: {$code})";

            case 'CREATE_WAREHOUSE':
                $whName = $new['name'] ?? 'Warehouse';
                $code = $new['code'] ?? '';
                return "Created warehouse facility <strong>'{$whName}'</strong> ({$code})";

            case 'CREATE_STORAGE_LOCATION':
                $code = $new['code'] ?? 'Bin';
                $zone = $new['zone'] ?? '';
                return "Created storage bin location <strong>'{$code}'</strong> ({$zone})";

            case 'CREATE_PROCUREMENT_REQUEST':
                $prNum = $new['request_number'] ?? 'Requisition';
                return "Submitted Purchase Requisition <strong>{$prNum}</strong>";

            case 'APPROVE_PROCUREMENT_REQUEST':
                $prNum = $new['request_number'] ?? 'Requisition';
                return "Approved Purchase Requisition <strong>{$prNum}</strong>";

            case 'UPLOAD_DOCUMENT':
                $title = $new['title'] ?? 'Document';
                $docNum = $new['document_number'] ?? '';
                return "Uploaded document <strong>'{$title}'</strong> ({$docNum})";

            case 'DOWNLOAD_DOCUMENT':
                $docId = $new['document_id'] ?? '';
                return "Downloaded tracked document #" . $docId;

            case 'STOCK_IN':
                $qty = $new['quantity_change'] ?? ($new['quantity'] ?? 0);
                return "Performed Stock-In of <span class=\"badge bg-success-subtle text-success\">+{$qty} units</span>";

            case 'STOCK_OUT':
                $qty = abs($new['quantity_change'] ?? ($new['quantity'] ?? 0));
                return "Performed Stock-Out of <span class=\"badge bg-danger-subtle text-danger\">-{$qty} units</span>";

            case 'TRANSFER':
                $qty = $new['quantity'] ?? 0;
                return "Transferred <strong>{$qty} units</strong> between warehouse bins";

            case 'CREATE_CATEGORY':
                $cat = $new['name'] ?? 'Category';
                return "Created product category <strong>'{$cat}'</strong>";

            case 'UPDATE_CATEGORY':
                $cat = $new['name'] ?? ($old['name'] ?? 'Category');
                return "Updated product category <strong>'{$cat}'</strong>";

            case 'REGISTER':
                $email = $new['email'] ?? '';
                return "Registered new user account <strong>{$email}</strong>";

            case 'LOGIN':
                $email = $new['email'] ?? '';
                return "User logged into system session ({$email})";

            case 'LOGOUT':
                return "User logged out of session";

            default:
                if (!empty($new)) {
                    $items = [];
                    foreach ($new as $k => $v) {
                        if (is_scalar($v)) {
                            $items[] = "<strong>" . str_replace('_', ' ', $k) . ":</strong> " . e($v);
                        }
                    }
                    return implode(' • ', array_slice($items, 0, 3));
                }
                return str_replace('_', ' ', $this->action);
        }
    }
}
