<?php

namespace App\Http\Controllers;

use App\Models\InOut;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class InOutController extends Controller
{

    public function in(Request $request, $id)
    {
        $item = Item::findOrFail($id);


        // $part->retail_price_in_mmk = $request->retail_price_mmk;
        // $part->save();

        $result = new InOut();
        $result->items_id = $request->items_id;
        $result->quantity = $request->quantity;
        $result->date = $request->date;
        $result->total_quantity = $item->quantity + $request->quantity;
        // $result->mingalar_market = $request->mingalar_market;
        $result->retail_price = $request->retail_price;
        $result->wholesale_price = $request->wholesale_price;
        $result->buy_price = $request->buy_price;
        $result->remark = $request->remark;
        $result->in_out = 'in';
        $result->warehouse_id = $request->warehouse_id;
        $result->save();

        $item->quantity += $request->quantity;
        $item->save();

        return redirect()->back()->with('success', 'In Update Successfully');
    }


    public function out(Request $request, $id)
    {
        //


        $item = Item::findorfail($id);
        $result = new InOut();
        $result->items_id = $request->items_id;
        $result->quantity = $request->quantity;
        $result->date = $request->date;
        $result->total_quantity = $item->quantity - $request->quantity;
        $result->remark = $request->remark;
        $result->in_out = 'out';
        $result->warehouse_id = $request->warehouse_id;
        $result->save();

        if (isset($request->quantity)) {
            $item = Item::findorfail($id);
            $item->quantity = $item->quantity - $request->quantity;
            $item->save();
        }

        // $result->create($request->all());

        return redirect()->back()->with('out-success', 'Out Update Successfully');
    }

    public function display_print($id, $items_id)
    {

        $item = Item::findorfail($items_id);
        $inout = Inout::findorfail($id);

        return view('inout.print-display', compact('inout', 'item'));
    }

    public function invoice_record($id)
    {
        $items = Item::find($id);
        $invoices = Invoice::where('status', 'invoice')->get();
        return view('inout.invoice_record', compact('invoices', 'items'));
    }

    public function purchase_order_reord($id)
    {
        $items = Item::find($id);
        $invoices = PurchaseOrder::latest()->get();
        return view('inout.purchase_order_record', compact('invoices', 'items'));
    }
    public function pos_record($id)
    {
        $items = Item::find($id);
        $invoices = Invoice::where(
            'status',
            'pos'
        )->get();

        return view('inout.pos_record', compact('invoices', 'items'));
    }
}
