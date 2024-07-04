<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    // public function index()
    // {

    //     $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

    //     if (auth()->user()->is_admin == '1') {
    //         $currentMonth = Carbon::now()->month;
    //         $currentYear = Carbon::now()->year;

    //         $invoices = Invoice::whereMonth('invoice_date', $currentMonth)
    //             ->whereYear('invoice_date', $currentYear)
    //             ->get();

    //         $purchases = PurchaseOrder::whereMonth('po_date', $currentMonth)
    //             ->whereYear('po_date', $currentYear)
    //             ->get();

    //         $expenses = Expense::whereMonth('date', $currentMonth)
    //             ->whereYear('date', $currentYear)
    //             ->get();

    //         $totalSum = $invoices->sum('total');
    //         $totalPurchase = $purchases->sum('total');
    //         $totalExpense = $expenses->sum('amount');
    //     } else {
    //         $currentMonth = Carbon::now()->month;
    //         $currentYear = Carbon::now()->year;

    //         $invoices = Invoice::whereMonth('invoice_date', $currentMonth)
    //             ->whereYear('invoice_date', $currentYear)
    //             ->whereIn('branch', $warehousePermission)
    //             ->get();

    //         $purchases = PurchaseOrder::whereMonth('po_date', $currentMonth)
    //             ->whereYear('po_date', $currentYear)
    //             ->whereIn('branch', $warehousePermission)
    //             ->get();

    //         $expenses = Expense::whereMonth('date', $currentMonth)
    //             ->whereYear('date', $currentYear)
    //             ->whereIn('branch', $warehousePermission)
    //             ->get();

    //         $totalSum = $invoices->sum('total');
    //         $totalPurchase = $purchases->sum('total');
    //         $totalExpense = $expenses->sum('amount');
    //     }

    //     return view('profit.profit', compact('invoices', 'totalSum', 'totalPurchase', 'totalExpense'));
    // }
    public function index(Request $request)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];
        $branch = $request->query('branch'); // Get the branch from the query parameters

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $invoicesQuery = Invoice::whereMonth('invoice_date', $currentMonth)
            ->whereYear('invoice_date', $currentYear);

        $purchasesQuery = PurchaseOrder::whereMonth('po_date', $currentMonth)
            ->whereYear('po_date', $currentYear);

        $expensesQuery = Expense::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear);

        if ($branch) {
            $invoicesQuery->where('branch', $branch);
            $purchasesQuery->where('branch', $branch);
            $expensesQuery->where('branch', $branch);
        } else {
            if (!auth()->user()->is_admin) {
                $invoicesQuery->whereIn('branch', $warehousePermission);
                $purchasesQuery->whereIn('branch', $warehousePermission);
                $expensesQuery->whereIn('branch', $warehousePermission);
            }
        }

        $invoices = $invoicesQuery->get();
        $purchases = $purchasesQuery->get();
        $expenses = $expensesQuery->get();

        $totalSum = $invoices->sum('total');
        $totalPurchase = $purchases->sum('total');
        $totalExpense = $expenses->sum('amount');

        $branchs = Warehouse::all();
        $branchNames = $branchs->pluck('name', 'id');
        $currentBranchName = $branch ? $branchNames[$branch] : 'All Invoices';

        return view('profit.profit', compact('invoices', 'totalSum', 'totalPurchase', 'totalExpense', 'branchs', 'currentBranchName'));
    }


    public function search(Request $request)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $branch = $request->input('branch');

        $invoicesQuery = Invoice::whereBetween('invoice_date', [$startDate, $endDate]);
        $purchasesQuery = PurchaseOrder::whereBetween('po_date', [$startDate, $endDate]);
        $expensesQuery = Expense::whereBetween('date', [$startDate, $endDate]);

        if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
            if (!empty($branch)) {
                $invoicesQuery->where('branch', $branch);
                $purchasesQuery->where('branch', $branch);
                $expensesQuery->where('branch', $branch);
            }
        } else {
            $invoicesQuery->whereIn('branch', $warehousePermission);
            $purchasesQuery->whereIn('branch', $warehousePermission);
            $expensesQuery->whereIn('branch', $warehousePermission);
        }

        $invoices = $invoicesQuery->get();
        $purchases = $purchasesQuery->get();
        $expenses = $expensesQuery->get();

        $totalSum = $invoices->sum('total');
        $totalPurchase = $purchases->sum('total');
        $totalExpense = $expenses->sum('amount');

        // Get the branch name
        $branchs = Warehouse::all();
        $branchNames = $branchs->pluck('name', 'id');
        $currentBranchName = $branch ? $branchNames->get($branch, 'Unknown Branch') : 'All Invoices';

        return view('profit.profit', compact('invoices', 'totalSum', 'totalPurchase', 'totalExpense', 'currentBranchName', 'branchs'));
    }
}
