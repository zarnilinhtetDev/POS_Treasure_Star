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
    public function report_invoice()
    {

        $invoices = Invoice::whereDate('created_at', today())->where('status', 'invoice')->where('balance_due', 'Invoice')->get();
        $total = $invoices->sum('total');
        // $quotations = Invoice::whereDate('created_at', today())->where('status', 'invoice')->get();
        // $pos = PurchaseOrder::whereDate('created_at', today())->get();

        return view('report.report_invoice', compact('invoices', 'total'));
    }

    public function report_sale_return()
    {

        $invoices = Invoice::whereDate('created_at', today())->where('status', 'invoice')->where('balance_due', 'PO Return')->get();
        $total = $invoices->sum('total');

        return view('report.report_sale_return', compact('invoices', 'total'));
    }

    public function report_quotation()
    {

        $quotations = Invoice::whereDate('created_at', today())->where('status', 'quotation')->get();

        $total = $quotations->sum('total');

        return view('report.report_quotation', compact('quotations', 'total'));
    }

    public function report_po()
    {

        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {

        $pos = PurchaseOrder::whereDate('created_at', today())
            ->where('balance_due', 'PO')
            ->get();

        $total = $pos->sum('total');
        // } elseif (auth()->user()->type == 'Warehouse' || auth()->user()->type == 'Shop') {

        //     $pos = PurchaseOrder::whereDate('created_at', today())
        //         ->where('balance_due', 'PO')
        //         ->get();

        //     $total = 0;
        //     foreach ($pos as $pos_item) {
        //         foreach ($pos_item->po_sells as $po_sell) {
        //             if ($po_sell->warehouse === auth()->user()->level) {
        //                 $total += $pos_item->total;
        //                 break; // Exit the inner loop once a match is found
        //             }
        //         }
        //     }
        // }

        // $total = $pos->sum('total');
        return view('report.report_po', compact('pos', 'total'));
    }

    public function report_purchase_return()
    {
        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
        // Fetch records for Admin users
        $pos = PurchaseOrder::whereDate('created_at', today())
            ->where('quote_no', 'like', 'PO%')
            ->where('balance_due', 'Sale Return')
            ->get();

        $total = $pos->sum('total');
        // } elseif (auth()->user()->type == 'Warehouse' || auth()->user()->type == 'Shop') {
        //     // Fetch records for Warehouse or Shop users
        //     $pos = PurchaseOrder::whereDate('created_at', today())
        //         ->where('quote_no', 'like', 'PO%')
        //         ->where('balance_due', 'Sale Return')
        //         ->get();

        //     $total = 0;
        //     foreach ($pos as $pos_item) {
        //         foreach ($pos_item->po_sells as $po_sell) {
        //             if ($po_sell->warehouse === auth()->user()->level) {
        //                 $total += $pos_item->total;
        //                 break; // Exit the inner loop once a match is found
        //             }
        //         }
        //     }
        // }

        return view('report.report_purchase_return', compact('pos', 'total'));
    }

    // public function report_item()
    // {

    //     if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
    //         $warehouse_name = [];
    //         $warehouses = Warehouse::all();
    //         foreach ($warehouses as $key => $ware) {
    //             $warehouse_name[$key] = $ware->name;
    //         }
    //         $items = DB::table('items')
    //             ->select('items.item_name', 'warehouses.id as warehouse_id', 'warehouses.name as warehouse_name', DB::raw('SUM(items.quantity) AS total_quantity'))
    //             ->join('warehouses', 'items.warehouse_id', '=', 'warehouses.id')
    //             ->groupBy('items.item_name', 'warehouses.id')
    //             ->get();

    //         // Rearrange the data to group by item name and store warehouse quantities
    //         $groupedItems = [];
    //         foreach ($items as $item) {
    //             $itemId = $item->item_name;
    //             if (!isset($groupedItems[$itemId])) {
    //                 $groupedItems[$itemId] = [
    //                     'item_name' => $item->item_name,
    //                     'total_quantity' => 0,
    //                     'warehouse_quantities' => [],
    //                 ];
    //             }
    //             $groupedItems[$itemId]['total_quantity'] += $item->total_quantity;
    //             $groupedItems[$itemId]['warehouse_quantities'][$item->warehouse_id] = $item->total_quantity;
    //         }

    //         // Convert the grouped items array to a collection
    //         $items = collect($groupedItems)->values();

    //         // return $items;
    //         return view('report.report_item', compact('items', 'warehouse_name', 'warehouses'));
    //     } elseif (auth()->user()->type == 'Warehouse' || auth()->user()->type == 'Shop') {

    //         $warehouse_name = [];
    //         $warehouses = Warehouse::all();
    //         foreach ($warehouses as $key => $ware) {
    //             $warehouse_name[$key] = $ware->name;
    //         }

    //         $warehouseId = auth()->user()->level;

    //         $items = DB::table('items')
    //             ->select('items.item_name', 'warehouses.id as warehouse_id', 'warehouses.name as warehouse_name', DB::raw('SUM(items.quantity) AS total_quantity'))
    //             ->join('warehouses', 'items.warehouse_id', '=', 'warehouses.id')
    //             ->where('warehouses.id', $warehouseId) // Filter by the authenticated user's warehouse_id
    //             ->groupBy('items.item_name', 'warehouses.id')
    //             ->get();

    //         // Rearrange the data to group by item name and store warehouse quantities
    //         $groupedItems = [];
    //         foreach ($items as $item) {
    //             $itemId = $item->item_name;
    //             if (!isset($groupedItems[$itemId])) {
    //                 $groupedItems[$itemId] = [
    //                     'item_name' => $item->item_name,
    //                     'total_quantity' => 0,
    //                     'warehouse_quantities' => [],
    //                 ];
    //             }
    //             $groupedItems[$itemId]['total_quantity'] += $item->total_quantity;
    //             $groupedItems[$itemId]['warehouse_quantities'][$item->warehouse_id] = $item->total_quantity;
    //         }

    //         // Convert the grouped items array to a collection
    //         $items = collect($groupedItems)->values();

    //         // return $items;
    //         return view('report.report_item', compact('items', 'warehouse_name', 'warehouses'));
    //     }
    // }

    public function report_item()
    {
        $user = auth()->user();

        $warehouse_name = [];
        $warehouses = Warehouse::all();
        foreach ($warehouses as $key => $ware) {
            $warehouse_name[$key] = $ware->name;
        }

        $query = DB::table('items')
            ->select('items.item_name', 'warehouses.id as warehouse_id', 'warehouses.name as warehouse_name', DB::raw('SUM(items.quantity) AS total_quantity'), 'items.retail_price', 'items.wholesale_price')
            ->join('warehouses', 'items.warehouse_id', '=', 'warehouses.id')
            ->groupBy('items.item_name', 'warehouses.id', 'items.retail_price', 'items.wholesale_price');

        if ($user->is_admin == '1' || $user->type == 'Admin') {
            // Admin can view all warehouses
            $items = $query->get();
        } elseif ($user->type == 'Warehouse' || $user->type == 'Shop') {
            // Filter by the authenticated user's warehouse_id
            $warehouseId = $user->level;
            $items = $query->where('warehouses.id', $warehouseId)->get();
        } else {
            // Handle other user types or unauthorized access
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Rearrange the data to group by item name and store warehouse quantities
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

        // Convert the grouped items array to a collection
        $items = collect($groupedItems)->values();

        return view('report.report_item', compact('items', 'warehouse_name', 'warehouses'));
    }

    public function monthly_invoice_search(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $search_invoices = Invoice::where('status', 'invoice')
            ->where('balance_due', 'Invoice')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();
        $search_total = $search_invoices->sum('total');

        return view('report.report_invoice', compact('search_invoices', 'search_total'));
    }

    public function monthly_sale_return(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $search_invoices = Invoice::where('status', 'invoice')
            ->where('balance_due', 'Po Return')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();
        $search_total = $search_invoices->sum('total');

        return view('report.report_sale_return', compact('search_invoices', 'search_total'));
    }

    public function monthly_quotation_search(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $search_quotations = Invoice::where('status', 'quotation')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();
        $search_total = $search_quotations->sum('total');

        return view('report.report_quotation', compact('search_quotations', 'search_total'));
    }

    public function monthly_po_search(Request $request)
    {

        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $search_pos = PurchaseOrder::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where('balance_due', 'PO')
            ->get();
        $search_total = $search_pos->sum('total');
        // } elseif (auth()->user()->type == 'Warehouse' || auth()->user()->type == 'Shop') {

        //     $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        //     $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        //     $search_pos = PurchaseOrder::whereDate('created_at', '>=', $start_date)
        //         ->whereDate('created_at', '<=', $end_date)
        //         ->where('balance_due', 'PO')
        //         ->get();

        //     $search_total = 0;
        //     foreach ($search_pos as $pos_item) {
        //         foreach ($pos_item->po_sells as $po_sell) {
        //             if ($po_sell->warehouse === auth()->user()->level) {
        //                 $search_total += $pos_item->total;
        //                 break; // Exit the inner loop once a match is found
        //             }
        //         }
        //     }
        // }

        return view('report.report_po', compact('search_pos', 'search_total'));
    }

    public function monthly_purchase_return(Request $request)
    {

        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $search_pos = PurchaseOrder::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where('quote_no', 'like', 'PO%')
            ->where('balance_due', 'Sale Return')
            ->get();
        $search_total = $search_pos->sum('total');
        // } elseif (auth()->user()->type == 'Warehouse' || auth()->user()->type == 'Shop') {

        //     $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        //     $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        //     $search_pos = PurchaseOrder::whereDate('created_at', '>=', $start_date)
        //         ->whereDate('created_at', '<=', $end_date)
        //         ->where('quote_no', 'like', 'PO%')
        //         ->where('balance_due', 'Sale Return')
        //         ->get();

        //     $search_total = 0;
        //     foreach ($search_pos as $pos_item) {
        //         foreach ($pos_item->po_sells as $po_sell) {
        //             if ($po_sell->warehouse === auth()->user()->level) {
        //                 $search_total += $pos_item->total;
        //                 break; // Exit the inner loop once a match is found
        //             }
        //         }
        //     }
        // }

        return view('report.report_purchase_return', compact('search_pos', 'search_total'));
    }

    // public function report_pos()
    // {

    //     if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {

    //         $pos_data = Invoice::whereDate('created_at', today())->where('status', 'POS')->get();

    //         $total = $pos_data->sum('pos_data');
    //     } elseif (auth()->user()->type == 'Warehouse' || auth()->user()->type == 'Cashier') {

    //         $pos_data = Invoice::whereDate('created_at', today())->where('status', 'POS')->get();
    //         $total = 0;
    //         foreach ($pos_data as $pos_item) {
    //             foreach ($pos_item->po_sells as $po_sell) {
    //                 if ($po_sell->warehouse === auth()->user()->level) {
    //                     $total += $pos_item->total;
    //                     break; // Exit the inner loop once a match is found
    //                 }
    //             }
    //         }
    //     }

    //     return view('report.report_pos', compact('pos_data', 'total'));
    // }

    // public function monthly_pos_search(Request $request)
    // {

    //     if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {

    //         $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
    //         $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
    //         $search_pos = Invoice::where('status', 'POS')
    //             ->whereDate('created_at', '>=', $start_date)
    //             ->whereDate('created_at', '<=', $end_date)
    //             ->get();
    //         $search_total = $search_pos->sum('total');
    //     } elseif (auth()->user()->type == 'Warehouse' || auth()->user()->type == 'Shop') {

    //         $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
    //         $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
    //         $search_pos = Invoice::where('status', 'POS')
    //             ->whereDate('created_at', '>=', $start_date)
    //             ->whereDate('created_at', '<=', $end_date)
    //             ->get();

    //         $search_total = 0;
    //         foreach ($search_pos as $pos_item) {
    //             foreach ($pos_item->po_sells as $po_sell) {
    //                 if ($po_sell->warehouse === auth()->user()->level) {
    //                     $search_total += $pos_item->total;
    //                     break; // Exit the inner loop once a match is found
    //                 }
    //             }
    //         }
    //     }

    //     return view('report.report_pos', compact('search_pos', 'search_total'));
    // }

    public function report_pos()
    {
        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
        $pos_data = Invoice::whereDate('created_at', today())->where('status', 'POS')->get();
        $today = Carbon::today();
        $sale_totals = DB::table('invoices')
            ->select('sale_by', DB::raw('count(*) as total_invoices'), DB::raw('sum(total) as sale_total'))
            ->whereDate('created_at', $today)
            ->where('status', 'POS')
            ->groupBy('sale_by')
            ->get();
        $total = $pos_data->sum('pos_data');
        // } elseif (auth()->user()->type == 'Warehouse' || auth()->user()->type == 'Cashier') {
        //     $pos_data = Invoice::whereDate('created_at', today())->where('status', 'POS')->get();
        //     $total = 0;
        //     foreach ($pos_data as $pos_item) {
        //         foreach ($pos_item->po_sells as $po_sell) {
        //             if ($po_sell->warehouse === auth()->user()->level) {
        //                 $total += $pos_item->total;
        //                 break; // Exit the inner loop once a match is found
        //             }
        //         }
        //     }
        // }
        return view('report.report_pos', compact('pos_data', 'total', 'sale_totals'));
    }
    public function monthly_pos_search(Request $request)
    {
        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $search_pos = Invoice::where('status', 'POS')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();
        $search_total = $search_pos->sum('total');
        $sale_totals = DB::table('invoices')
            ->select('sale_by', DB::raw('count(*) as total_invoices'), DB::raw('sum(total) as sale_total'))
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where('status', 'POS')
            ->groupBy('sale_by')
            ->get();
        // } elseif (auth()->user()->type == 'Warehouse' || auth()->user()->type == 'Shop') {
        //     $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        //     $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        //     $search_pos = Invoice::where('status', 'POS')
        //         ->whereDate('created_at', '>=', $start_date)
        //         ->whereDate('created_at', '<=', $end_date)
        //         ->get();
        //     $search_total = 0;
        //     foreach ($search_pos as $pos_item) {
        //         foreach ($pos_item->po_sells as $po_sell) {
        //             if ($po_sell->warehouse === auth()->user()->level) {
        //                 $search_total += $pos_item->total;
        //                 break; // Exit the inner loop once a match is found
        //             }
        //         }
        //     }
        // }
        return view('report.report_pos', compact('search_pos', 'search_total', 'sale_totals'));
    }


    public function reportExpense()
    {

        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            $expenses = Expense::whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->get();
            $total = $expenses->sum('amount');
        } else {
            $expenses = Expense::whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->where('branch', auth()->user()->level)->get();
            $total = $expenses->where('branch', auth()->user()->level)->sum('amount');
        }
        // dd($expenses);
        return view('report.report_expense', compact('expenses', 'total'));
    }

    public function expenseSearch(Request $request)
    {
        // dd($request);
        // if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') {
        $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        $search_expenses = Expense::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->get();
        // dd($search_expenses);
        $search_total = $search_expenses->sum('amount');
        // } else {
        //     $start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');
        //     $end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');
        //     $search_expenses = Expense::whereDate('created_at', '>=', $start_date)
        //         ->whereDate('created_at', '<=', $end_date)
        //         ->where('branch', auth()->user()->level)
        //         ->get();
        //     // dd($search_expenses);
        //     $search_total = $search_expenses->where('branch', auth()->user()->level)->sum('amount');
        // }
        // $expenses = [];
        return view('report.report_expense', compact('search_expenses', 'search_total'));
    }
}
