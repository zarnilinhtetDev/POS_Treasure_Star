<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PurchaseOrderPaymentMethod;
use App\Models\Unit;
use App\Models\PO_sells;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $po = PurchaseOrder::where('quote_no', 'like', 'PO%')->latest()->get();
            $branchs = Warehouse::all();
        } else {

            $po = PurchaseOrder::where('quote_no', 'like', 'PO%')->whereIn('branch', $warehousePermission)->latest()->get();
            $branchs = Warehouse::all();
        }

        return view('purchase_order.purchase_order_manage', compact('po', 'branchs'));
    }
    public function purchase_order_register()
    {
        $suppliers = Supplier::all();
        $po_number = PurchaseOrder::withTrashed()->where('quote_no', 'like', 'PO%')->latest()->get();
        $units = Unit::all();
        $po_no = 'PO-' . (count($po_number) + 1);
        $warehouses = Warehouse::all();
        return view('purchase_order.purchase_order', compact('po_no', 'suppliers', 'units', 'warehouses'));
    }

    //Customer Fill
    public function po_search(Request $request)
    {
        $query = $request->get('query');

        $data = Supplier::select('name', 'phno')
            ->where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('phno', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json($data);
    }

    public function po_search_fill(Request $request)
    {
        // $userBranchId = auth()->user()->branch_id;
        $supplier = Supplier::where('name', $request->model)
            ->orWhere('phno', $request->model)
            ->orderBy('created_at', 'desc')
            ->first();
        if (!$supplier) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $responseData = [
            'product' => $supplier,

        ];

        return response()->json($responseData);
    }

    public function purchase_order_store(Request $request)
    {

        $count = count($request->part_number);
        $invoice = new PurchaseOrder();
        // $invoice->supplier_id = $request->supplier_id;
        // $invoice->supplier_name = $request->supplier_name;
        // $invoice->invoice_category = $request->quote_category;
        // $invoice->phno = $request->phno;
        // $invoice->status = $request->status;
        // $invoice->type = $request->type;
        // $invoice->address = $request->address;
        // $invoice->invoice_no = $request->invoice_no;
        // $invoice->overdue_date = $request->overdue_date;
        // $invoice->po_date = $request->po_date;
        // $invoice->quote_no = $request->po_number;
        // $invoice->net_total = $request->total;
        // $invoice->total = $request->total;
        // $invoice->balance_due = $request->balance_due;
        // $invoice->discount_total = $request->discount_total;
        // $invoice->remain_balance = $request->remain_balance;
        // $invoice->deposit = $request->paid;
        // $invoice->remark = $request->remark;
        // $invoice->payment_method = $request->payment_method;
        $invoice->supplier_id = $request->supplier_id;
        $invoice->supplier_name = $request->supplier_name;
        $invoice->invoice_category = $request->quote_category;
        $invoice->phno  = $request->phno;
        $invoice->status  = $request->status;
        $invoice->type  = $request->type;
        $invoice->address  = $request->address;
        $invoice->invoice_no  = $request->invoice_no;
        $invoice->overdue_date = $request->overdue_date;
        $invoice->po_date = $request->po_date;
        $invoice->branch = $request->location;
        $invoice->quote_no  = $request->po_number;
        $invoice->sub_total  = $request->sub_total;
        $invoice->total  = $request->total;
        $invoice->balance_due  = $request->balance_due;
        $invoice->discount_total  = $request->total_discount;
        $invoice->deposit  = $request->paid;
        $invoice->remain_balance  = $request->balance;
        $invoice->remark = $request->remark;
        // $invoice->payment_method   = $request->payment_method;
        $invoice->save();
        $last_id = $invoice->id;
        for ($i = 0; $i < $count; $i++) {
            $result = new PO_sells();
            $result->invoiceid = $last_id;
            $result->supplier_id = $request->supplier_id;
            $result->part_number = $request->part_number[$i];
            $result->unit = $request->item_unit[$i];
            $result->product_qty = $request->product_qty[$i];
            $result->product_price = $request->product_price[$i];
            $result->warehouse = $request->warehouse[$i];
            $result->discount = $request->discount[$i];
            $result->status = $request->sell_status[$i];
            $result->save();
        }

        $count2 = count($request->payment_method);
        for ($i = 0; $i < $count2; $i++) {
            $payment_method = new PurchaseOrderPaymentMethod();
            $payment_method->po_id = $last_id;
            $payment_method->payment_method = $request->payment_method[$i];
            $payment_method->payment_amount = $request->payment_amount[$i];
            $payment_method->save();
        }

        foreach ($invoice->po_sells as $po_sell) {
            $item = Item::where('item_name', $po_sell->part_number)
                ->where('warehouse_id', $po_sell->warehouse)
                ->first();
            if ($item) {
                $item->quantity += $po_sell->product_qty;
                $item->save();
            } else {
                continue;
            }
        }

        if (Str::contains($invoice->quote_no, 'PO')) {
            return redirect('/purchase_order_manage')->with('success', 'Purchase Order Added Successful!');
        } else {
            return redirect('/sale_return')->with('success', 'Sale Return Added Successful!');
        }
    }
    public function edit($id)
    {
        $suppliers = Supplier::all();
        $purchase_orders = PurchaseOrder::find($id);
        $purchase_sells = PO_sells::where('invoiceid', $id)->get();
        $warehouses = Warehouse::all();
        $payment_method = PurchaseOrderPaymentMethod::where('po_id', $id)->get();

        return view('purchase_order.purchase_order_edit', compact('purchase_orders', 'suppliers', 'purchase_sells', 'warehouses', 'payment_method'));
    }

    public function purchase_order_update(Request $request, $id)
    {
        $count = count($request->part_number);
        $invoice = PurchaseOrder::find($id);
        $invoice->supplier_id = $request->supplier_id;
        $invoice->invoice_category = $request->quote_category;
        $invoice->phno  = $request->phno;
        $invoice->status  = $request->status;
        $invoice->type  = $request->type;
        $invoice->address  = $request->address;
        $invoice->invoice_no  = $request->invoice_no;
        $invoice->overdue_date = $request->overdue_date;
        $invoice->po_date = $request->po_date;
        $invoice->branch = $request->location;
        $invoice->quote_no  = $request->po_number;
        $invoice->sub_total  = $request->sub_total;
        $invoice->total  = $request->total;
        $invoice->balance_due  = $request->balance_due;
        $invoice->discount_total  = $request->total_discount;
        $invoice->deposit  = $request->paid;
        $invoice->remain_balance  = $request->balance;
        $invoice->remark = $request->remark;
        // $invoice->payment_method   = $request->payment_method;
        $invoice->save();
        $last_id = $invoice->id;


        $po = [];
        for ($i = 0; $i < $count; $i++) {
            $po[] = [
                'invoiceid' => $last_id,
                'supplier_id' => $request->supplier_id,
                'part_number' => $request->part_number[$i],
                'unit' => $request->item_unit[$i],
                'product_qty' => $request->product_qty[$i],
                'product_price' => $request->product_price[$i],
                'discount' => $request->discount[$i],
                'warehouse' => $request->warehouse[$i],
                'status' => $request->sell_status[$i] ?? '0',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        PurchaseOrderPaymentMethod::where('po_id', $id)->delete();
        if ($request->input('payment_method')) {
            foreach ($request->input('payment_method') as $key => $paymentMethod) {
                $payment_amount = $request->input('payment_amount')[$key] ?? 0;
                $payment_method = new PurchaseOrderPaymentMethod();
                $payment_method->po_id = $id;
                $payment_method->payment_method = $paymentMethod;
                $payment_method->payment_amount = $payment_amount;
                $payment_method->created_at = now();
                $payment_method->updated_at = now();
                $payment_method->save();
            }
        }



        $oldQuantities = [];
        foreach ($invoice->po_sells as $key => $po_sell) {
            $oldQuantities[$key] = $po_sell->product_qty;
        }
        foreach ($request->input('part_number') as $key => $partNumber) {
            $po_sell = $invoice->po_sells[$key] ?? null;

            $item = Item::where('item_name', $partNumber)
                ->where('warehouse_id', $request->warehouse)
                ->first();
            // info($item);
            if (!$item) {
                continue;
            }
            $currentQuantity = $item->quantity;
            $newQuantity = $currentQuantity - ($oldQuantities[$key] ?? 0) + $request->input('product_qty')[$key];
            $item->quantity = $newQuantity;
            info($key);
            info($request->product_qty[$key]);
            $item->save();
        }

        PO_sells::where('invoiceid', $id)->delete();
        PO_sells::insert($po);

        if (Str::contains($invoice->quote_no, 'PO')) {
            return redirect('/purchase_order_manage')->with('success', 'Purchase Order Updated Successful!');
        } else {
            return redirect('/sale_return')->with('success', 'Sale Return Updated Successful!');
        }
    }

    public function po_delete($id)
    {

        DB::beginTransaction();

        try {

            PurchaseOrder::findOrFail($id)->delete();
            PO_sells::where('invoiceid', $id)->delete();
            PurchaseOrderPaymentMethod::where('invoice_id', $id)->delete();
            DB::commit();
            return redirect('/purchase_order_manage')->with('success', 'Purchase Order Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/purchase_order_manage')->with('error', 'Failed to delete purchase order.');
        }

        // return redirect('/quotation')->with('success', 'Quotation Deleted Successful!');
    }

    public function details($id)
    {
        $purchase_order = PurchaseOrder::find($id);
        $purchase_sells = PO_sells::where('invoiceid', $id)->get();
        $profile = UserProfile::all();
        $payment_methods = PurchaseOrderPaymentMethod::where('po_id', $id)->get();
        return view('purchase_order.purchase_order_details', compact('purchase_order', 'purchase_sells', 'profile', 'payment_methods'));
    }
}
