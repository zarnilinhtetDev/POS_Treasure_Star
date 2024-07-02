<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    //
    public function index()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $suppliers = Supplier::latest()->get();
            $branchs = Warehouse::all();
        } else {
            $suppliers = Supplier::whereIn('branch', $warehousePermission)->latest()->get();
            $branchs = Warehouse::all();
        }
        return view('supplier.supplier', compact('suppliers', 'branchs'));
    }
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|unique:suppliers,name',
                'phno' => 'required|unique:suppliers,phno',
                'address' => 'required',
                'branch' => 'required',
            ]);

            Supplier::create($validate);

            return redirect()->back()->with('success', 'Supplier Added Successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        $branchs = Warehouse::all();

        return view(
            'supplier.supplier_edit',
            compact('supplier', 'branchs')
        );
    }
    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->update($request->all());
        return redirect('supplier')->with('success', 'Supplier Updated Successful!');
    }
    public function delete($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        return redirect('supplier')->with('success', 'Supplier Delete Successful!');
    }
}
