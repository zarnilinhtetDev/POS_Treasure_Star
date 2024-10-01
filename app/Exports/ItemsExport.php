<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use App\Models\Item;
use Illuminate\Support\Collection;

class ItemsExport implements FromCollection, FromQuery, WithMapping, WithHeadings
{
    public function collection()
    {
        // This method is not used when FromQuery is implemented.
        // If you need to use collection(), implement only FromCollection instead.
        return new Collection();
    }

    public function query()
    {
        if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
            return Item::query(); // Return all items for admin
        } else {
            $userLevel = auth()->user()->level;

            return Item::whereHas('warehouse', function ($query) use ($userLevel) {
                $query->where('id', $userLevel);
            });
        }
    }

    public function headings(): array
    {
        return [
            'Warhouse',
            'Item Name',
            'Barcode',
            'Description',
            'Expired Date',
            'Category',
            'Quantity',
            'Item Unit',
            'Reorder Level Stock',
            'လက်လီစျေး',
            'လက်ကားစျေး',
            'ဝယ်စျေး',
            'Parent ID',
        ];
    }

    public function map($item): array
    {
        $warehouse = $item->warehouse->name ?? null;
        return [
            'Warhouse' => $warehouse,
            'Item Name' => $item->item_name,
            'Barcode' => $item->barcode,
            'Description' => $item->descriptions, // Corrected key name
            'Expired Date' => $item->expired_date,
            'Category' => $item->category,
            'Quantity' => $item->quantity,
            'Item Unit' => $item->item_unit,
            'Reorder Level Stock' => $item->reorder_level_stock,
            'လက်လီစျေး' => $item->retail_price,
            'လက်ကားစျေး' => $item->wholesale_price,
            'ဝယ်စျေး' => $item->buy_price,
            'Parent ID' => $item->parent_id,
        ];
    }
}
