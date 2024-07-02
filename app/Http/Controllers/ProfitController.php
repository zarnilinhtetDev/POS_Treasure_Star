<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    public function index()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $invoices = Invoice::whereMonth('invoice_date', $currentMonth)
                ->whereYear('invoice_date', $currentYear)
                ->get();

            $purchases = PurchaseOrder::whereMonth('po_date', $currentMonth)
                ->whereYear('po_date', $currentYear)
                ->get();

            $expenses = Expense::whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->get();

            $totalSum = $invoices->sum('total');
            $totalPurchase = $purchases->sum('total');
            $totalExpense = $expenses->sum('amount');
        } else {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $invoices = Invoice::whereMonth('invoice_date', $currentMonth)
                ->whereYear('invoice_date', $currentYear)
                ->whereIn('branch', $warehousePermission)
                ->get();

            $purchases = PurchaseOrder::whereMonth('po_date', $currentMonth)
                ->whereYear('po_date', $currentYear)
                ->whereIn('branch', $warehousePermission)
                ->get();

            $expenses = Expense::whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->whereIn('branch', $warehousePermission)
                ->get();

            $totalSum = $invoices->sum('total');
            $totalPurchase = $purchases->sum('total');
            $totalExpense = $expenses->sum('amount');
        }

        return view('profit.profit', compact('invoices', 'totalSum', 'totalPurchase', 'totalExpense'));
    }

    public function search(Request $request)
    {


        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])->get();
            $purchases = PurchaseOrder::whereBetween('po_date', [$startDate, $endDate])->get();
            $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();

            $totalSum = $invoices->sum('total');
            $totalPurchase = $purchases->sum('total');
            $totalExpense = $expenses->sum('amount');
        } else {

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
                ->whereIn('branch', $warehousePermission)
                ->get();
            $purchases = PurchaseOrder::whereBetween('po_date', [$startDate, $endDate])
                ->whereIn('branch', $warehousePermission)
                ->get();
            $expenses = Expense::whereBetween('date', [$startDate, $endDate])
                ->whereIn('branch', $warehousePermission)
                ->get();

            $totalSum = $invoices->sum('total');
            $totalPurchase = $purchases->sum('total');
            $totalExpense = $expenses->sum('amount');
        }

        return view('profit.profit', compact('invoices', 'totalSum', 'totalPurchase', 'totalExpense'));
    }
}
