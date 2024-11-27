<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Payment;
use App\Models\Warehouse;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
        $sumByOut = Payment::where('payment_status', 'OUT')
            ->groupBy('transaction_id')
            ->selectRaw('transaction_id, SUM(amount) as total')
            ->pluck('total', 'transaction_id');
        $diff = collect($sumByIn)->map(function ($totalIn, $transactionId) use ($sumByOut) {
            $totalOut = $sumByOut->get($transactionId, 0);

            return $totalIn - $totalOut;
        });
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

        $payment = new Payment();
        $payment->account_id = $account->id;
        $payment->transaction_id = $trasaction->id;
        $payment->payment_status = 'IN';
        $payment->amount = 0;
        $payment->save();

        $payment = new Payment();
        $payment->account_id = $account->id;
        $payment->transaction_id = $trasaction->id;
        $payment->payment_status = 'IN';
        $payment->amount = 0;
        $payment->save();

        $payment = new Payment();
        $payment->account_id = $account->id;
        $payment->transaction_id = $trasaction->id;
        $payment->payment_status = 'OUT';
        $payment->amount = 0;
        $payment->save();

        $payment = new Payment();
        $payment->account_id = $account->id;
        $payment->transaction_id = $trasaction->id;
        $payment->payment_status = 'IN';
        $payment->amount = 0;
        $payment->save();

        $payment = new Payment();
        $payment->account_id = $account->id;
        $payment->transaction_id = $trasaction->id;
        $payment->payment_status = 'OUT';
        $payment->amount = 0;
        $payment->save();

        $payment = new Payment();
        $payment->account_id = $account->id;
        $payment->transaction_id = $trasaction->id;
        $payment->payment_status = 'OUT';
        $payment->amount = 0;
        $payment->save();

        $payment = new Payment();
        $payment->account_id = $account->id;
        $payment->transaction_id = $trasaction->id;
        $payment->payment_status = 'IN';
        $payment->amount = 0;
        $payment->save();

        $payment = new Payment();
        $payment->account_id = $account->id;
        $payment->transaction_id = $trasaction->id;
        $payment->payment_status = 'OUT';
        $payment->amount = 0;
        $payment->save();

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
