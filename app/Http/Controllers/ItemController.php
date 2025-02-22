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
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemsUpdateImport;
//use File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{

    public function index()
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $warehouses = Warehouse::select('name', 'id')->get();
            $items = Item::with('warehouse')->latest()->paginate(100);
        } else {
            $warehouses = Warehouse::select('name', 'id')->get();
            $items = Item::whereIn('warehouse_id', $warehousePermission)->with('warehouse')->latest()->paginate(100);
        }

        return view('item.item', compact('items', 'warehouses'));
    }

    public function item_filter(Request $request)
    {
        $searchTerm = $request->input('query');

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $items = Item::with('warehouse')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('item_name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('category', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('barcode', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('retail_price', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('wholesale_price', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhereHas('warehouse', function ($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
                        })
                        ->orWhere('expired_date', 'LIKE', '%' . $searchTerm . '%');
                })
                ->latest()
                ->paginate(200);
        } else {
            $items = Item::whereIn('warehouse_id', $warehousePermission)
                ->where(function ($query) use ($searchTerm) {
                    $query->where('item_name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('category', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('barcode', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('retail_price', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('wholesale_price', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhereHas('warehouse', function ($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
                        })
                        ->orWhere('expired_date', 'LIKE', '%' . $searchTerm . '%');
                })
                ->with('warehouse')
                ->latest()
                ->paginate(200);
        }

        $choosePermission = auth()->user()->permissions ?? [];

        $items->transform(function ($item) use ($choosePermission) {
            $item->can_view = in_array('Item Details', $choosePermission) || auth()->user()->is_admin == '1';
            $item->can_edit = in_array('Item Edit', $choosePermission) || auth()->user()->is_admin == '1';
            $item->can_delete = in_array('Item Delete', $choosePermission) || auth()->user()->is_admin == '1';
            $item->can_in_out = in_array('Item In/Out', $choosePermission) || auth()->user()->is_admin == '1';
            return $item;
        });

        return response()->json($items);
    }



    public function register()
    {
        $units = Unit::all();
        $branchs = Warehouse::select('name', 'id')->get();
        return view('item.itemRegister', compact('branchs', 'units'));
    }
    public function generateRandomBarcode()
    {
        // Generate 12 random digits
        $barcode = rand(100000000000, 999999999999);
        // Calculate the check digit
        $digits = str_split($barcode);
        $sum = 0;
        foreach ($digits as $key => $digit) {
            $sum += ($key % 2 === 0) ? $digit : $digit * 3;
        }
        $checkDigit = (10 - ($sum % 10)) % 10;
        // Append check digit to the barcode
        $barcode = $barcode . $checkDigit;
        return $barcode;
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'item_unit' => 'required',
            'item_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'item_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ], [
            'item_unit.required' => 'Please Select Unit',
        ]);
        if (empty($request->barcode)) {
            $randomBarcode = $this->generateRandomBarcode();
            $request->merge(['barcode' => $randomBarcode]);
        } else {
            $barcodeWithoutCheckDigit = $request->barcode;

            if (strlen($barcodeWithoutCheckDigit) < 12) {
                $paddingLength = 12 - strlen($barcodeWithoutCheckDigit);
                for ($i = 0; $i < $paddingLength; $i++) {
                    $barcodeWithoutCheckDigit .= rand(1, 9);
                }
            } elseif (strlen($barcodeWithoutCheckDigit) > 12) {
                $barcodeWithoutCheckDigit = substr($barcodeWithoutCheckDigit, 0, 12);
            }
            $digits = array_map('intval', str_split($barcodeWithoutCheckDigit));
            $sum = 0;

            foreach ($digits as $key => $digit) {
                $sum += ($key % 2 === 0) ? $digit : $digit * 3;
            }

            $checkDigit = (10 - ($sum % 10)) % 10;
            $barcodeWithCheckDigit = $barcodeWithoutCheckDigit . $checkDigit;
            $request->merge(['barcode' => $barcodeWithCheckDigit]);
        }

        $items = new Item();
        $items->fill($validate);
        $items->fill($request->all());
        $image = $request->file('item_image');
        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('item_images'), $imagename);
            $items->item_image = $imagename;
        }

        //add second image
        $image2 = $request->file('item_image_2');
        if ($image2) {
            $imagename2 = time() . 'img2.' . $image2->getClientOriginalExtension();
            $image2->move(public_path('item_images'), $imagename2);
            $items->item_image_2 = $imagename2;
        }

        // dd($imagename , '-', $imagename2);

        $items->warehouse_id = $request->warehouse_id;
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
        $branchs = Warehouse::select('name', 'id')->get();
        return view('item.item_edit', compact('items', 'branchs', 'units'));
    }
    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        $request->validate([
            'item_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'item_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $image = $request->file('item_image');
        if ($image) {
            if ($item->item_image && file_exists(public_path('item_images/' . $item->item_image))) {
                unlink(public_path('item_images/' . $item->item_image));
            }
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('item_images'), $imagename);
            $item->item_image = $imagename;
        }

        //second image
        $image2 = $request->file('item_image_2');
        // dd($image2);
        if ($image2) {
            if ($item->item_image_2 && file_exists(public_path('item_images/' . $item->item_image_2))) {
                unlink(public_path('item_images/' . $item->item_image_2));
            }
            $imagename2 = time() . 'img2.' . $image2->getClientOriginalExtension();
            $image2->move(public_path('item_images'), $imagename2);
            $item->item_image_2 = $imagename2;
        }

        $item->update($request->except(['item_image', 'item_image_2']));
        $item->warehouse_id = $request->warehouse_id;
        $item->save();

        return redirect(url('items'))->with('success', 'Item Update Successfully');
    }
    public function delete($id)
    {
        $item = Item::find($id);

        $invoiceExists = Invoice::where('item_id', $id)->exists();

        if ($invoiceExists) {
            return redirect(url('items'))->with('error', 'Item cannot be deleted as it is associated with an invoice.');
        }


        if ($item) {
            if ($item->item_image && file_exists(public_path('item_images/' . $item->item_image))) {
                unlink(public_path('item_images/' . $item->item_image));
            }

            if ($item->item_image_2 && file_exists(public_path('item_images/' . $item->item_image_2))) {
                unlink(public_path('item_images/' . $item->item_image_2));
            }

            $item->delete();

            return redirect(url('items'))->with('delete', 'Item Deleted Successfully');
        }

        return redirect(url('items'))->with('error', 'Item Not Found');
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
            // dd($warehouseId);

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

    public function fileUpdateImport(Request $request)
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

            $warehouseId = $request->warehouse_id;
            $file = $request->file('file');
            $import = new ItemsUpdateImport($warehouseId);
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
    public function update_barcode()
    {
        $items = Item::all();
        $item_oribc = [];
        $item_genbc = [];
        foreach ($items as $key => $item) {
            if (strlen($item->barcode) < 13) {
                // echo $item->barcode . "<br>";
                $item_oribc[$key] = $item->barcode;
            } else {
                //  echo $item->barcode . "<br>";
                // $item_genbc[$key] = $item->barcode;
                // $ff = DB::table('items_server')->where('id', $item->id)->first();
                // $eeee = Item::find($item->id);
                // $eeee->barcode = $ff->barcode;
                // $eeee->update();

                $aa = DB::table('items_server')->where("warehouse_id", "2")->get();

                foreach ($aa as $key => $bb) {
                    if ($item->item_name == $bb->item_name) {
                        $bb->barcode = $item->barcode;
                        $bb->save();
                        DB::table('items_server')
                            ->where($bb->id, $item->id)
                            ->update([$bb->barcode => $item->barcode]);
                    }
                }
                // $item_oribc = $item->barcode;
                // $item_genbc = $item->barcode;

            }
            dd($item_genbc);
            dd('asdf');
            DB::statement('
        UPDATE items
        JOIN items_new ON items.id = items_new.id
        SET items.barcode = items_new.barcode
    ');
        }
    }
}
