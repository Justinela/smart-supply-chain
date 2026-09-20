<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransfer;
use App\Models\Product;
use App\Models\StorageLocation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryLedgerService
{
    /**
     * Perform Stock-In operation.
     */
    public function stockIn(int $productId, int $storageLocationId, int $quantity, ?int $userId, string $referenceType = 'STOCK_IN', ?int $referenceId = null, ?string $notes = null): InventoryTransaction
    {
        if ($quantity <= 0) {
            throw new Exception("Stock-In quantity must be greater than zero.");
        }

        $product = Product::find($productId);
        if (!$product || !$product->is_active) {
            throw new Exception("Stock operation failed: Product does not exist or is inactive.");
        }

        $location = StorageLocation::with('warehouse')->find($storageLocationId);
        if (!$location || !$location->is_active || !$location->warehouse || !$location->warehouse->is_active) {
            throw new Exception("Stock operation failed: Storage location or associated warehouse is inactive or disabled.");
        }

        // Capacity Check
        $incomingVolume = $quantity * floatval($product->volume_m3 ?? 0);
        $availableVolume = floatval($location->max_volume_m3) - floatval($location->occupied_volume_m3);
        if (floatval($location->max_volume_m3) > 0 && $incomingVolume > $availableVolume) {
            throw new Exception("Stock-In rejected: Location '{$location->code}' capacity exceeded. Available volume: {$availableVolume} m³, Required: {$incomingVolume} m³.");
        }

        return DB::transaction(function () use ($productId, $storageLocationId, $quantity, $userId, $referenceType, $referenceId, $notes, $product, $location, $incomingVolume) {
            $inventory = Inventory::where('product_id', $productId)
                ->where('storage_location_id', $storageLocationId)
                ->lockForUpdate()
                ->first();

            $quantityBefore = $inventory ? $inventory->quantity_on_hand : 0;
            $quantityAfter = $quantityBefore + $quantity;

            if ($inventory) {
                $inventory->quantity_on_hand = $quantityAfter;
                $inventory->save();
            } else {
                $inventory = Inventory::create([
                    'product_id' => $productId,
                    'storage_location_id' => $storageLocationId,
                    'quantity_on_hand' => $quantityAfter,
                    'quantity_reserved' => 0,
                ]);
            }

            // Update StorageLocation occupied volume
            if ($incomingVolume > 0) {
                $location->occupied_volume_m3 += $incomingVolume;
                $location->save();
            }

            return InventoryTransaction::create([
                'product_id' => $productId,
                'storage_location_id' => $storageLocationId,
                'user_id' => $userId,
                'type' => 'STOCK_IN',
                'quantity_change' => $quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'notes' => $notes ?? 'Stock In transaction recorded',
                'created_at' => now(),
            ]);
        });
    }

    /**
     * Perform Stock-Out operation.
     */
    public function stockOut(int $productId, int $storageLocationId, int $quantity, ?int $userId, string $referenceType = 'STOCK_OUT', ?int $referenceId = null, ?string $notes = null): InventoryTransaction
    {
        if ($quantity <= 0) {
            throw new Exception("Stock-Out quantity must be greater than zero.");
        }

        $product = Product::find($productId);
        if (!$product || !$product->is_active) {
            throw new Exception("Stock operation failed: Product does not exist or is inactive.");
        }

        $location = StorageLocation::with('warehouse')->find($storageLocationId);
        if (!$location || !$location->is_active || !$location->warehouse || !$location->warehouse->is_active) {
            throw new Exception("Stock operation failed: Storage location or associated warehouse is inactive.");
        }

        return DB::transaction(function () use ($productId, $storageLocationId, $quantity, $userId, $referenceType, $referenceId, $notes, $product, $location) {
            $inventory = Inventory::where('product_id', $productId)
                ->where('storage_location_id', $storageLocationId)
                ->lockForUpdate()
                ->first();

            $availableOnHand = $inventory ? $inventory->quantity_on_hand : 0;
            $reserved = $inventory ? $inventory->quantity_reserved : 0;
            $unreservedAvailable = max(0, $availableOnHand - $reserved);

            if (!$inventory || $unreservedAvailable < $quantity) {
                throw new Exception("Stock-Out rejected: Insufficient unreserved inventory on hand. Total On Hand: {$availableOnHand}, Reserved: {$reserved}, Available Unreserved: {$unreservedAvailable}, Requested: {$quantity}.");
            }

            $quantityBefore = $inventory->quantity_on_hand;
            $quantityAfter = $quantityBefore - $quantity;

            $inventory->quantity_on_hand = $quantityAfter;
            $inventory->save();

            // Update StorageLocation occupied volume
            $releasedVolume = $quantity * floatval($product->volume_m3 ?? 0);
            if ($releasedVolume > 0) {
                $location->occupied_volume_m3 = max(0, $location->occupied_volume_m3 - $releasedVolume);
                $location->save();
            }

            return InventoryTransaction::create([
                'product_id' => $productId,
                'storage_location_id' => $storageLocationId,
                'user_id' => $userId,
                'type' => 'STOCK_OUT',
                'quantity_change' => -$quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'notes' => $notes ?? 'Stock Out transaction recorded',
                'created_at' => now(),
            ]);
        });
    }

    /**
     * Execute Location Transfer.
     */
    public function transfer(int $productId, int $sourceLocationId, int $destinationLocationId, int $quantity, int $initiatedByUserId, bool $aiRecommended = false, ?string $notes = null): InventoryTransfer
    {
        if ($sourceLocationId === $destinationLocationId) {
            throw new Exception("Source and destination storage locations cannot be identical.");
        }

        return DB::transaction(function () use ($productId, $sourceLocationId, $destinationLocationId, $quantity, $initiatedByUserId, $aiRecommended, $notes) {
            $transferNum = 'TRF-' . strtoupper(Str::random(8));

            // Stock Out from Source
            $this->stockOut($productId, $sourceLocationId, $quantity, $initiatedByUserId, 'TRANSFER_OUT', null, "Transfer {$transferNum} to Location #{$destinationLocationId}");

            // Stock In to Destination
            $this->stockIn($productId, $destinationLocationId, $quantity, $initiatedByUserId, 'TRANSFER_IN', null, "Transfer {$transferNum} from Location #{$sourceLocationId}");

            return InventoryTransfer::create([
                'transfer_number' => $transferNum,
                'product_id' => $productId,
                'source_location_id' => $sourceLocationId,
                'destination_location_id' => $destinationLocationId,
                'quantity' => $quantity,
                'status' => 'COMPLETED',
                'ai_recommended' => $aiRecommended,
                'initiated_by_user_id' => $initiatedByUserId,
                'approved_by_user_id' => $initiatedByUserId,
            ]);
        });
    }
}
