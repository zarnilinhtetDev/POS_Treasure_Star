<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->get();
        $invoice = Setting::with('transaction')->where('category', '1')->first();
        $purchase_orders = Setting::with('transaction')->where('category', '2')->first();
        $point_of_sales = Setting::with('transaction')->where('category', '3')->first();
        $purchase_returns = Setting::with('transaction')->where('category', '4')->first();
        $sale_return_invoices = Setting::with('transaction')->where('category', '5')->first();
        $sale_return_pos = Setting::with('transaction')->where('category', '6')->first();
        return view('setting.setting', compact('transactions', 'invoice', 'purchase_orders', 'point_of_sales', 'purchase_returns', 'sale_return_invoices', 'sale_return_pos'));
    }

    public function invoice(Request $request)
    {
        $invoice_setting = new Setting();
        $invoice_setting->transaction_id = $request->transaction_id;
        $invoice_setting->category = '1';
        $invoice_setting->save();
        return redirect()->route('setting')->with('success', 'Invoice setting saved successfully.');
    }

    public function invoice_setting_edit(Request $request)
    {
        $invoice_setting = Setting::where('category', '1')->first();
        $invoice_setting->transaction_id = $request->transaction_id;
        $invoice_setting->update();
        return redirect()->route('setting')->with('success', 'Invoice setting updated successfully.');
    }


    public function pos(Request $request)
    {
        $pos_setting = new Setting();
        $pos_setting->transaction_id = $request->transaction_id;
        $pos_setting->category = '3';
        $pos_setting->save();
        return redirect()->route('setting')->with('success', 'POS setting saved successfully.');
    }

    public function pos_setting_edit(Request $request)
    {
        $pos_setting = Setting::where('category', '3')->first();
        $pos_setting->transaction_id = $request->transaction_id;
        $pos_setting->update();
        return redirect()->route('setting')->with('success', 'POS setting updated successfully.');
    }


    public function purchase_order(Request $request)
    {
        $po_setting = new Setting();
        $po_setting->transaction_id = $request->transaction_id;
        $po_setting->category = '2';
        $po_setting->save();
        return redirect()->route('setting')->with('success', 'Purchase Order setting saved successfully.');
    }

    public function purchase_order_setting_edit(Request $request)
    {
        $po_setting = Setting::where('category', '2')->first();
        $po_setting->transaction_id = $request->transaction_id;
        $po_setting->update();
        return redirect()->route('setting')->with('success', 'Purchase Order setting updated successfully.');
    }

    public function purchase_return(Request $request)
    {
        $pr_setting = new Setting();
        $pr_setting->transaction_id = $request->transaction_id;
        $pr_setting->category = '4';
        $pr_setting->save();
        return redirect()->route('setting')->with('success', 'Purchase Return setting saved successfully.');
    }

    public function purchase_return_setting_edit(Request $request)
    {
        $pr_setting = Setting::where('category', '4')->first();
        $pr_setting->transaction_id = $request->transaction_id;
        $pr_setting->update();
        return redirect()->route('setting')->with('success', 'Purchase Return setting updated successfully.');
    }

    public function sale_return_invoice(Request $request)
    {
        $pr_setting = new Setting();
        $pr_setting->transaction_id = $request->transaction_id;
        $pr_setting->category = '5';
        $pr_setting->save();
        return redirect()->route('setting')->with('success', 'Sale Return setting saved successfully.');
    }

    public function sale_return_invoice_setting_edit(Request $request)
    {
        $pr_setting = Setting::where('category', '5')->first();
        $pr_setting->transaction_id = $request->transaction_id;
        $pr_setting->update();
        return redirect()->route('setting')->with('success', 'Sale Return setting updated successfully.');
    }


    public function sale_return_pos(Request $request)
    {
        $pr_setting = new Setting();
        $pr_setting->transaction_id = $request->transaction_id;
        $pr_setting->category = '6';
        $pr_setting->save();
        return redirect()->route('setting')->with('success', 'Sale Return setting saved successfully.');
    }

    public function sale_return_pos_setting_edit(Request $request)
    {
        $pr_setting = Setting::where('category', '6')->first();
        $pr_setting->transaction_id = $request->transaction_id;
        $pr_setting->update();
        return redirect()->route('setting')->with('success', 'Sale Return setting updated successfully.');
    }
}
