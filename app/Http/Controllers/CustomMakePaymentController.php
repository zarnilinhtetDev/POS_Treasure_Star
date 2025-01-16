<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\MakePayment;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\CustomInvoice;
use App\Models\CustomInvoicePayment;
use App\Models\CustomMakePayment;
use App\Models\InvoicePaymentMethod;

class CustomMakePaymentController extends Controller
{
    public function index(CustomInvoice $invoice)
    {

        $payments = CustomMakePayment::where('custom_invoice_id', $invoice->id)->get();
        return view('custom_invoice.custom_make_payment', compact('invoice', 'payments'));
    }


    public function payment_store(Request $request, CustomInvoice $invoice)
    {

        if ($request->remain_balance == '0') {
            return redirect()->back()->with('error', 'Remaining Balance is 0 , Nothing To Pay!');
        }

        $make_payments = new CustomMakePayment();

        // $invoice = Invoice::where('status', 'invoice')->where('id', $id)->first();
        $make_payments->payment_method = $request->payment_method;
        $make_payments->amount = $request->amount;
        $make_payments->note = $request->note;
        $make_payments->custom_invoice_no = $request->invoice_no;
        $make_payments->custom_invoice_id = $invoice->id;
        $make_payments->payment_date = $request->payment_date;
        $make_payments->save();

        $invoice->deposit = $request->amount + $invoice->deposit;
        $invoice->remain_balance = $invoice->remain_balance - $request->amount;
        $invoice->update();

        $payment_method = new CustomInvoicePayment();
        $payment_method->custom_invoice_id = $invoice->id;
        $payment_method->payment_method = $request->payment_method;
        $payment_method->payment_amount = $request->amount;
        $payment_method->created_at = Carbon::now();
        $payment_method->updated_at = Carbon::now();
        $payment_method->save();

        return redirect(url('custom_invoice'))->with('success', 'Payment Added Successfull!');
    }

    public function voucherView(CustomMakePayment $make_payment)
    {
        $invoice = CustomInvoice::where('id', $make_payment->custom_invoice_id)->first();
        $profile = UserProfile::all();

        return view('invoice.invoice_voucher', [
            'invoice' => $invoice,
            'make_payment' => $make_payment,
            'profile' => $profile
        ]);
    }
}
