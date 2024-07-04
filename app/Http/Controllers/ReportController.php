<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
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
            ->where('status', 'invoice');

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
        $totalKbz = $invoices->where('payment_method', 'KBZ Pay')->sum('total');
        $totalCB = $invoices->where('payment_method', 'CB Pay')->sum('total');
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
            $invoices = Invoice::whereDate('created_at', today())->where('status', 'invoice')->where('balance_due', 'PO Return')->get();
            $total = $invoices->sum('total');
            $branchs = Warehouse::all();
        } else {
            $invoices = Invoice::whereDate('created_at', today())->where('status', 'invoice')->where('balance_due', 'PO Return')->whereIn('branch', $warehousePermission)->get();
            $total = $invoices->sum('total');
            $branchs = Warehouse::all();
        }

        return view('report.report_sale_return', compact('invoices', 'total', 'branchs'));
    }

    public function report_quotation()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $quotations = Invoice::whereDate('created_at', today())->where('status', 'quotation')->get();
            $total = $quotations->sum('total');
            $branchs = Warehouse::all();
        } else {
            $quotations = Invoice::whereDate('created_at', today())->where('status', 'quotation')->whereIn('branch', $warehousePermission)->get();
            $total = $quotations->sum('total');
            $branchs = Warehouse::all();
        }


        return view('report.report_quotation', compact('quotations', 'total', 'branchs'));
    }

    public function report_po()
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $pos = PurchaseOrder::whereDate('created_at', today())
                ->where('balance_due', 'PO')
                ->get();
            $branchs = Warehouse::all();
            $totalCash = $pos->where('payment_method', 'Cash')->where('balance_due', 'PO')->sum('total');
            $totalKbz = $pos->where('payment_method', 'K Pay')->where('balance_due', 'PO')->sum('total');
            $totalCB =  $pos->where('payment_method', 'Wave')->where('balance_due', 'PO')->sum('total');
            $totalOther =  $pos->where('payment_method', 'Others')->where('balance_due', 'PO')->sum('total');
        } else {
            $pos = PurchaseOrder::whereDate('created_at', today())
                ->whereIn('branch', $warehousePermission)
                ->where('balance_due', 'PO')
                ->get();
            $branchs = Warehouse::all();
            $totalCash = $pos->where('payment_method', 'Cash')->where('balance_due', 'PO')
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
            $totalKbz = $pos->where('payment_method', 'K Pay')->where('balance_due', 'PO')
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
            $totalCB =  $pos->where('payment_method', 'Wave')->where('balance_due', 'PO')
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
            $totalOther =  $pos->where('payment_method', 'Others')->where('balance_due', 'PO')
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
        }

        $total = $pos->sum('total');
        return view('report.report_po', compact(
            'pos',
            'total',
            'branchs',
            'totalCash',
            'totalKbz',
            'totalCB',
            'totalOther',
        ));
    }

    public function report_purchase_return()
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

        return view('report.report_purchase_return', compact('pos', 'total', 'branchs'));
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
        $totalKbz = $search_invoices->where('payment_method', 'KBZ Pay')->sum('total');
        $totalCB = $search_invoices->where('payment_method', 'CB Pay')->sum('total');
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



    public function monthly_sale_return(Request $request)
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
        return view('report.report_sale_return', compact('search_invoices', 'search_total', 'branchs'));
    }

    public function monthly_quotation_search(Request $request)
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_quotations = Invoice::where('status', 'quotation')
                ->whereDate('quote_date', '>=', $start_date)
                ->whereDate('quote_date', '<=', $end_date)
                ->get();
            $search_total = $search_quotations->sum('total');
            $branchs = Warehouse::all();
        } else {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_quotations = Invoice::where('status', 'quotation')
                ->whereIn('branch', $warehousePermission)
                ->whereDate('quote_date', '>=', $start_date)
                ->whereDate('quote_date', '<=', $end_date)
                ->get();
            $search_total = $search_quotations->sum('total');
            $branchs = Warehouse::all();
        }

        return view('report.report_quotation', compact('search_quotations', 'search_total', 'branchs'));
    }

    public function monthly_po_search(Request $request)
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_pos = PurchaseOrder::whereDate('po_date', '>=', $start_date)
                ->whereDate('po_date', '<=', $end_date)
                ->where('balance_due', 'PO')
                ->get();
            $search_total = $search_pos->sum('total');
            $branchs = Warehouse::all();
            $totalCash = $search_pos->where('payment_method', 'Cash')->where('balance_due', 'PO')->sum('total');
            $totalKbz = $search_pos->where('payment_method', 'K Pay')->where('balance_due', 'PO')->sum('total');
            $totalCB =  $search_pos->where('payment_method', 'Wave')->where('balance_due', 'PO')->sum('total');
            $totalOther =  $search_pos->where('payment_method', 'Others')->where('balance_due', 'PO')->sum('total');
        } else {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_pos = PurchaseOrder::whereDate('po_date', '>=', $start_date)
                ->whereIn('branch', $warehousePermission)
                ->whereDate('po_date', '<=', $end_date)
                ->where('balance_due', 'PO')
                ->get();
            $search_total = $search_pos->sum('total');
            $branchs = Warehouse::all();
            $totalCash = $search_pos->where('payment_method', 'Cash')->where('balance_due', 'PO')->sum('total');
            $totalKbz = $search_pos->where('payment_method', 'K Pay')->where('balance_due', 'PO')->sum('total');
            $totalCB =  $search_pos->where('payment_method', 'Wave')->where('balance_due', 'PO')->sum('total');
            $totalOther =  $search_pos->where('payment_method', 'Others')->where('balance_due', 'PO')->sum('total');
        }


        return view('report.report_po', compact(
            'search_pos',
            'search_total',
            'branchs',
            'totalCash',
            'totalKbz',
            'totalCB',
            'totalOther',
        ));
    }

    public function monthly_purchase_return(Request $request)
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

        return view('report.report_purchase_return', compact('search_pos', 'search_total', 'branchs'));
    }


    public function report_pos()
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $pos_data = Invoice::whereDate('created_at', today())->where('status', 'POS')->get();
            $today = Carbon::today();
            $sale_totals = DB::table('invoices')
                ->select('sale_by', DB::raw('count(*) as total_invoices'), DB::raw('sum(total) as sale_total'))
                ->whereDate('created_at', $today)
                ->where('status', 'POS')
                ->groupBy('sale_by')
                ->get();
            $total = $pos_data->sum('pos_data');
            $branchs = Warehouse::all();
            $totalCash = $pos_data->where('payment_method', 'Cash')->sum('total');
            $totalKbz = $pos_data->where('payment_method', 'K Pay')->sum('total');
            $totalCB =  $pos_data->where('payment_method', 'Wave')->sum('total');
            $totalOther =  $pos_data->where('payment_method', 'Others')->sum('total');
        } else {
            $pos_data = Invoice::whereDate('created_at', today())->where('status', 'POS')->whereIn('branch', $warehousePermission)->get();
            $today = Carbon::today();
            $sale_totals = DB::table('invoices')
                ->select('sale_by', DB::raw('count(*) as total_invoices'), DB::raw('sum(total) as sale_total'))
                ->whereDate('created_at', $today)
                ->where('status', 'POS')
                ->whereIn('branch', $warehousePermission)
                ->groupBy('sale_by')
                ->get();
            $total = $pos_data->sum('pos_data');
            $branchs = Warehouse::all();
            $totalCash = $pos_data->where('payment_method', 'Cash')
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
            $totalKbz = $pos_data->where('payment_method', 'K Pay')
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
            $totalCB =  $pos_data->where('payment_method', 'Wave')
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
            $totalOther =  $pos_data->where('payment_method', 'Others')
                ->whereIn('branch', $warehousePermission)
                ->sum('total');
        }

        return view('report.report_pos', compact('pos_data', 'total', 'sale_totals', 'branchs', 'totalCash', 'totalKbz', 'totalCB', 'totalOther'));
    }
    public function monthly_pos_search(Request $request)
    {

        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_pos = Invoice::where('status', 'POS')
                ->whereDate('invoice_date', '>=', $start_date)
                ->whereDate('invoice_date', '<=', $end_date)
                ->get();
            $search_total = $search_pos->sum('total');
            $sale_totals = DB::table('invoices')
                ->select('sale_by', DB::raw('count(*) as total_invoices'), DB::raw('sum(total) as sale_total'))
                ->whereDate('invoice_date', '>=', $start_date)
                ->whereDate('invoice_date', '<=', $end_date)
                ->where('status', 'POS')
                ->groupBy('sale_by')
                ->get();
            $branchs = Warehouse::all();
            $totalCash = $search_pos->where('payment_method', 'Cash')->sum('total');
            $totalKbz = $search_pos->where('payment_method', 'K Pay')->sum('total');
            $totalCB =  $search_pos->where('payment_method', 'Wave')->sum('total');
            $totalOther =  $search_pos->where('payment_method', 'Others')->sum('total');
        } else {
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_pos = Invoice::where('status', 'POS')
                ->whereIn('branch', $warehousePermission)
                ->whereDate('invoice_date', '>=', $start_date)
                ->whereDate('invoice_date', '<=', $end_date)
                ->get();
            $search_total = $search_pos->sum('total');
            $sale_totals = DB::table('invoices')
                ->select('sale_by', DB::raw('count(*) as total_invoices'), DB::raw('sum(total) as sale_total'))
                ->whereDate('invoice_date', '>=', $start_date)
                ->whereDate('invoice_date', '<=', $end_date)
                ->whereIn('branch', $warehousePermission)
                ->where('status', 'POS')
                ->groupBy('sale_by')
                ->get();
            $branchs = Warehouse::all();
            $totalCash = $search_pos->where('payment_method', 'Cash')->sum('total');
            $totalKbz = $search_pos->where('payment_method', 'K Pay')->sum('total');
            $totalCB =  $search_pos->where('payment_method', 'Wave')->sum('total');
            $totalOther =  $search_pos->where('payment_method', 'Others')->sum('total');
        }

        return view('report.report_pos', compact('search_pos', 'search_total', 'sale_totals', 'branchs', 'totalCash', 'totalKbz', 'totalCB', 'totalOther'));
    }


    public function reportExpense()
    {
        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $branchs = Warehouse::all();
            $expenses = Expense::whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->get();
            $total = $expenses->sum('amount');
        } else {
            $branchs = Warehouse::all();
            $expenses = Expense::whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->whereIn('branch', $warehousePermission)
                ->get();
            $total = $expenses->sum('amount');
        }

        return view('report.report_expense', compact('expenses', 'total', 'branchs'));
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
        } else {
            $branchs = Warehouse::all();
            $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
            $search_expenses = Expense::whereDate('date', '>=', $start_date)
                ->whereDate('date', '<=', $end_date)->whereIn('branch', $warehousePermission)->get();

            $search_total = $search_expenses->sum('amount');
        }

        return view('report.report_expense', compact('search_expenses', 'search_total', 'branchs'));
    }
}
