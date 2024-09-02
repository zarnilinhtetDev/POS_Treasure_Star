<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Sell;
use App\Models\Unit;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\PO_sells;
use App\Models\Supplier;
use App\Models\Warehouse;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //
    public function index()
    {


        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $invoices = Invoice::where('status', 'invoice')->latest()->get();
            $branchs = Warehouse::all();
        } else {
            $invoices = Invoice::whereIn('branch', $warehousePermission)->where('status', 'invoice')->latest()->get();
            $branchs = Warehouse::all();
        }

        return view('invoice.invoice_manage', compact('invoices', 'branchs'));
    }


    public function quotation()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $quotations = Invoice::where('status', 'quotation')->latest()->get();
            $branchs = Warehouse::all();
        } else {
            $quotations = Invoice::whereIn('branch', $warehousePermission)->where('status', 'quotation')->latest()->get();
            $branchs = Warehouse::all();
        }
        return view('quotation.quotation_manage', compact(
            'quotations',
            'branchs'
        ));
    }

    public function quotation_register()
    {
        $quotations = Invoice::whereNotNull('quote_no')->latest()->get();
        $quotation_no = "Quote-" . count($quotations) + 1;
        $units = Unit::all();
        $warehouses = Warehouse::all();
        return view('quotation.quotation', compact('quotation_no', 'units', 'warehouses'));
    }

    public function invoice()
    {
        $invoices = Invoice::where('status', 'invoice')->latest()->get();
        $invoice_no = "Invoice-" . count($invoices) + 1;
        $units = Unit::all();
        $warehouses = Warehouse::all();
        return view('invoice.invoice', compact('invoice_no', 'units', 'warehouses'));
    }
    public function pos_register()
    {
        $invoices = Invoice::whereIn('status',  ['pos', 'suspend'])->latest()->get();
        $suspends = Invoice::where('status', 'suspend')->latest()->get();
        $invoice_no = "POS-" . count($invoices) + 1;
        $units = Unit::all();
        $warehouses = Warehouse::all();
        return view('invoice.pos', compact('invoice_no', 'units', 'warehouses', 'suspends'));
    }

    public function pos()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $invoices = Invoice::where('status', 'pos')->latest()->get();
            $branchs = Warehouse::all();
        } else {
            $invoices = Invoice::whereIn('branch', $warehousePermission)->where('status', 'pos')->latest()->get();
            $branchs = Warehouse::all();
        }

        return view('invoice.pos_manage', compact(
            'invoices',
            'branchs'
        ));
    }

    public function invoice_register(Request $request)
    {

        $invoice_number = Invoice::where('invoice_no', $request->invoice_no)->get();
        $count = count($invoice_number);

        // if ($count < 1) {
        //     $inv_number = $request->invoice_no;
        // } else {

        //     $inv_number = (int)str_replace("POS-", "", $request->invoice_no) + 1;
        //     $inv_number = "POS-" . $inv_number;
        // }

        if ($count < 1) {
            $inv_number = $request->invoice_no;
        } else {
            $inv_number = $request->invoice_no;
            do {
                $inv_number++;
                $count = Invoice::where('invoice_no', $inv_number)->count();
            } while ($count > 0);
        }


        $count = count($request->part_description);
        $invoice = new Invoice();

        $invoice->customer_id = $request->customer_id;
        $invoice->customer_name = $request->customer_name;
        $invoice->invoice_category = $request->quote_category;
        $invoice->sale_price_category = $request->sale_price_category;
        $invoice->phno  = $request->phno;
        $invoice->status  = $request->status;
        $invoice->branch  = $request->branch;
        // $invoice->sale_by  = $request->sale_by;
        $invoice->sale_by = auth()->user()->name;
        $invoice->location = $request->location;
        $invoice->type  = $request->type;
        $invoice->address  = $request->address;
        $invoice->invoice_no  = $inv_number;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->quote_date = $request->quote_date;
        $invoice->quote_no  = $request->quote_no;
        $invoice->overdue_date  = $request->overdue_date;
        $invoice->sub_total  = $request->sub_total;
        $invoice->total  = $request->total;

        $invoice->balance_due  = $request->balance_due;
        $invoice->discount_total  = $request->discount;
        $invoice->deposit  = $request->paid;
        $invoice->remain_balance  = $request->balance;
        $invoice->remark = $request->remark;

        $invoice->payment_method   = $request->payment_method;


        $invoice->save();
        $last_id = $invoice->id;
        for ($i = 0; $i < $count; $i++) {
            $result = new Sell();
            $result->invoiceid = $last_id;
            $result->customer_id = $request->customer_id;
            $result->description = $request->part_description[$i];
            $result->part_number = $request->part_number[$i];
            $result->product_qty = $request->product_qty[$i];
            $result->product_price = $request->product_price[$i];
            $result->retail_price = $request->retail_price[$i];
            $result->exp_date = $request->exp_date[$i];
            $result->unit = $request->item_unit[$i];
            $result->warehouse = $request->warehouse[$i];
            $result->save();
        }

        if ($request->status == 'quotation') {
            return redirect('/quotation')->with('success', 'Quotation Added Successful!');
        } elseif ($request->status == 'invoice') {
            foreach ($invoice->sells as $sell) {
                $item = Item::where('item_name', $sell->part_number)
                    ->where('warehouse_id', $sell->warehouse)
                    ->first();
                if ($item) {
                    $item->quantity -= $sell->product_qty;
                    $item->save();
                } else {
                    continue;
                }
            }
            return redirect('/invoice')->with('success', 'Invoice Added Successful!');
        } elseif ($invoice->status === 'pos') {

            foreach ($invoice->sells as $sell) {
                $item = Item::where('item_name', $sell->part_number)
                    ->where('warehouse_id', $sell->warehouse)
                    ->first();
                if ($item) {
                    $item->quantity -= $sell->product_qty;
                    $item->save();
                } else {
                    continue;
                }
            }
            return redirect()->route('invoice_detail', ['invoice' => $invoice->id])->with('success', 'POS Register Successfully');
        } else if ($invoice->status === 'suspend') {
            return redirect()->back()->with('success', 'Suspend Added Successful!');
        } else {
            foreach ($invoice->sells as $sell) {
                $item = Item::where('item_name', $sell->part_number)
                    ->where('warehouse_id', $sell->warehouse)
                    ->first();
                if ($item) {
                    $item->quantity -= $sell->product_qty;
                    $item->save();
                } else {
                    continue;
                }
            }
            return redirect('/invoice')->with('success', 'POS Added Successful!');
        }
    }

    public function customer_service_search(Request $request)
    {
        $data = Customer::select('name', 'phno')
            ->where('branch', $request->location)
            ->where('name', 'LIKE', '%' . $request->get('query') . '%')
            ->orWhere('phno', 'LIKE', '%' . $request->get('query') . '%')
            ->get(); // Retrieve all matching records
        info($request->location);
        return response()->json($data);
    }


    public function customer_service_search_fill(Request $request)
    {

        $product = Customer::where('name', $request->model)->orWhere('phno', $request->model)
            ->where('branch', $request->location)
            ->orderBy('created_at', 'desc')
            ->first();
        if (!$product) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
        $responseData = [
            'customer' => $product,
        ];

        return response()->json($responseData);
    }


    public function quotation_delete($id)
    {
        $quotation = Invoice::find($id);
        Sell::where('invoiceid', $id)->delete();
        $quotation->delete();
        return redirect('/quotation')->with('success', 'Quotation Deleted Successful!');
    }
    public function invoice_delete($id)
    {
        $invoice = Invoice::find($id);

        if ($invoice) {
            Sell::where('invoiceid', $id)->delete();

            $invoice->delete();

            if ($invoice->status == 'pos') {
                return back()->with('success', 'POS Deleted Successful!');
            } else {
                return redirect('/invoice')->with('success', 'Invoice Deleted Successful!');
            }
        } else {
            return redirect('/invoice')->with('error', 'Invoice Not Found!');
        }
    }

    public function quotation_edit($id)
    {
        $quotation = Invoice::find($id);
        $sell = Sell::where('invoiceid', $id)->get();
        $warehouses = Warehouse::all();

        return view('quotation.quotation_edit', compact('quotation', 'sell', 'warehouses'));
    }
    public function suspend_delete($id)
    {
        $suspend = Invoice::find($id);
        Sell::where('invoiceid', $id)->delete();
        $suspend->delete();
        return redirect()->back()->with('delete', 'Suspend Deleted Successful!');
    }
    public function invoice_edit($id)
    {

        $invoice = Invoice::find($id);
        $warehouses = Warehouse::all();


        if ($invoice->status === 'suspend') {
            $sells = Sell::where('invoiceid', $id)->get();

            return view('invoice.pos_edit', compact('invoice', 'sells', 'warehouses'));
        } else {
            $sell = Sell::where('invoiceid', $id)->get();
            return view('invoice.invoice_edit', compact('invoice', 'sell', 'warehouses'));
        }
    }




    public function invoice_update(Request $request, $id)
    {

        $invoice = Invoice::find($id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice not found');
        }

        $invoice->customer_id = $request->customer_id;
        $invoice->customer_name = $request->customer_name;
        $invoice->invoice_category = $request->quote_category;
        $invoice->sale_price_category = $request->sale_price_category;
        $invoice->phno  = $request->phno;
        $invoice->status  = $request->status;
        $invoice->type  = $request->type;
        $invoice->address  = $request->address;
        $invoice->invoice_no  = $request->invoice_no;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->quote_date = $request->quote_date;
        $invoice->quote_no  = $request->quote_no;
        $invoice->overdue_date  = $request->overdue_date;
        // $invoice->sale_by  = $request->sale_by;
        $invoice->sale_by = auth()->user()->name;
        $invoice->sub_total  = $request->sub_total;
        $invoice->total  = $request->total;
        $invoice->location = $request->location;
        $invoice->branch  = $request->branch;
        $invoice->balance_due  = $request->balance_due;
        $invoice->discount_total  = $request->discount;
        $invoice->deposit  = $request->paid;
        $invoice->remain_balance  = $request->balance;
        $invoice->remark = $request->remark;

        $invoice->payment_method   = $request->payment_method;
        $invoice->save();

        $sellsData = [];
        $now = Carbon::now();

        if ($request->input('part_description')) {
            foreach ($request->input('part_description') as $key => $partDescription) {
                $sellsData[] = [
                    'description' => $partDescription,
                    'part_number' => $request->input('part_number')[$key],
                    'product_qty' => $request->input('product_qty')[$key],
                    'product_price' => $request->input('product_price')[$key],
                    'retail_price' => $request->input('retail_price')[$key],
                    'unit' => $request->input('item_unit')[$key],
                    'exp_date' => $request->input('exp_date')[$key],
                    'warehouse' => $request->input('warehouse')[$key],
                    'invoiceid' => $id,
                    'created_at' => $now, // Add created_at field
                    'updated_at' => $now, // Add updated_at field
                ];
            }
        }

        if ($invoice->status === 'invoice') {

            $oldQuantities = [];
            foreach ($invoice->sells as $key => $sell) {
                $oldQuantities[$key] = $sell->product_qty;
            }


            foreach ($request->input('part_number') as $key => $partNumber) {
                $sell = $invoice->sells[$key] ?? null;

                if (!$sell) {
                    continue;
                }

                $item = Item::where('item_name', $partNumber)
                    ->where('warehouse_id', $sell->warehouse)
                    ->first();

                if (!$item) {
                    continue;
                }

                $currentQuantity = $item->quantity;
                $newQuantity = $currentQuantity + ($oldQuantities[$key] ?? 0) - $request->input('product_qty')[$key];
                $item->quantity = $newQuantity;
                $item->save();
            }
        }

        Sell::where('invoiceid', $id)->delete();
        Sell::insert($sellsData);

        if ($invoice->status === 'quotation') {
            return redirect('quotation')->with('success', 'Quotation Update Successful');
        } elseif ($invoice->status === 'invoice') {
            return redirect('invoice')->with('success', 'Invoice Update Successful');
        } elseif ($invoice->status === 'pos') {
            return redirect(url('invoice_detail', $invoice->id))->with('success', 'POS Update Successful');
        }
        return redirect()->back()->with('error', 'Failed to update invoice');
    }

    public function change_invoice($id)
    {

        $invoice = Invoice::where('status', 'invoice')->get();
        $invoices = Invoice::find($id);
        foreach ($invoices->sells as $sell) {
            $item = Item::where('item_name', $sell->part_number)
                ->where('warehouse_id', $sell->warehouse)
                ->first();
            if ($item) {
                $item->quantity -= $sell->product_qty;
                $item->save();
            } else {
                continue;
            }
        }
        $invoice_no =  "Invoice-" . count($invoice) + 1;
        $quotation = Invoice::find($id);
        $quotation->status = 'invoice';
        $quotation->invoice_no = $invoice_no;
        $quotation->invoice_date = Carbon::today()->format('Y-m-d');
        $quotation->update();

        return redirect('/invoice')->with('status', 'Change Invoice Successful!');
    }

    public function autocompletePartCodeInvoice(Request $request)
    {
        $query = $request->get('query');
        $location = $request->get('location');
        $items = Item::where('item_name', 'like', '%' . $query . '%')->where('warehouse_id', $location)
            ->pluck('item_name');
        return response()->json($items);
    }
    public function getPartDataInvoice(Request $request)
    {
        $result = Item::where('item_name', $request->item_name)->where('warehouse_id', $request->location)
            ->first();
        if (!$result) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json($result);
    }


    //Pos
    public function autocompletePartCode(Request $request)
    {
        $query = $request->get('query');
        $location = $request->get('location');
        $items = Item::where('item_name', 'like', '%' . $query . '%')->where('warehouse_id', $location)
            ->pluck('item_name');
        return response()->json($items);
    }

    public function autocompleteBarCode(Request $request)
    {
        $query = $request->get('query');

        $location = $request->get('location');

        $barcode = Item::where('barcode', 'like', '%' . $query . '%')->where('warehouse_id', $location)
            ->pluck('barcode');
        info($barcode);
        return response()->json($barcode);
    }

    public function getPartData(Request $request)
    {

        $item = Item::where('item_name', $request->itemname)->where('warehouse_id', $request->location)
            ->orderBy('created_at', 'desc')
            ->first();


        if (!$item) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $resdata = [
            'item' => $item,
        ];

        return response()->json($resdata);
    }

    public function getBarcodeData(Request $request)
    {

        $item = Item::where('barcode', $request->barcode)->where('warehouse_id', $request->location)
            ->orderBy('created_at', 'desc')
            ->first();


        if (!$item) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $resdata = [
            'item' => $item,
        ];

        return response()->json($resdata);
    }

    //End Pos

    // public function invoice_detail(Invoice $invoice)
    public function invoice_detail(Invoice $invoice)

    {

        if ($invoice->status === 'pos') {
            $profile = UserProfile::all();
            $sells = Sell::where('invoiceid', $invoice->id)->get();
            $invoices = Invoice::where('id', $invoice->id)->get();
            return view('invoice.pos_detail', [
                'invoice' => $invoice,
                'invoices' => $invoices,
                'sells' => $sells,
                'profile' => $profile
            ]);
        } else {
            $profile = UserProfile::all();
            $sells = Sell::where('invoiceid', $invoice->id)->get();
            return view('invoice.invoice_details', [
                'invoice' => $invoice,
                'sells' => $sells,
                'profile' => $profile

            ]);
        }
    }
    public function daily_sales()
    {


        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $daily_pos = Invoice::whereDate('created_at', Carbon::today())->where('status', 'pos')->get();
            $branchs = Warehouse::all();
        } else {
            $daily_pos = Invoice::whereDate('created_at', Carbon::today())->whereIn('branch', $warehousePermission)->where('status', 'pos')->get();
            $branchs = Warehouse::all();
        }

        return view('invoice.daily_sales', compact('daily_pos', 'branchs'));
    }
    public function item_search(Request $request)
    {
        $data = Item::select('item_name')
            ->where('item_name', 'LIKE', '%' . $request->get('query') . '%')->where('parent_id', 0)
            ->pluck('item_name'); // Retrieve all matching records
        return response()->json($data);
    }
    public function item_data_search_fill(Request $request)
    {
        $product = Item::where('item_name', $request->model)
            ->orderBy('created_at', 'desc')
            ->first();
        if (!$product) {
            return response()->json(['error' => 'Item not found'], 404);
        }
        $warehouse = Warehouse::find($product->warehouse_id);
        $responseData = [
            'item' => $product,
            'warehouse' => $warehouse,
        ];
        return response()->json($responseData);
    }

    public function quotation_detail($id)
    {
        $quotation = Invoice::find($id);
        $sells = Sell::where('invoiceid', $id)->get();
        $profile = UserProfile::all();
        return view('quotation.quotation_details', compact('quotation', 'sells', 'profile'));
    }

    public function sale_return()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $today = Carbon::today();
            $po = PurchaseOrder::where('quote_no', 'like', 'SR%')
                ->whereDate('created_at', $today)
                ->latest()
                ->get();

            $po_total = PurchaseOrder::where('quote_no', 'like', 'SR%')
                ->whereDate('created_at', $today)
                ->sum('total');
            $branchs = Warehouse::all();
        } else {
            $today = Carbon::today();
            $po = PurchaseOrder::where('quote_no', 'like', 'SR%')
                ->whereDate('created_at', $today)
                ->whereIn('branch', $warehousePermission)
                ->latest()
                ->get();

            $po_total = PurchaseOrder::where('quote_no', 'like', 'SR%')
                ->whereDate('created_at', $today)
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
            $branchs = Warehouse::all();
        }

        return view('invoice.sale_return', compact('po', 'po_total', 'branchs'));
    }

    public function sale_return_search(Request $request)
    {


        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

            $po = PurchaseOrder::where('quote_no', 'like', 'SR%')
                ->whereBetween('po_date', [$startDate, $endDate])
                ->latest()
                ->get();

            $po_total = PurchaseOrder::where('quote_no', 'like', 'SR%')
                ->whereBetween('po_date', [$startDate, $endDate])
                ->sum('total');
            $branchs = Warehouse::all();
        } else {

            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

            $po = PurchaseOrder::where('quote_no', 'like', 'SR%')
                ->whereBetween('po_date', [$startDate, $endDate])
                ->whereIn('branch', $warehousePermission)
                ->latest()
                ->get();

            $po_total = PurchaseOrder::where('quote_no', 'like', 'SR%')
                ->whereBetween('po_date', [$startDate, $endDate])
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
            $branchs = Warehouse::all();
        }


        return view('invoice.sale_return', compact('po', 'po_total', 'branchs'));
    }



    public function sale_return_register()
    {
        $po_number = PurchaseOrder::where('quote_no', 'like', 'SR%')->latest()->get();
        $units = Unit::all();
        $po_no = 'SR-' . (count($po_number) + 1);
        $warehouses = Warehouse::all();
        return view('invoice.sale_return_register', compact('po_no', 'units', 'warehouses'));
    }

    public function sale_return_edit($id)
    {
        $suppliers = Supplier::all();
        $purchase_orders = PurchaseOrder::find($id);
        $purchase_sells = PO_sells::where('invoiceid', $id)->get();
        $warehouses = Warehouse::all();
        return view('invoice.sale_return_edit', compact('purchase_orders', 'suppliers', 'purchase_sells', 'warehouses'));
    }

    public function sale_return_detail($id)
    {
        $purchase_order = PurchaseOrder::find($id);
        $purchase_sells = PO_sells::where('invoiceid', $id)->get();
        return view('invoice.sale_return_detail', compact('purchase_order', 'purchase_sells'));
    }

    public function sale_return_delete($id)
    {

        DB::beginTransaction();

        try {

            PurchaseOrder::findOrFail($id)->delete();


            PO_sells::where('invoiceid', $id)->delete();


            DB::commit();

            return redirect('/sale_return')->with('success', 'Sale Return Deleted Successfully!');
        } catch (\Exception $e) {

            DB::rollback();

            return redirect('/sale_return')->with('error', 'Failed to delete sale return.');
        }

        // return redirect('/quotation')->with('success', 'Quotation Deleted Successful!');
    }
}
