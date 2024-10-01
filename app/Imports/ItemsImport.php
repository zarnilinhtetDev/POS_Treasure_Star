<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class ItemsImport implements ToModel
{
    private $firstRowSkipped = false;
    private $hasErrors = false;
    private $skipImport = false;
    private $warehouseId; // Add a property to store the warehouse_id
    protected $rowCount = 0;
    public function __construct($warehouseId)
    {
        $this->warehouseId = $warehouseId; // Assign the warehouse_id passed from the controller
    }
    public function model(array $row)
    {
        $this->rowCount++;

        if (!$this->firstRowSkipped) {
            $this->firstRowSkipped = true;
            return null;
        }

        if ($this->hasErrors) {
            return null;
        }

        $warehouse = Warehouse::where('name', $row[0])->first();

        if (!$warehouse || $warehouse->id != $this->warehouseId) {
            return null;
        }

        if (empty($row[2])) {
            $row[2] = $this->generateRandomBarcode();
        }

        return new Item([
            'warehouse_id' => $warehouse->id,
            'item_name' => $row[1],
            'barcode' => (string)$row[2],
            'descriptions' => $row[3],
            'expired_date' => $row[4],
            'category' => $row[5],
            'quantity' => $row[6],
            'item_unit' => $row[7],
            'reorder_level_stock' => $row[8],
            'retail_price' => $row[9],
            'wholesale_price' => $row[10],
            'buy_price' => $row[11],
            'parent_id' => $row[12],
        ]);
    }


    public function generateRandomBarcode()
    {
        $barcodeBase = str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);
        $checksum = $this->calculateEAN13Checksum($barcodeBase);
        return $barcodeBase . $checksum;
    }

    public function formatBarcode($barcode)
    {
        $barcode = str_pad($barcode, 12, '0', STR_PAD_LEFT);
        if (strlen($barcode) == 13) {
            return $barcode;
        } else {
            $checksum = $this->calculateEAN13Checksum($barcode);
            return $barcode . $checksum;
        }
    }
    public function calculateEAN13Checksum($barcode)
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += ($i % 2 === 0 ? 1 : 3) * intval($barcode[$i]);
        }
        $mod = $sum % 10;
        return $mod === 0 ? 0 : 10 - $mod;
    }
    public function getRowCount()
    {
        return $this->rowCount;
    }
}
