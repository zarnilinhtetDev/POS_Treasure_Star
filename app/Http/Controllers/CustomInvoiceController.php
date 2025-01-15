<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\CustomSell;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\CustomInvoice;
use App\Models\CustomInvoicePayment;

class CustomInvoiceController extends Controller
{
    //
    public function custom_invoice()
    {
        $custom_invoices = CustomInvoice::latest()->get();
        return view('custom_invoice.custom_invoice_manage', compact('custom_invoices'));
    }
    public function invoice_register()
    {
        $totalInvoices = CustomInvoice::where('status', 'invoice')->count();
        $invoice_no = "Custom Inv - " . ($totalInvoices + 1);
        $units = Unit::all();
        $warehouses = Warehouse::select('name', 'id')->get();
        return view('custom_invoice.custom_invoice_register', compact('invoice_no', 'units', 'warehouses'));
    }
    public function invoice_store(Request $request)
    {
        $invoice_number = CustomInvoice::where('invoice_no', $request->invoice_no)->get();
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
                $count = CustomInvoice::where('invoice_no', $inv_number)->count();
            } while ($count > 0);
        }


        $count = count($request->part_number);
        $invoice = new CustomInvoice();






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
        $invoice->discount_total  = $request->total_discount;
        $invoice->deposit  = $request->paid;
        $invoice->remain_balance  = $request->balance;
        $invoice->remark = $request->remark;
        $invoice->save();
        $last_id = $invoice->id;
        // dd($request->all());
        for ($i = 0; $i < $count; $i++) {
            $result = new CustomSell();
            $result->custom_invoiceid = $last_id;
            $result->customer_id = $request->customer_id;
            // $result->item_id = $request->item_id[$i]; //add new column
            $result->description = $request->part_description[$i];
            $result->part_number = $request->part_number[$i];
            $result->product_qty = $request->product_qty[$i];
            $result->discount = $request->discount[$i];
            $result->product_price = $request->product_price[$i];
            $result->retail_price = $request->retail_price[$i];
            $result->buy_price = $request->buy_price[$i]; //add new column
            $result->exp_date = $request->exp_date[$i];
            $result->unit = $request->item_unit[$i];
            $result->warehouse = $request->warehouse[$i];
            $result->save();
        }



        $count2 = count($request->payment_method);
        for ($i = 0; $i < $count2; $i++) {
            $payment_method = new CustomInvoicePayment();
            $payment_method->custom_invoice_id = $last_id;
            $payment_method->payment_method = $request->payment_method[$i];
            $payment_method->payment_amount = $request->payment_amount[$i];
            $payment_method->save();
        }



        return redirect('/custom_invoice')->with('success', 'Custom Invoice Added Successfull!');
    }
    public function invoice_delete($id)
    {
        $invoice = CustomInvoice::find($id);
        if ($invoice) {
            CustomSell::where('custom_invoiceid', $id)->delete();
            CustomInvoicePayment::where('custom_invoice_id', $invoice->id)->delete();
            $invoice->delete();
        }
        return redirect('/custom_invoice')->with('delete', 'Custom Invoice delete Successfull!');
    }
    public function invoice_edit($id)
    {
        $invoice = CustomInvoice::find($id);
        $warehouses = Warehouse::select('name', 'id')->get();;
        $payment_method = CustomInvoicePayment::where('custom_invoice_id', $id)->get();
        $units = Unit::all();


        $sell = CustomSell::where('custom_invoiceid', $id)->get();
        return view('custom_invoice.custom_invoice_edit', compact('invoice', 'sell', 'warehouses', 'payment_method', 'units'));
    }
    public function invoice_update($id, Request $request)
    {
        $invoice = CustomInvoice::find($id);


        if (!$invoice) {
            return redirect()->back()->with('error', 'Custom Invoice not found');
        }
        if ($request->paid)

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
        $invoice->discount_total  = $request->total_discount;
        $invoice->deposit  = $request->paid;
        $invoice->remain_balance  = $request->balance;
        $invoice->remark = $request->remark;

        // $invoice->payment_method   = $request->payment_method;
        $invoice->save();

        $sellsData = [];
        $now = Carbon::now();
        if ($request->input('part_number')) {
            foreach ($request->input('part_number') as $key => $partNumber) {
                $sellsData[] = [

                    'description' => $request->input('part_description')[$key],
                    'part_number' => $partNumber,
                    'product_qty' => $request->input('product_qty')[$key],
                    'product_price' => $request->input('product_price')[$key],
                    'discount' => $request->input('discount')[$key],
                    'retail_price' => $request->input('retail_price')[$key],
                    'unit' => $request->input('item_unit')[$key],
                    'exp_date' => $request->input('exp_date')[$key],
                    'warehouse' => $request->input('warehouse')[$key],
                    'custom_invoiceid' => $id,
                    'created_at' => $now, // Add created_at field
                    'updated_at' => $now, // Add updated_at field
                ];
            }
        }
        if ($invoice->status === 'invoice' || $invoice->status === 'pos' || $invoice->status === 'suspend') {
            CustomInvoicePayment::where('custom_invoice_id', $id)->delete();
            if ($request->input('payment_method')) {
                foreach ($request->input('payment_method') as $key => $paymentMethod) {
                    $payment_amount = $request->input('payment_amount')[$key] ?? 0;
                    $payment_method = new CustomInvoicePayment();
                    $payment_method->custom_invoice_id = $id;
                    $payment_method->payment_method = $paymentMethod;
                    $payment_method->payment_amount = $payment_amount;
                    $payment_method->created_at = $now;
                    $payment_method->updated_at = $now;
                    $payment_method->save();
                }
            }
        }


        CustomSell::where('custom_invoiceid', $id)->delete();
        CustomSell::insert($sellsData);


        return redirect('custom_invoice')->with('success', 'Invoice Update Successful');
    }
    public function invoice_detail(CustomInvoice $invoice)
    {
        $invoices =  CustomInvoice::where('id', $invoice->id)->get();
        $payment_methods = CustomInvoicePayment::where('custom_invoice_id', $invoice->id)->get();
        $profile = UserProfile::all();
        $sells = CustomSell::where('custom_invoiceid', $invoice->id)->get();
        return view('custom_invoice.custom_invoice_details', [
            'invoice' => $invoice,
            'invoices' => $invoices,
            'sells' => $sells,
            'profile' => $profile,
            'payment_methods' => $payment_methods,

        ]);
    }

    public function invoice_print(CustomInvoice $invoice)
    {
        $profile = UserProfile::all();
        $sells = CustomSell::where('custom_invoiceid', $invoice->id)->get();
        $invoices = CustomInvoice::where('id', $invoice->id)->get();
        $payment_methods = CustomInvoicePayment::where('custom_invoice_id', $invoice->id)->get();
        return view('custom_invoice.custom_invoice_print', [
            'invoice' => $invoice,
            'invoices' => $invoices,
            'sells' => $sells,
            'profile' => $profile,
            'payment_methods' => $payment_methods,
        ]);
    }
}
