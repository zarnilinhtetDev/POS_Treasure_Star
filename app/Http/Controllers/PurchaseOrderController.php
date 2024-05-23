<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Unit;
use App\Models\PO_sells;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $po = PurchaseOrder::latest()->get();
        return view('purchase_order.purchase_order_manage', compact('po'));
    }
    public function purchase_order_register()
    {
        $suppliers = Supplier::all();
        $po_number = PurchaseOrder::whereNotNull('quote_no')->latest()->get();
        $units = Unit::all();
        $po_no = 'PO-' . count($po_number) + 1;
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

        $count = count($request->part_description);
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
        $invoice->quote_no  = $request->po_number;
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
            $result = new PO_sells();
            $result->invoiceid = $last_id;
            $result->supplier_id = $request->supplier_id;
            $result->description = $request->part_description[$i];
            $result->part_number = $request->part_number[$i];
            $result->unit = $request->item_unit[$i];
            $result->exp_date = $request->exp_date[$i];
            $result->product_qty = $request->product_qty[$i];
            $result->product_price = $request->product_price[$i];
            $result->warehouse = $request->warehouse[$i];
            $result->save();
        }



        foreach ($invoice->po_sells as $po_sell) {
            $item = Item::where('item_name', $po_sell->part_number)->first();
            if ($item) {
                $item->quantity += $po_sell->product_qty;
                $item->save();
            } else {
                continue;
            }
        }


        return redirect('/purchase_order_manage')->with('success', 'Purchase Order Added Successful!');
    }

    public function edit($id)
    {
        $suppliers = Supplier::all();
        $purchase_orders = PurchaseOrder::find($id);
        $purchase_sells = PO_sells::where('invoiceid', $id)->get();
        $warehouses = Warehouse::all();
        return view('purchase_order.purchase_order_edit', compact('purchase_orders', 'suppliers', 'purchase_sells', 'warehouses'));
    }

    public function purchase_order_update(Request $request, $id)
    {
        $count = count($request->part_description);
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
        $invoice->quote_no  = $request->po_number;
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


        $oldQuantities = [];
        foreach ($invoice->po_sells as $key => $po_sell) {
            $oldQuantities[$key] = $po_sell->product_qty;
        }
        foreach ($request->input('part_number') as $key => $partNumber) {
            $item = Item::where('item_name', $partNumber)->first();
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
        for ($i = 0; $i < $count; $i++) {
            $result = new PO_sells();
            $result->invoiceid = $last_id;
            $result->supplier_id = $request->supplier_id;
            $result->description = $request->part_description[$i];
            $result->part_number = $request->part_number[$i];
            $result->unit = $request->item_unit[$i];
            $result->product_qty = $request->product_qty[$i];
            $result->exp_date = $request->exp_date[$i];
            $result->product_price = $request->product_price[$i];
            $result->warehouse = $request->warehouse[$i];



            $result->save();
        }




        return redirect('/purchase_order_manage')->with('success', 'Purchase Order Updated Successful!');
    }

    public function po_delete($id)
    {

        DB::beginTransaction();

        try {

            PurchaseOrder::findOrFail($id)->delete();


            PO_sells::where('invoiceid', $id)->delete();


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
        return view('purchase_order.purchase_order_details', compact('purchase_order', 'purchase_sells'));
    }
}