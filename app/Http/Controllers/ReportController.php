<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Invoice;
use App\Models\InvoicePaymentMethod;
use App\Models\MakePayment;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function report_invoice($branch = null)
    {
        $today = Carbon::today();
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        $invoicesQuery = Invoice::whereDate('created_at', $today)
            ->where('status', 'invoice')
            ->whereNull('balance_due');

        if ($branch) {
            $invoicesQuery->where('branch', $branch);
        }

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $invoices = $invoicesQuery->get();
        } else {
            $invoicesQuery->whereIn('branch', $warehousePermission);
            $invoices = $invoicesQuery->get();
        }

        $total = $invoices->sum('total');
        $totalCash = $invoices->where('payment_method', 'Cash')->sum('total');
        $totalKbz = $invoices->where('payment_method', 'K Pay')->sum('total');
        $totalCB = $invoices->where('payment_method', 'Wave')->sum('total');
        $totalOther = $invoices->where('payment_method', 'Others')->sum('total');

        $branchs = Warehouse::all();
        $branchNames = $branchs->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Invoices';

        return view('report.report_invoice', compact('invoices', 'total', 'totalCash', 'totalKbz', 'totalCB', 'totalOther', 'branch', 'branchs', 'currentBranchName'));
    }


    public function report_sale_return()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $pos = PurchaseOrder::whereDate('created_at', today())
                ->where('quote_no', 'like', 'PO%')
                ->where('balance_due', 'Sale Return')
                ->get();

            $total = $pos->sum('total');
            $branchs = Warehouse::all();
        } else {
            $pos = PurchaseOrder::whereDate('created_at', today())
                ->whereIn('branch', $warehousePermission)
                ->where('quote_no', 'like', 'PO%')
                ->where('balance_due', 'Sale Return')
                ->get();

            $total = $pos->sum('total');
            $branchs = Warehouse::all();
        }

        return view('report.report_sale_return', compact('pos', 'total', 'branchs'));
    }


    public function report_quotation($branch = null)
    {
        $today = Carbon::today();
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        $quotationsQuery = Invoice::whereDate('created_at', $today)
            ->where('status', 'quotation');

        if ($branch) {
            $quotationsQuery->where('branch', $branch);
        }

        if (auth()->user()->is_admin == '1') {
            $quotations = $quotationsQuery->get();
        } else {
            $quotationsQuery->whereIn('branch', $warehousePermission);
            $quotations = $quotationsQuery->get();
        }

        $total = $quotations->sum('total');
        $branchs = Warehouse::all();
        $branchNames = $branchs->pluck('name', 'id');
        $currentBranchName = $branch ? $branchNames[$branch] : 'All Quotations';

        return view('report.report_quotation', compact('quotations', 'total', 'branchs', 'currentBranchName'));
    }

    public function report_po(Request $request)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];
        $branch = $request->input('branch');
        $currentBranchName = "All Branches";

        $query = PurchaseOrder::whereDate('created_at', today())->where('balance_due', 'PO');

        if (auth()->user()->is_admin != '1') {
            $query->whereIn('branch', $warehousePermission);
        }

        if ($branch) {
            $query->where('branch', $branch);
            $currentBranchName = Warehouse::find($branch)->name;
        }

        $pos = $query->get();

        $totalCash = $pos->where('payment_method', 'Cash')->sum('total');
        $totalKbz = $pos->where('payment_method', 'K Pay')->sum('total');
        $totalCB = $pos->where('payment_method', 'Wave')->sum('total');
        $totalOther = $pos->where('payment_method', 'Others')->sum('total');
        $total = $pos->sum('total');

        $branchs = Warehouse::all();

        return view('report.report_po', compact(
            'pos',
            'total',
            'branchs',
            'totalCash',
            'totalKbz',
            'totalCB',
            'totalOther',
            'currentBranchName'
        ));
    }


    public function report_purchase_return()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $invoices = Invoice::whereDate('created_at', today())->where('status', 'invoice')->where('balance_due', 'PO Return')->get();
            $total = $invoices->sum('total');
            $branchs = Warehouse::all();
        } else {
            $invoices = Invoice::whereDate('created_at', today())->where('status', 'invoice')->where('balance_due', 'PO Return')->whereIn('branch', $warehousePermission)->get();
            $total = $invoices->sum('total');
            $branchs = Warehouse::all();
        }

        return view('report.report_purchase_return', compact('invoices', 'total', 'branchs'));
    }

    public function report_item()
    {
        $warehouse_name = [];
        $warehouses = Warehouse::all();
        foreach ($warehouses as $key => $ware) {
            $warehouse_name[$key] = $ware->name;
        }

        $query = DB::table('items')
            ->select('items.item_name', 'warehouses.id as warehouse_id', 'warehouses.name as warehouse_name', DB::raw('SUM(items.quantity) AS total_quantity'), 'items.retail_price', 'items.wholesale_price')
            ->join('warehouses', 'items.warehouse_id', '=', 'warehouses.id')
            ->groupBy('items.item_name', 'warehouses.id', 'items.retail_price', 'items.wholesale_price');

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $items = $query->get();
        } else {
            $items = $query->whereIn('warehouses.id', $warehousePermission)->get();
        }
        $groupedItems = [];
        foreach ($items as $item) {
            $itemId = $item->item_name;
            if (!isset($groupedItems[$itemId])) {
                $groupedItems[$itemId] = [
                    'item_name' => $item->item_name,
                    'total_quantity' => 0,
                    'warehouse_quantities' => [],
                    'retail_price' => $item->retail_price,
                    'wholesale_price' => $item->wholesale_price,
                ];
            }
            $groupedItems[$itemId]['total_quantity'] += $item->total_quantity;
            $groupedItems[$itemId]['warehouse_quantities'][$item->warehouse_id] = $item->total_quantity;
        }

        $items = collect($groupedItems)->values();

        return view('report.report_item', compact('items', 'warehouse_name', 'warehouses'));
    }

    public function monthly_item_search(Request $request)
    {
        $warehouse_name = [];
        $warehouses = Warehouse::all();
        foreach ($warehouses as $key => $ware) {
            $warehouse_name[$key] = $ware->name;
        }

        $query = DB::table('items')
            ->select('items.item_name', 'warehouses.id as warehouse_id', 'warehouses.name as warehouse_name', DB::raw('SUM(items.quantity) AS total_quantity'), 'items.retail_price', 'items.wholesale_price')
            ->join('warehouses', 'items.warehouse_id', '=', 'warehouses.id')
            ->groupBy('items.item_name', 'warehouses.id', 'items.retail_price', 'items.wholesale_price');

        // Date filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('items.created_at', [$start_date, $end_date]);
        }

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $items = $query->get();
        } else {
            $items = $query->whereIn('warehouses.id', $warehousePermission)->get();
        }

        $groupedItems = [];
        foreach ($items as $item) {
            $itemId = $item->item_name;
            if (!isset($groupedItems[$itemId])) {
                $groupedItems[$itemId] = [
                    'item_name' => $item->item_name,
                    'total_quantity' => 0,
                    'warehouse_quantities' => [],
                    'retail_price' => $item->retail_price,
                    'wholesale_price' => $item->wholesale_price,
                ];
            }
            $groupedItems[$itemId]['total_quantity'] += $item->total_quantity;
            $groupedItems[$itemId]['warehouse_quantities'][$item->warehouse_id] = $item->total_quantity;
        }

        $items = collect($groupedItems)->values();

        return view('report.report_item', compact('items', 'warehouse_name', 'warehouses'));
    }


    public function monthly_invoice_search(Request $request)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');

        $invoicesQuery = Invoice::where('status', 'invoice')
            ->where('balance_due', 'Invoice')
            ->whereNull('balance_due')
            ->whereDate('invoice_date', '>=', $start_date)
            ->whereDate('invoice_date', '<=', $end_date);

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            if ($request->has('branch') && !empty($request->input('branch'))) {
                $invoicesQuery->where('branch', $request->input('branch'));
            }
        } else {
            $invoicesQuery->whereIn('branch', $warehousePermission);
        }

        $search_invoices = $invoicesQuery->get();

        $totalCash = $search_invoices->where('payment_method', 'Cash')->sum('total');
        $totalKbz = $search_invoices->where('payment_method', 'K Pay')->sum('total');
        $totalCB = $search_invoices->where('payment_method', 'Wave')->sum('total');
        $totalOther = $search_invoices->where('payment_method', 'Others')->sum('total');
        $search_total = $search_invoices->sum('total');

        $branchs = Warehouse::all();
        $branchNames = $branchs->pluck('name', 'id');

        $branch = $request->input('branch');
        $currentBranchName = $branch ? $branchNames->get($branch, 'Unknown Branch') : 'All Invoices';

        return view('report.report_invoice', compact(
            'search_invoices',
            'search_total',
            'branchs',
            'totalCash',
            'totalKbz',
            'totalCB',
            'totalOther',
            'currentBranchName'
        ));
    }



    // public function monthly_sale_return(Request $request)
    // {

    //     $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

    //     if (auth()->user()->is_admin == '1') {
    //         $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
    //         $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
    //         $search_invoices = Invoice::where('status', 'invoice')
    //             ->where('balance_due', 'Po Return')
    //             ->whereDate('invoice_date', '>=', $start_date)
    //             ->whereDate('invoice_date', '<=', $end_date)
    //             ->get();
    //         $search_total = $search_invoices->sum('total');
    //         $branchs = Warehouse::all();
    //     } else {
    //         $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
    //         $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
    //         $search_invoices = Invoice::where('status', 'invoice')
    //             ->whereIn('branch', $warehousePermission)
    //             ->where('balance_due', 'Po Return')
    //             ->whereDate('invoice_date', '>=', $start_date)
    //             ->whereDate('invoice_date', '<=', $end_date)
    //             ->get();
    //         $search_total = $search_invoices->sum('total');
    //         $branchs = Warehouse::all();
    //     }
    //     return view('report.report_sale_return', compact('search_invoices', 'search_total', 'branchs'));
    // }
    public function monthly_sale_return(Request $request)
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_pos = PurchaseOrder::whereDate('po_date', '>=', $start_date)
                ->whereDate('po_date', '<=', $end_date)
                ->where('quote_no', 'like', 'PO%')
                ->where('balance_due', 'Sale Return')
                ->get();
            $search_total = $search_pos->sum('total');
            $branchs = Warehouse::all();
        } else {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_pos = PurchaseOrder::whereDate('po_date', '>=', $start_date)
                ->whereDate('po_date', '<=', $end_date)
                ->whereIn('branch', $warehousePermission)
                ->where('quote_no', 'like', 'PO%')
                ->where('balance_due', 'Sale Return')
                ->get();
            $search_total = $search_pos->sum('total');
            $branchs = Warehouse::all();
        }

        return view('report.report_sale_return', compact('search_pos', 'search_total', 'branchs'));
    }

    public function monthly_quotation_search(Request $request)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $branch = $request->input('branch');

        $quotationsQuery = Invoice::where('status', 'quotation')
            ->whereDate('quote_date', '>=', $start_date)
            ->whereDate('quote_date', '<=', $end_date);

        if ($branch) {
            $quotationsQuery->where('branch', $branch);
        }

        if (auth()->user()->is_admin != '1') {
            $quotationsQuery->whereIn('branch', $warehousePermission);
        }

        $search_quotations = $quotationsQuery->get();
        $search_total = $search_quotations->sum('total');
        $branchs = Warehouse::all();
        $branchNames = $branchs->pluck('name', 'id');
        $currentBranchName = $branch ? $branchNames[$branch] : 'All Quotations';

        return view('report.report_quotation', compact('search_quotations', 'search_total', 'branchs', 'currentBranchName'));
    }
    public function monthly_po_search(Request $request)
    {
        // Get the current user's branch permissions
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        // Fetch all branches
        $branchs = Warehouse::all();
        $branch = $request->input('branch');
        $branchNames = $branchs->pluck('name', 'id');
        $currentBranchName = $branch ? $branchNames->get($branch, 'Unknown Branch') : 'All Quotations';

        // Parse the start and end dates
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');

        // Initialize the Purchase Order query
        $search_pos = PurchaseOrder::whereDate('po_date', '>=', $start_date)
            ->whereDate('po_date', '<=', $end_date)
            ->where('balance_due', 'PO');

        // Check if the user is an admin
        if (auth()->user()->is_admin == '1') {
            // Admins can filter by any branch
            if ($branch) {
                $search_pos->where('branch', $branch);
            }
        } else {
            // Non-admins can only filter within their allowed branches
            $search_pos->whereIn('branch', $warehousePermission);

            if ($branch) {
                $search_pos->where('branch', $branch);
            }
        }

        // Execute the query and calculate totals
        $search_pos = $search_pos->get();
        $totalCash = $search_pos->where('payment_method', 'Cash')->sum('total');
        $totalKbz = $search_pos->where('payment_method', 'K Pay')->sum('total');
        $totalCB = $search_pos->where('payment_method', 'Wave')->sum('total');
        $totalOther = $search_pos->where('payment_method', 'Others')->sum('total');
        $search_total = $search_pos->sum('total');

        // Return the view with data
        return view('report.report_po', compact(
            'search_pos',
            'search_total',
            'branchs',
            'totalCash',
            'totalKbz',
            'totalCB',
            'totalOther',
            'currentBranchName'
        ));
    }




    public function monthly_purchase_return(Request $request)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_invoices = Invoice::where('status', 'invoice')
                ->where('balance_due', 'Po Return')
                ->whereDate('invoice_date', '>=', $start_date)
                ->whereDate('invoice_date', '<=', $end_date)
                ->get();
            $search_total = $search_invoices->sum('total');
            $branchs = Warehouse::all();
        } else {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_invoices = Invoice::where('status', 'invoice')
                ->whereIn('branch', $warehousePermission)
                ->where('balance_due', 'Po Return')
                ->whereDate('invoice_date', '>=', $start_date)
                ->whereDate('invoice_date', '<=', $end_date)
                ->get();
            $search_total = $search_invoices->sum('total');
            $branchs = Warehouse::all();
        }
        return view('report.report_purchase_return', compact('search_invoices', 'search_total', 'branchs'));
    }


    public function report_pos(Request $request)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];
        $branch = $request->input('branch');

        $today = Carbon::today();
        $posQuery = Invoice::whereDate('created_at', $today)
            ->where('status', 'POS');

        if ($branch) {
            $posQuery->where('branch', $branch);
        }

        if (auth()->user()->is_admin != '1') {
            $posQuery->whereIn('branch', $warehousePermission);
        }

        $pos_data = $posQuery->get();
        $sale_totals = DB::table('invoices')
            ->select('sale_by', DB::raw('count(*) as total_invoices'), DB::raw('sum(total) as sale_total'))
            ->whereDate('created_at', $today)
            ->where('status', 'POS')
            ->when($branch, function ($query, $branch) {
                return $query->where('branch', $branch);
            })
            ->when(auth()->user()->is_admin != '1', function ($query) use ($warehousePermission) {
                return $query->whereIn('branch', $warehousePermission);
            })
            ->groupBy('sale_by')
            ->get();

        $total = $pos_data->sum('total');
        $branchs = Warehouse::all();
        $branchNames = $branchs->pluck('name', 'id');
        $currentBranchName = $branch ? $branchNames[$branch] : 'All POS';

        $totalCash = $pos_data->where('payment_method', 'Cash')->sum('total');
        $totalKbz = $pos_data->where('payment_method', 'K Pay')->sum('total');
        $totalCB =  $pos_data->where('payment_method', 'Wave')->sum('total');
        $totalOther =  $pos_data->where('payment_method', 'Others')->sum('total');

        return view('report.report_pos', compact('pos_data', 'total', 'sale_totals', 'branchs', 'totalCash', 'totalKbz', 'totalCB', 'totalOther', 'currentBranchName'));
    }
    public function monthly_pos_search(Request $request)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $branch = $request->input('branch');

        $query = Invoice::where('status', 'POS')
            ->whereDate('invoice_date', '>=', $start_date)
            ->whereDate('invoice_date', '<=', $end_date);

        if (auth()->user()->is_admin != '1') {
            $query->whereIn('branch', $warehousePermission);
        }

        if ($branch) {
            $query->where('branch', $branch);
            $currentBranchName = Warehouse::find($branch)->name;
        } else {
            $currentBranchName = "All Locations"; // Default value if no branch is selected
        }

        $search_pos = $query->get();
        $search_total = $search_pos->sum('total');

        $sale_totals = DB::table('invoices')
            ->select('sale_by', DB::raw('count(*) as total_invoices'), DB::raw('sum(total) as sale_total'))
            ->whereDate('invoice_date', '>=', $start_date)
            ->whereDate('invoice_date', '<=', $end_date)
            ->where('status', 'POS');

        if (auth()->user()->is_admin != '1') {
            $sale_totals->whereIn('branch', $warehousePermission);
        }

        if ($branch) {
            $sale_totals->where('branch', $branch);
        }

        $sale_totals = $sale_totals->groupBy('sale_by')->get();

        $branchs = Warehouse::all();
        $totalCash = $search_pos->where('payment_method', 'Cash')->sum('total');
        $totalKbz = $search_pos->where('payment_method', 'K Pay')->sum('total');
        $totalCB = $search_pos->where('payment_method', 'Wave')->sum('total');
        $totalOther = $search_pos->where('payment_method', 'Others')->sum('total');

        return view('report.report_pos', compact('search_pos', 'search_total', 'sale_totals', 'branchs', 'totalCash', 'totalKbz', 'totalCB', 'totalOther', 'currentBranchName'));
    }


    public function reportExpense()
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $branchs = Warehouse::all();
            $expenses = Expense::whereDate('created_at', today())
                ->get();
            $total = $expenses->sum('amount');
            $categorys = ExpenseCategory::all();
        } else {
            $branchs = Warehouse::all();
            $expenses = Expense::whereDate('created_at', today())
                ->whereIn('branch', $warehousePermission)
                ->get();
            $total = $expenses->sum('amount');
            $categorys = ExpenseCategory::all();
        }

        return view('report.report_expense', compact('expenses', 'total', 'branchs', 'categorys'));
    }


    public function expenseSearch(Request $request)
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $branchs = Warehouse::all();
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_expenses = Expense::whereDate('date', '>=', $start_date)
                ->whereDate('date', '<=', $end_date)->get();

            $search_total = $search_expenses->sum('amount');
            $categorys = ExpenseCategory::all();
        } else {
            $branchs = Warehouse::all();
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_expenses = Expense::whereDate('date', '>=', $start_date)
                ->whereDate('date', '<=', $end_date)->whereIn('branch', $warehousePermission)->get();

            $search_total = $search_expenses->sum('amount');
            $categorys = ExpenseCategory::all();
        }

        return view('report.report_expense', compact('search_expenses', 'search_total', 'branchs', 'categorys'));
    }
}
