<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {

        $categories = ExpenseCategory::latest()->get();
        $branches = Warehouse::latest()->get();
        // } else {
        //     $categories = ExpenseCategory::where('branch', auth()->user()->level)->latest()->get();
        //     $branches = Warehouse::latest()->get();
        // }
        return view('expense.expense_category', compact('categories', 'branches'));
    }

    public function categoryStore(Request $request)
    {
        ExpenseCategory::create($request->all());
        return redirect(url('expense_category'))->with('success', 'Expense Category Create Successful!');
    }
    public function edit($id)
    {
        $expense = ExpenseCategory::find($id);
        $branches = Warehouse::latest()->get();
        return view('expense.expense_category_edit', compact('expense', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $category = ExpenseCategory::find($id);
        $category->update($request->all());
        return redirect(url('expense_category'))->with('success', 'Expense Category Updated Successfully!');
    }

    public function delete($id)
    {
        $category = ExpenseCategory::find($id);
        $category->delete();
        return redirect()->back()->with('delete', 'Expense Category deleted Successfully!');
    }
}
