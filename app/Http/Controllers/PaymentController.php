<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\Transaction;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function payment($id)
    {

        $accounts = Account::all();
        $transaction = Transaction::find($id);
        $transactions = Transaction::with('account')->latest()->get();


        $invoices = Invoice::where('transaction_id', $transaction->id)
            ->where('status', 'invoice')
            ->where('balance_due', 'Invoice')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();

        $po_returns = Invoice::where('transaction_id', $transaction->id)
            ->where('status', 'invoice')
            ->where('balance_due', 'Po Return')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();

        $purchase_orders = PurchaseOrder::where('transaction_id', $transaction->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('balance_due', 'PO')
            ->get();

        $point_of_sales = Invoice::where('transaction_id', $transaction->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', 'pos')
            ->get();

        $sale_return_invoices = PurchaseOrder::where('transaction_id', $transaction->id)
            ->whereMonth('po_date', Carbon::now()->month)
            ->whereYear('po_date', Carbon::now()->year)
            ->where('balance_due', 'Sale Return Invoice')
            ->get();

        $sale_return_pos = PurchaseOrder::where('transaction_id', $transaction->id)
            ->whereMonth('po_date', Carbon::now()->month)
            ->whereYear('po_date', operator: Carbon::now()->year)
            ->where('balance_due', 'Sale Return')
            ->get();

        $sumByIn = Payment::where('payment_status', 'IN')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');
        $sumByOut = Payment::where('payment_status', 'OUT')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');
        $diff = collect($sumByIn)->map(function ($totalIn, $transactionId) use ($sumByOut) {
            $totalOut = $sumByOut->get($transactionId, 0);

            return $totalIn - $totalOut;
        });
        $warehouses = Warehouse::all();
        $payment = Payment::where('transaction_id', $id)->get();
        return view('finance.payment.makePayment', compact('accounts', 'transaction', 'payment', 'invoices', 'warehouses', 'transactions', 'diff', 'purchase_orders', 'point_of_sales', 'po_returns', 'sale_return_invoices', 'sale_return_pos'));
    }


    public function payment_register(Request $request)
    {
        $data = new Payment();
        $data->fill($request->all());
        $data->save();
        return redirect()->back()->with('success', 'Payment created successfully');
    }
    // Direct finance Make payment Page
    public function makePaymentEdit($id)
    {
        $show = Payment::find($id);

        return view('finance.payment.makePaymentEdit', compact('show'));
    }
    public function paymentUpdate(Request $request, $id)
    {
        $update = Payment::find($id);
        $update->payment_status = $request->input('payment_status');
        $update->amount = $request->input('amount');
        $update->note = $request->input('note');
        $update->save();
        return redirect(url('payment', $update->transaction_id))->with('updateStatus', 'Payment Update Successful');
    }
    public function paymentDelete($id)
    {
        Payment::find($id)->delete();
        return redirect()->back()->with('success', 'Payment Deleted Successful!');
    }

    public function account_invoice_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transaction = Transaction::find($id);
        $invoices = Invoice::where('transaction_id', $transaction->id)
            ->where('status', 'invoice')
            ->where('balance_due', 'Invoice')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('invoice_date', [$startDate, $endDate]);
            })
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'invoices' => $invoices,
                'warehouses' => $warehouses
            ]);
        }

        return view('finance.payment.makePayment', compact('transaction', 'invoices', 'warehouses'));
    }

    public function account_po_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $transaction = Transaction::find($id);

        $purchase_orders = PurchaseOrder::where('transaction_id', $transaction->id)
            ->where('balance_due', 'PO')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('po_date', [$startDate, $endDate]);
            })
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'purchase_orders' => $purchase_orders,
                'warehouses' => $warehouses,
            ]);
        }

        return view('finance.payment.makePayment', compact('transaction', 'purchase_orders', 'warehouses', 'startDate', 'endDate'));
    }



    public function account_pos_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $transaction = Transaction::find($id);

        $point_of_sales = Invoice::where('transaction_id', $transaction->id)
            ->where('status', 'pos')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('invoice_date', [$startDate, $endDate]);
            })
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'point_of_sales' => $point_of_sales,
                'warehouses' => $warehouses,
            ]);
        }
        return view('finance.payment.makePayment', compact('transaction', 'point_of_sales', 'warehouses', 'startDate', 'endDate'));
    }



    public function purchase_return_invoice_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transaction = Transaction::find($id);

        $po_returns = Invoice::where('transaction_id', $transaction->id)
            ->where('status', 'invoice')
            ->where('balance_due', 'Po Return')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('invoice_date', [$startDate, $endDate]);
            })
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'po_returns' => $po_returns,
                'warehouses' => $warehouses,
            ]);
        }

        return view('finance.payment.makePayment', compact('transaction', 'po_returns', 'warehouses', 'startDate', 'endDate'));
    }


    public function sale_return_invoice_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transaction = Transaction::find($id);
        $sale_return_invoices = PurchaseOrder::where('transaction_id', $transaction->id)
            ->where('balance_due', 'Sale Return Invoice')
            ->whereBetween('po_date', [$startDate, $endDate])
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'sale_return_invoices' => $sale_return_invoices,
                'warehouses' => $warehouses
            ]);
        }

        return view('finance.payment.makePayment', compact('transaction', 'sale_return_invoices', 'warehouses', 'startDate', 'endDate'));
    }


    public function sale_return_pos_search(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $transaction = Transaction::find($id);

        $sale_return_pos = PurchaseOrder::where('transaction_id', $transaction->id)
            ->whereBetween('po_date', [$startDate, $endDate])
            ->where('balance_due', 'Sale Return')
            ->get();

        $warehouses = Warehouse::all();

        if ($request->ajax()) {
            return response()->json([
                'sale_return_pos' => $sale_return_pos,
                'warehouses' => $warehouses
            ]);
        }

        return view('finance.payment.makePayment', compact('transaction', 'sale_return_pos', 'warehouses', 'startDate', 'endDate'));
    }
}
