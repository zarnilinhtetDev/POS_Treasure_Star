<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\MakePayment;
use App\Models\InvoicePaymentMethod;
use App\Models\UserProfile;
use Carbon\Carbon;

class MakePaymentController extends Controller
{
    public function index(Invoice $invoice){

        $payments = MakePayment::where('invoice_id', $invoice->id)->get();
        return view('invoice.make_payment',compact('invoice','payments'));
    }


    public function payment_store(Request $request, Invoice $invoice)
    {

        if($request->remain_balance == '0'){
            return redirect()->back()->with('error','Remaining Balance is 0 , Nothing To Pay!');
        }

        $make_payments = new MakePayment();

        // $invoice = Invoice::where('status', 'invoice')->where('id', $id)->first();
        $make_payments->payment_method = $request->payment_method;
        $make_payments->amount = $request->amount;
        $make_payments->note = $request->note;
        $make_payments->invoice_no = $request->invoice_no;
        $make_payments->invoice_id = $invoice->id;
        $make_payments->payment_date = $request->payment_date;
        $make_payments->save();

        $invoice->deposit = $request->amount + $invoice->deposit;
        $invoice->remain_balance = $invoice->remain_balance - $request->amount;
        $invoice->update();

        $payment_method = new InvoicePaymentMethod();
        $payment_method->invoice_id = $invoice->id;
        $payment_method->payment_method = $request->payment_method;
        $payment_method->payment_amount = $request->amount;
        $payment_method->created_at = Carbon::now();
        $payment_method->updated_at = Carbon::now();
        $payment_method->save();

        return redirect(url('invoice'))->with('success', 'Payment Added Successfull!');
    }

    public function voucherView(MakePayment $make_payment)
    {
        $invoice = Invoice::where('id', $make_payment->invoice_id)->first();
        $profile = UserProfile::all();
        
        return view('invoice.invoice_voucher', [
            'invoice' => $invoice,
            'make_payment' => $make_payment,
            'profile' => $profile
        ]);
    }
}
