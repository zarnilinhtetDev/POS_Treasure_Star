<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Item;
use Illuminate\Support\Collection;

class ItemsImportTemplate implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    /**
     * @var Item $item
     */

    /**
     * @return array
     */
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
}
