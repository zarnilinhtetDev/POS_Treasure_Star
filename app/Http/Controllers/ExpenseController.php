<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;

class ExpenseController extends Controller
{
    public function index()
    {
        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {

        // } else {
        //     $expenses = Expense::where('branch', auth()->user()->level)->latest()->get();
        //     $categories = ExpenseCategory::latest()->get();
        //     $branches = Warehouse::latest()->get();
        // }

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $expenses = Expense::latest()->get();
            $categories = ExpenseCategory::all();
            $branches = Warehouse::latest()->get();
        } else {
            $expenses = Expense::whereIn('branch', $warehousePermission)->latest()->get();
            $categories = ExpenseCategory::all();
            $branches = Warehouse::latest()->get();
        }

        return view('expense.expense', [
            "expenses" => $expenses,
            "categories" => $categories,
            "branches" => $branches
        ]);
    }

    public function expenseStore(Request $request)
    {

        $expense = new Expense();
        $expense->fill($request->all());
        $setting = Setting::where('category', 'Expense')->where('location', $request->branch)->first();
        if ($setting) {
            $expense->transaction_id = $setting->transaction_id ?? null;


            $transactions = Payment::all();
            foreach ($transactions as $transaction) {
                if ($transaction->id == $setting->transaction_id) {
                    $tran = Payment::where('transaction_id', $transaction->id)->get();
                    // dd($tran);
                    $payment = $tran->skip(8)->first();

                    if ($payment) {
                        $payment->amount += $request->amount;
                        $payment->save();
                    } else {
                    }
                }
            }
        }

        $expense->save();
        return redirect(url('expense'))->with('success', 'Expense Created Successfully!');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::latest()->get();
        $branches = Warehouse::latest()->get();
        return view('expense.expenseEdit', compact('expense', 'categories', 'branches'));
    }

    public function update(Request $request, Expense $expense)
    {


        if ($expense && $expense->transaction_id) {
            $oldtotal = $expense->amount;
            $currenttotal = $request->amount;


            if ($expense->transaction_id) {
                $payment = Payment::where('transaction_id', $expense->transaction_id)->skip(8)->first();

                if ($payment) {
                    $payment->amount = $payment->amount + ($currenttotal - $oldtotal);
                    $payment->save();
                } else {
                }
            } else {
            }
        }
        $expense->update($request->all());
        return redirect(url('expense'))->with('success', 'Expense Updated Successfully!');
    }

    public function delete(Expense $expense)
    {
        // $unit = Expense::find($id);
        if ($expense) {
            $oldtotal = $expense->amount;

            if ($expense->transaction_id) {
                $payment = Payment::where('transaction_id', $expense->transaction_id)->skip(8)->first();

                if ($payment) {
                    $payment->amount = $payment->amount - $oldtotal;
                    $payment->save();
                } else {
                }
            } else {
            }
        } else {
        }
        $expense->delete();
        return redirect()->back()->with('delete', 'Expense Deleted Successfully!');
    }

    public function get_part_data_unit()
    {
        $units = Expense::all();
        return response()->json($units);
    }
}
