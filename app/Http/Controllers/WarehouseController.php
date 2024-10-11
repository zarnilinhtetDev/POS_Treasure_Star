<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\TransferHistory;
use App\Models\Transfer_Historyy;
use Illuminate\Support\Facades\Cache;


class WarehouseController extends Controller
{
    public function index()
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $warehouses = Warehouse::all();
        } else {
            $warehouses = Warehouse::whereIn('id', $warehousePermission)->get();
        }

        return view('warehouse.warehouse', compact('warehouses'));
    }
    public function warehouse_register(Request $request, Warehouse $warehouse)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
            ], [
                'phone_number.required' => 'Phone Number is required',
                'name.required' => 'Name is required',
                'address.required' => 'Address is required',
            ]);

            $warehouse->create($validated);

            return back()->with('success', 'Register Warehouse Successful');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . 'Something Wrong!');
        }
    }
    public function warehouse_delete($id)
    {
        $itemsCount = Item::where('warehouse_id', $id)->count();

        if ($itemsCount > 0) {
            return back()->with('error', 'Cannot delete warehouse because items are associated with it.');
        } else {
            Warehouse::destroy($id);
            return back()->with('delete', 'Warehouse deleted successfully.');
        }
    }

    public function warehouse_edit($id)
    {
        $warehouse = Warehouse::find($id);
        return view('warehouse.warehouse_edit', compact('warehouse'));
    }
    public function warehouse_Update($id, Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ], [
            'phone_number.required' => 'Phone Number is required',
            'name.required' => 'Name is required',
            'address.required' => 'Address is required',
        ]);
        $warehouse = Warehouse::find($id);
        $warehouse->update($validated);
        return redirect(url('warehouse'))->with('success', 'Warehouse Updated Successful');
    }
    public function transfer_item()
    {
        $warehouses = WareHouse::all();
        return view('warehouse.transfer_item', compact('warehouses'));
    }


    public function store_transfer_item(Request $request)
    {
        try {
            $from = Warehouse::find($request->from_location);
            $to = Warehouse::find($request->to_location);
            $lastItem = Item::latest()->first();

            $count = count($request->part_number);
            info($count);
            for ($i = 0; $i < $count; $i++) {

                $item_to = Item::where('item_name', $request->part_number[$i])
                    ->where('warehouse_id', $request->to_location)
                    ->first();
                $item_from = Item::where('item_name', $request->part_number[$i])
                    ->where('warehouse_id', $request->from_location)
                    ->first();

                if ($item_to) {
                    // Update quantity to location item
                    $item_to->quantity += $request->product_qty[$i];
                    $item_to->update();

                    // Update quantity from location item
                    $item_from->quantity -= $request->product_qty[$i];
                    $item_from->update();

                    // Store record history
                    $transfer_history = new TransferHistory();
                    $transfer_history->from_location = $request->from_location;
                    $transfer_history->to_location = $request->to_location;
                    $transfer_history->item_name = $request->part_number[$i];
                    $transfer_history->quantity = $request->product_qty[$i];
                    $transfer_history->date = $request->date;
                    $transfer_history->save();
                } else {
                    // Insert new item to location
                    $item = new Item();
                    $item->item_name = $request->part_number[$i];
                    $item->descriptions = $item_from->descriptions;
                    $item->barcode = $item_from->barcode;
                    $item->expired_date = $item_from->expired_date;
                    $item->category = $item_from->category;
                    $item->price = $item_from->price;
                    $item->company_price = $item_from->company_price;
                    $item->reorder_level_stock = $item_from->reorder_level_stock;
                    $item->retail_price = $item_from->retail_price;
                    $item->wholesale_price = $item_from->wholesale_price;
                    $item->buy_price = $item_from->buy_price;
                    $item->item_type = $item_from->item_type;
                    $item->item_unit = $item_from->item_unit;
                    $item->parent_id = $lastItem->id + 1;
                    $item->quantity = $request->product_qty[$i];
                    $item->warehouse_id = $request->to_location;
                    $item->save();


                    // Update quantity from location item
                    $item_from->quantity -= $request->product_qty[$i];
                    $item_from->update();

                    // Store record history
                    $transfer_history = new TransferHistory();
                    $transfer_history->from_location = $request->from_location;
                    $transfer_history->to_location = $request->to_location;
                    $transfer_history->item_name = $request->part_number[$i];
                    $transfer_history->quantity = $request->product_qty[$i];
                    $transfer_history->date = $request->date;
                    $transfer_history->save();
                }
            }

            return redirect('show_transfer_history')
                ->with('success', 'Successfully transferred items from ' . $from->name . ' to ' . $to->name);
        } catch (\Exception $e) {

            return redirect('show_transfer_history')
                ->with('warning', 'An error occurred while transferring items');
        }
    }

    public function autocompletePartCode(Request $request)
    {
        $query = $request->get('query');
        $location = $request->get('location');

        $cacheKey = "autocomplete_part_code_{$query}_{$location}";

        $items = Cache::remember($cacheKey, 60, function () use ($query, $location) {
            return Item::where('item_name', 'like', '%' . $query . '%')
                ->where('warehouse_id', $location)
                ->pluck('item_name');
        });

        return response()->json($items);
    }

    public function getPartData(Request $request)
    {
        $itemName = $request->item_name;
        $location = $request->location;

        $cacheKey = "get_part_data_{$itemName}_{$location}";

        $result = Cache::remember($cacheKey, 60, function () use ($itemName, $location) {
            return Item::where('item_name', $itemName)
                ->where('warehouse_id', $location)
                ->first();
        });

        if (!$result) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($result);
    }

    public function show_history()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $location_names = [];
            $histories = TransferHistory::all();

            foreach ($histories as $history) {
                $from = Warehouse::find($history->from_location);
                $to = Warehouse::find($history->to_location);

                // Check if $from and $to are not null before accessing their properties
                if ($from && $to) {
                    // Store location names along with history ID in the associative array
                    $location_names[$history->id] = [
                        'from' => $from->name,
                        'to' => $to->name
                    ];
                }
            }
        } else {
            $location_names = [];
            $histories = TransferHistory::whereIn('from_location', $warehousePermission)->get();

            foreach ($histories as $history) {
                $from = Warehouse::find($history->from_location);
                $to = Warehouse::find($history->to_location);
                if ($from && $to) {
                    $location_names[$history->id] = [
                        'from' => $from->name,
                        'to' => $to->name
                    ];
                }
            }
        }

        return view('warehouse.transfer_history', compact('histories', 'location_names'));
    }
}
