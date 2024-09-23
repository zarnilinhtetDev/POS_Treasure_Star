<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //index
    public function index()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $customers = Customer::latest()->get();
            $branchs = Warehouse::all();
        } else {
            $branchs = Warehouse::all();
            $customers = Customer::whereIn('branch', $warehousePermission)->latest()->get();
            $branchs = Warehouse::all();
        }


        return view('customer.customer', compact('customers', 'branchs'));
    }

    public function credit($id)
    {
        $customer = Customer::find($id);
        $invoices = Invoice::where('customer_id', $id)
            ->where('status', 'Invoice')
            ->get();
        $total_amount = $invoices->sum('total');
        $balance = $invoices->sum('remain_balance');
        $deposit = $invoices->sum('deposit');
        return view('customer.credit', compact('invoices', 'customer', 'total_amount', 'balance', 'deposit'));
    }

    public function customer_invoice($id)
    {
        $customer = Customer::find($id);
        $invoices = Invoice::where('customer_id', $id)
            ->where('status', 'Invoice')
            ->get();
        $total_amount = $invoices->sum('total');
        $balance = $invoices->sum('remain_balance');
        $deposit = $invoices->sum('deposit');
        return view('customer.customer_invoice', compact('invoices', 'customer', 'total_amount', 'balance', 'deposit'));
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
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {
        $showCustomer = Customer::find($id);
        $branchs = Warehouse::all();
        return view('customer.customer_edit', compact('showCustomer', 'branchs'));
    }
    public function update($id, Request $request)
    {
        $customer = Customer::find($id);
        $customer->update($request->all());
        $customers = Customer::latest()->get();
        return redirect('customer')->with('success', 'Customer Updated Successful!');
    }
    public function delete($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return redirect('customer')->with('success', 'Customer Deleted Successful!');
    }
}
