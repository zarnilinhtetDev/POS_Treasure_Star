<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Payment;
use App\Models\PurchaseOrderPaymentMethod;
use App\Models\Unit;
use App\Models\PO_sells;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Setting;
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

        if ($request->balance_due == 'PO') {
            if ($request->paid > 0 && $request->balance > 0) {
                $setting = Setting::where('category', 'Purchase Order')->where('location', $request->location)->first();
                $payable_setting = Setting::where('category', 'Payable (Purchase Order)')->where('location', $request->location)->first();

                if ($setting && $payable_setting) {
                    $invoice->transaction_id = $setting->transaction_id ?? null;
                    $invoice->payable_id = $payable_setting->transaction_id ?? null;
                    $transactions = Payment::all();

                    foreach ($transactions as $transaction) {

                        if ($transaction->id == $setting->transaction_id) {
                            $tran = Payment::where('transaction_id', $transaction->id)->get();
                            $po = $tran->skip(2)->first();

                            if ($po) {
                                $po->amount += $request->paid;
                                $po->save();
                            } else {
                            }
                        }
                        if ($transaction->id == $payable_setting->transaction_id) {
                            $tran = Payment::where('transaction_id', $transaction->id)->get();
                            $po = $tran->skip(7)->first();

                            if ($po) {
                                $po->amount += $request->balance;
                                $po->save();
                            } else {
                            }
                        }
                    }
                }
            } else {
                $setting = Setting::where('category', 'Purchase Order')->where('location', $request->location)->first();

                if ($setting) {
                    $invoice->transaction_id = $setting->transaction_id ?? null;
                    $transactions = Payment::all();

                    foreach ($transactions as $transaction) {

                        if ($transaction->id == $setting->transaction_id) {
                            $tran = Payment::where('transaction_id', $transaction->id)->get();
                            $po = $tran->skip(2)->first();

                            if ($po) {
                                $po->amount += $request->paid;
                                $po->save();
                            } else {
                            }
                        }
                    }
                }
            }
        } else if ($request->balance_due == 'Sale Return Invoice') {
            $setting = Setting::where('category', 'Sale Return (Invoice)')->where('location', $request->location)->first();

            if ($setting) {
                $invoice->transaction_id = $setting->transaction_id ?? null;
                $transactions = Payment::all();

                foreach ($transactions as $transaction) {

                    if ($transaction->id == $setting->transaction_id) {
                        $tran = Payment::where('transaction_id', $transaction->id)->get();
                        $po = $tran->skip(4)->first();

                        if ($po) {
                            $po->amount += $request->paid;
                            $po->save();
                        } else {
                        }
                    }
                }
            } else {
            }
        } else if ($request->balance_due == 'Sale Return') {
            $setting = Setting::where('category', 'Sale Return (POS)')->where('location', $request->location)->first();

            if ($setting) {
                $invoice->transaction_id = $setting->transaction_id ?? null;
                $transactions = Payment::all();

                foreach ($transactions as $transaction) {

                    if ($transaction->id == $setting->transaction_id) {
                        $tran = Payment::where('transaction_id', $transaction->id)->get();
                        $po = $tran->skip(5)->first();

                        if ($po) {
                            $po->amount += $request->paid;
                            $po->save();
                        } else {
                        }
                    }
                }
            } else {
            }
        }


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
            $result->description = $request->part_description[$i];
            $result->part_number = $request->part_number[$i];
            $result->unit = $request->item_unit[$i];
            $result->exp_date = $request->exp_date[$i];
            $result->product_qty = $request->product_qty[$i];
            $result->product_price = $request->product_price[$i];
            $result->warehouse = $request->warehouse[$i];
            $result->discount = $request->discount[$i];
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

        if ($request->balance_due == 'PO') {
            if ($invoice) {
                $oldtotal = $invoice->deposit;
                $currenttotal = $request->paid;
                if ($invoice->payable_id) {
                    $payment = Payment::where('transaction_id', $invoice->payable_id)->skip(7)->first();

                    if ($payment) {
                        $payment->amount = $payment->amount - ($currenttotal - $oldtotal);
                        $payment->save();
                        if ($payment->amount == 0) {
                            $invoice->payable_id = null;
                        }
                    } else {
                    }
                }
                if ($invoice->transaction_id) {
                    $payment = Payment::where('transaction_id', $invoice->transaction_id)->skip(2)->first();

                    if ($payment) {
                        $payment->amount = $payment->amount + ($currenttotal - $oldtotal);
                        $payment->save();
                    } else {
                    }
                } else {
                }
            } else {
            }
        } else if ($request->balance_due == 'Sale Return Invoice') {
            if ($invoice) {
                $oldtotal = $invoice->deposit;
                $currenttotal = $request->paid;

                if ($invoice->transaction_id) {
                    $payment = Payment::where('transaction_id', $invoice->transaction_id)->skip(4)->first();

                    if ($payment) {
                        $payment->amount = $payment->amount + ($currenttotal - $oldtotal);
                        $payment->save();
                    } else {
                    }
                } else {
                }
            } else {
            }
        } else if ($request->balance_due == 'Sale Return') {
            if ($invoice) {
                $oldtotal = $invoice->deposit;
                $currenttotal = $request->paid;

                if ($invoice->transaction_id) {
                    $payment = Payment::where('transaction_id', $invoice->transaction_id)->skip(5)->first();

                    if ($payment) {
                        $payment->amount = $payment->amount + ($currenttotal - $oldtotal);
                        $payment->save();
                    } else {
                    }
                } else {
                }
            } else {
            }
        }


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
                'description' => $request->part_description[$i],
                'part_number' => $request->part_number[$i],
                'unit' => $request->item_unit[$i],
                'product_qty' => $request->product_qty[$i],
                'exp_date' => $request->exp_date[$i],
                'product_price' => $request->product_price[$i],
                'discount' => $request->discount[$i],
                'warehouse' => $request->warehouse[$i],
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
                ->where('warehouse_id', $request->warehouse[$key])
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

            $invoice = PurchaseOrder::findOrFail($id);

            if ($invoice) {

                foreach ($invoice->po_sells as $sell) {
                    $item = Item::where('item_name', $sell->part_number)->where('warehouse_id', $invoice->branch)->first();
                    if ($item) {
                        $item->quantity -= $sell->product_qty;
                        $item->save();
                    }
                }
            }
            if ($invoice->balance_due == 'PO') {
                if ($invoice) {
                    $oldtotal = $invoice->deposit;

                    // dd($invoice->payable_id);
                    if ($invoice->payable_id) {
                        $payment = Payment::where('transaction_id', $invoice->payable_id)->skip(7)->first();
                        // dd($payment);
                        if ($payment) {
                            $payment->amount = $payment->amount - $invoice->remain_balance;
                            $payment->save();
                        } else {
                        }
                    }
                    if ($invoice->transaction_id) {
                        $payment = Payment::where('transaction_id', $invoice->transaction_id)->skip(2)->first();

                        if ($payment) {
                            $payment->amount = $payment->amount - $oldtotal;
                            $payment->save();
                        } else {
                        }
                    } else {
                    }
                } else {
                }
            } else if ($invoice->balance_due == 'Sale Return Invoice') {
                if ($invoice) {
                    $oldtotal = $invoice->deposit;

                    if ($invoice->transaction_id) {
                        $payment = Payment::where('transaction_id', $invoice->transaction_id)->skip(4)->first();

                        if ($payment) {
                            $payment->amount = $payment->amount - $oldtotal;
                            $payment->save();
                        } else {
                        }
                    } else {
                    }
                } else {
                }
            }
            $invoice->delete();
            PO_sells::where('invoiceid', $id)->delete();
            PurchaseOrderPaymentMethod::where('po_id', $id)->delete();
            DB::commit();
            return redirect('/purchase_order_manage')->with('success', 'Purchase Order Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/purchase_order_manage')->with('error', 'Failed to delete purchase order.' . $e);
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
