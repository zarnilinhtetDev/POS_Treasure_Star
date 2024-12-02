<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Warehouse;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class TransactionController extends Controller
{

    public function transactionManagement($branch = null)
    {

        $account = Account::all();
        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            if ($branch) {
                $transaction = Transaction::with('account')->where('location', $branch)->get();
            } else {
                $transaction = Transaction::with('account')->latest()->get();
            }
        } else {
            $transaction = Transaction::with('account')->where('location', auth()->user()->level)->get();
        }
        // $transaction = Transaction::with('account')->latest()->get();
        $sumByIn = Payment::where('payment_status', 'IN')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');
        // dd($sumByIn);
        $sumByOut = Payment::where('payment_status', 'OUT')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');
        $invoices = Invoice::where('status', 'invoice')
            ->where('balance_due', 'Invoice')
            ->whereNull('receviable_id')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(total) as total')
            ->pluck('total', 'transaction_id');


        $deposit_invoices = Invoice::where('status', 'invoice')
            ->where('balance_due', 'Invoice')
            ->whereNotNull('receviable_id')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(deposit) as total')
            ->pluck('total', 'transaction_id');

        $invoices_remain = Invoice::where('status', 'invoice')
            ->where('balance_due', 'Invoice')
            ->whereNotNull('transaction_id')
            ->groupBy('receviable_id')
            ->selectRaw('receviable_id, SUM(remain_balance) as total')
            ->pluck('total', 'receviable_id');


        $po_returns = Invoice::where('status', 'invoice')
            ->where('balance_due', 'Po Return')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(total) as total')
            ->pluck('total', 'transaction_id');


        $purchase_orders = PurchaseOrder::whereNull('payable_id')
            ->where('balance_due', 'PO')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(total) as total')
            ->pluck('total', 'transaction_id');

        $purchase_orders_remain = PurchaseOrder::whereNotNull('transaction_id')
            ->where('balance_due', 'PO')
            ->groupBy('payable_id')
            ->selectRaw('payable_id, SUM(remain_balance) as total')
            ->pluck('total', 'payable_id');

        $purchase_orders_deposit = PurchaseOrder::whereNotNull('payable_id')
            ->where('balance_due', 'PO')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(deposit) as total')
            ->pluck('total', 'transaction_id');


        $point_of_sales = Invoice::where('status', 'pos')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(deposit) as total')
            ->pluck('total', 'transaction_id');


        $sale_return_invoices = PurchaseOrder::where('balance_due', 'Sale Return Invoice')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(deposit) as total')
            ->pluck('total', 'transaction_id');


        $sale_return_pos = PurchaseOrder::where('balance_due', 'Sale Return')->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(total) as total')
            ->pluck('total', 'transaction_id');


        $expense = Expense::groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');



        //startoldcode
        // $diff = collect($sumByIn)->map(function ($totalIn, $transactionId) use ($sumByOut) {
        //     $totalOut = $sumByOut->get($transactionId, 0);

        //     return $totalIn - $totalOut;
        // });
        //endoldcode


        // dd($diff);
        $allTransactionIds = collect([
            $sumByIn->keys(),
            $sumByOut->keys(),
            $invoices->keys(),
            $deposit_invoices->keys(),
            $invoices_remain->keys(),
            $po_returns->keys(),
            $purchase_orders->keys(),
            $purchase_orders_remain->keys(),
            $purchase_orders_deposit->keys(),
            $point_of_sales->keys(),
            $sale_return_invoices->keys(),
            $sale_return_pos->keys(),
            $expense->keys()
        ])->flatten()->unique();

        // Initialize missing keys for all collections
        $collections = [
            $sumByIn,
            $sumByOut,
            $invoices,
            $deposit_invoices,
            $invoices_remain,
            $po_returns,
            $purchase_orders,
            $purchase_orders_remain,
            $purchase_orders_deposit,
            $point_of_sales,
            $sale_return_invoices,
            $sale_return_pos,
            $expense
        ];

        $collections = collect($collections)->map(function ($collection) use ($allTransactionIds) {
            return $allTransactionIds->mapWithKeys(function ($transactionId) use ($collection) {
                return [$transactionId => $collection->get($transactionId, 0)];
            });
        });

        // Destructure the collections for easier use
        [
            $sumByIn,
            $sumByOut,
            $invoices,
            $deposit_invoices,
            $invoices_remain,
            $po_returns,
            $purchase_orders,
            $purchase_orders_remain,
            $purchase_orders_deposit,
            $point_of_sales,
            $sale_return_invoices,
            $sale_return_pos,
            $expense
        ] = $collections;

        // Calculate diff
        $diff = $allTransactionIds->mapWithKeys(function ($transactionId) use (
            $sumByIn,
            $sumByOut,
            $invoices,
            $deposit_invoices,
            $invoices_remain,
            $po_returns,
            $purchase_orders,
            $purchase_orders_remain,
            $purchase_orders_deposit,
            $point_of_sales,
            $sale_return_invoices,
            $sale_return_pos,
            $expense
        ) {
            $totalIn = $sumByIn->get($transactionId, 0);
            $totalOut = $sumByOut->get($transactionId, 0);
            $invoiceTotal = $invoices->get($transactionId, 0);
            $depositInvoiceTotal = $deposit_invoices->get($transactionId, 0);
            $remainingBalance = $invoices_remain->get($transactionId, 0);
            $poReturnTotal = $po_returns->get($transactionId, 0);
            $purchaseOrderTotal = $purchase_orders->get($transactionId, 0);
            $purchaseOrderRemain = $purchase_orders_remain->get($transactionId, 0);
            $purchaseOrderDeposit = $purchase_orders_deposit->get($transactionId, 0);
            $posTotal = $point_of_sales->get($transactionId, 0);
            $saleReturnInvoiceTotal = $sale_return_invoices->get($transactionId, 0);
            $saleReturnPosTotal = $sale_return_pos->get($transactionId, 0);
            $expenseTotal = $expense->get($transactionId, 0);

            // Calculate final diff
            $finalDiff = $totalIn - $totalOut +
                $invoiceTotal + $depositInvoiceTotal + $remainingBalance +
                $poReturnTotal - $purchaseOrderTotal - $purchaseOrderRemain -
                $purchaseOrderDeposit + $posTotal - $saleReturnInvoiceTotal -
                $saleReturnPosTotal - $expenseTotal;

            return [$transactionId => $finalDiff];
        });
        // dd($diff);
        $branches = Warehouse::all();

        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Accounts';
        return view('finance.transaction.transactionManagement', compact('account', 'transaction', 'diff', 'branches', 'branch_drop', 'currentBranchName'));
    }


    //transaction register
    public function transaction_register(Request $request)
    {

        $trasaction = new Transaction();
        $trasaction->transaction_code = $request->transaction_code;
        $trasaction->transaction_name = $request->transaction_name;
        $trasaction->description = $request->description;
        $trasaction->location = $request->location;
        $account = Account::find($request->input('account_id'));
        $account->transaction()->save($trasaction);

        // $payment = new Payment();
        // $payment->account_id = $account->id;
        // $payment->transaction_id = $trasaction->id;
        // $payment->payment_status = 'IN';
        // $payment->amount = 0;
        // $payment->save();

        // $payment = new Payment();
        // $payment->account_id = $account->id;
        // $payment->transaction_id = $trasaction->id;
        // $payment->payment_status = 'IN';
        // $payment->amount = 0;
        // $payment->save();

        // $payment = new Payment();
        // $payment->account_id = $account->id;
        // $payment->transaction_id = $trasaction->id;
        // $payment->payment_status = 'OUT';
        // $payment->amount = 0;
        // $payment->save();

        // $payment = new Payment();
        // $payment->account_id = $account->id;
        // $payment->transaction_id = $trasaction->id;
        // $payment->payment_status = 'IN';
        // $payment->amount = 0;
        // $payment->save();

        // $payment = new Payment();
        // $payment->account_id = $account->id;
        // $payment->transaction_id = $trasaction->id;
        // $payment->payment_status = 'OUT';
        // $payment->amount = 0;
        // $payment->save();

        // $payment = new Payment();
        // $payment->account_id = $account->id;
        // $payment->transaction_id = $trasaction->id;
        // $payment->payment_status = 'OUT';
        // $payment->amount = 0;
        // $payment->save();

        // $payment = new Payment();
        // $payment->account_id = $account->id;
        // $payment->transaction_id = $trasaction->id;
        // $payment->payment_status = 'IN';
        // $payment->amount = 0;
        // $payment->save();

        // $payment = new Payment();
        // $payment->account_id = $account->id;
        // $payment->transaction_id = $trasaction->id;
        // $payment->payment_status = 'OUT';
        // $payment->amount = 0;
        // $payment->save();
        // $payment = new Payment();
        // $payment->account_id = $account->id;
        // $payment->transaction_id = $trasaction->id;
        // $payment->payment_status = 'OUT';
        // $payment->amount = 0;
        // $payment->save();


        return redirect()->back()->with('success', 'Transaction Created Successful!');
    }

    public function transactionManagementEdit($id)
    {
        $transaction = Transaction::with('account')->find($id);
        $accounts = Account::latest()->get();
        $branches = Warehouse::all();
        return view('finance.transaction.transactionManagementEdit', compact('transaction', 'accounts', 'branches'));
    }
    public function transactionUpdate(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if ($transaction->account_id != $request->input('account_id')) {
            $transaction->account()->associate(Account::find($request->input('account_id')));
        }
        $transaction->update([
            'transaction_code' => $request->input('transaction_code'),
            'transaction_name' => $request->input('transaction_name'),
            'description' => $request->input('description'),
        ]);
        return redirect('/transactionManagement')->with('updateStatus', 'Transaction Update is Successfull');
    }
    public function transactionDelete($id)
    {
        Transaction::find($id)->delete();
        return redirect('transactionManagement')->with('success', 'Transaction Deleted Successful!');
    }
}
