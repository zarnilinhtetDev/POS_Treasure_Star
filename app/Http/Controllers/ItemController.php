<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Unit;
use App\Models\InOut;
use App\Models\Invoice;
use App\Models\Warehouse;
use App\Exports\ItemsExport;
use App\Imports\ItemsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ItemsImportTemplate;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    // public function index()
    // {
    //     $warehouses = Warehouse::all();
    //     $items = Item::latest()->get();
    //     return view('item.item', compact('items', 'warehouses'));
    // }
    public function index()
    {
        // Use eager loading to retrieve items along with their related warehouse data
        $warehouses = Warehouse::all();
        $items = Item::with('warehouse')->latest()->get();
        return view('item.item', compact('items', 'warehouses'));
    }
    public function register()
    {
        $units = Unit::all();
        $branchs = Warehouse::all();
        return view('item.itemRegister', compact('branchs', 'units'));
    }
    public function generateRandomBarcode()
    {
        $barcode = rand(1000000000000, 9999999999999);
        return (string)$barcode;
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'item_unit' => 'required',
        ], [
            'item_unit.required' => 'Please Select Unit',
        ]);
        $barcode = '';
        if (empty($request->barcode)) {
            $randomBarcode = $this->generateRandomBarcode();

            $request->merge(['barcode' => $randomBarcode]);
        }
        //dd($request->barcode);
        $items = new Item();
        $items->fill($validate);
        $items->fill($request->all());
        $items->warehouse_id = $request->warehouse_id;
        $items->barcode = $request->barcode;
        $items->save();
        return redirect(url('items'))->with('success', 'Item Register is Successfully');
    }
    public function details($id)
    {
        $items = Item::find($id);
        return view('item.item_details', compact('items'));
    }
    public function edit($id)
    {
        $items = Item::find($id);
        $units = Unit::all();
        $branchs = Warehouse::all();
        return view('item.item_edit', compact('items', 'branchs', 'units'));
    }
    public function update(Request $request, $id)
    {
        $item = Item::find($id);
        $item->update($request->all());
        $item->warehouse_id = $request->warehouse_id;
        $item->update();
        return redirect(url('items'))->with('success', 'Item Update Is Successfully');
    }
    public function delete($id)
    {
        $items = Item::find($id);
        $items->delete();
        return redirect(url('items'))->with('delete', 'Item Delete Is Successfully');
    }
    public function inout($id)
    {
        $items = Item::find($id);
        $inouts = Inout::where('items_id', $id)->latest()->get();
        $branchs = Warehouse::all();
        $invoices = Invoice::latest()->get();
        return view('inout.inout', compact('id', 'items', 'inouts', 'branchs', 'invoices'));
    }
    public function item_search(Request $request)
    {
        $items = Item::all();
        return response()->json($items);
    }
    //Excel
    public function fileImport(Request $request)
    {
        try {
            $request->validate([
                'warehouse_id' => 'required|exists:warehouses,id',
                'file' => 'required|file|mimes:xlsx,xls,csv',
            ], [
                'file.required' => 'Please upload a file.',
                'file.file' => 'The uploaded file is invalid.',
                'file.mimes' => 'The file must be a valid Excel or CSV file.',
            ]);
            // Get the warehouse_id from the request
            $warehouseId = $request->warehouse_id;
            // Get the file from the request
            $file = $request->file('file');
            // Import the file and associate it with the warehouse
            $import = new ItemsImport($warehouseId);
            Excel::import($import, $file->store('temp'));
            return back()->with('success', 'File Import Is Successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while importing the file: ' . $e->getMessage());
        }
    }
    public function fileExport()
    {
        return Excel::download(new ItemsExport, 'items.xlsx');
    }
    public function fileImportTemplate()
    {
        return Excel::download(new ItemsImportTemplate, 'items.xlsx');
    }
    public function barcode($id)
    {
        $productCode = Item::find($id);
        return view('item.barcode', [
            'productCode' => $productCode
        ]);
    }
    public function drop_table(Request $request)
    {
        DB::table('items')->truncate();
        return redirect()->back()->with('success', 'Table dropped successfully!');
    }
}