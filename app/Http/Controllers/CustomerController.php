<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //index
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer.customer', compact('customers'));
    }

    public function credit($id)
    {
        $customer = Customer::find($id);
        $invoices = Invoice::where('customer_id', $id)->get();
        $total_amount = $invoices->sum('total');
        $balance = $invoices->sum('remain_balance');
        $deposit = $invoices->sum('deposit');
        return view('customer.credit', compact('invoices', 'customer', 'total_amount', 'balance', 'deposit'));
    }
    public function store(Request $request)
    {

        try {
            $validated = $request->validate(
                [
                    'name' => 'required',
                    'phno' => 'required',
                    'type' => 'required',
                    'address' => 'required',
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
        return view('customer.customer_edit', compact('showCustomer'));
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
