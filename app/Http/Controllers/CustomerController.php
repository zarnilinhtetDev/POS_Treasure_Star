<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

            if (auth()->user()->is_admin == '1') {
                $customers = Customer::latest()->get();
                $branchs = Warehouse::select('name', 'id')->get();
            } else {
                $customers = Customer::whereIn('branch', $warehousePermission)->latest()->get();
                $branchs = Warehouse::select('name', 'id')->get();
            }

            return view('customer.customer', compact('customers', 'branchs'));
        } catch (\Exception $e) {
            // Handle cache exception
            return redirect('customer.customer')->with('trycache', 'Something went wrong: Please try again');
        }
    }

    public function credit($id)
    {
        try {
            $customer = Customer::find($id);
            $invoices = Invoice::where('customer_id', $id)
                ->where('status', 'Invoice')
                ->get();
            $total_amount = $invoices->sum('total');
            $balance = $invoices->sum('remain_balance');
            $deposit = $invoices->sum('deposit');
            return view('customer.credit', compact('invoices', 'customer', 'total_amount', 'balance', 'deposit'));
        } catch (\Exception $e) {
            // Handle cache exception
            return redirect('customer.credit')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function customer_invoice($id)
    {
        try {
            $customer = Customer::find($id);
            $invoices = Invoice::where('customer_id', $id)
                ->where('status', 'Invoice')
                ->get();
            $total_amount = $invoices->sum('total');
            $balance = $invoices->sum('remain_balance');
            $deposit = $invoices->sum('deposit');
            return view('customer.customer_invoice', compact('invoices', 'customer', 'total_amount', 'balance', 'deposit'));
        } catch (\Exception $e) {
            // Handle cache exception
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'name' => 'required',
                    'phno' => 'nullable',
                    'type' => 'nullable',
                    'address' => 'nullable',
                    'branch' => 'required',
                ],
                ['type.required' => 'Customer Type is required']
            );

            Customer::create($validated);

            return redirect()->back()->with('success', 'New Customer Added Successfully!');
        } catch (\Exception $e) {
            // Handle cache exception
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $showCustomer = Customer::find($id);
            $branchs = Warehouse::select('name', 'id')->get();
            return view('customer.customer_edit', compact('showCustomer', 'branchs'));
        } catch (\Exception $e) {
            // Handle cache exception
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $customer = Customer::find($id);
            $customer->update($request->all());
            $customers = Customer::latest()->get();
            return redirect('customer')->with('success', 'Customer Updated Successful!');
        } catch (\Exception $e) {
            // Handle cache exception
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $customer = Customer::find($id);
            $customer->delete();
            return redirect('customer')->with('success', 'Customer Deleted Successful!');
        } catch (\Exception $e) {
            // Handle cache exception
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function customerDue(Request $request){

        $date = $request->date;
        if($date){
            $invoices = Invoice::where('remain_balance', '!=' , '0')->where('overdue_date','<',$date)->latest()->get();
        }else{
            $invoices = Invoice::where('remain_balance', '!=' , '0')->latest()->get();
        }
        return view('customer.customer_due',compact('invoices'));
    }
}
