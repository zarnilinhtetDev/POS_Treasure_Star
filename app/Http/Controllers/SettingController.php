<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        Setting::create($request->all());

        return redirect(url('setting'))->with('success', ucfirst($request->category) . ' setting  has been created!');
    }
    public function delete(Request $request)
    {
        Setting::find($request->id)->delete();

        return redirect(url('setting'))->with('success', 'Setting has been deleted!');
    }
    public function edit(Request $request, $id)
    {

        $setting = Setting::find($id);

        $transactions = Transaction::latest()->get();
        return view('setting.setting_edit', compact('setting', 'transactions'));
    }
    public function update(Request $request, $id)
    {

        $setting = Setting::find($id);
        $setting->update($request->all());

        return redirect(url('setting'))->with('success', ucfirst($request->category) . ' setting  has been updated!');
    }
    public function index()
    {

        $transactions = Transaction::latest()->get();

        $settings = Setting::latest()->get();
        $transactions = Transaction::latest()->get();
        return view('setting.setting', compact('settings', 'transactions'));
    }


    // public function invoice(Request $request)
    // {
    //     $invoice_setting = new Setting();
    //     $invoice_setting->transaction_id = $request->transaction_id;
    //     $invoice_setting->category = '1';
    //     $invoice_setting->save();
    //     return redirect()->route('setting')->with('success', 'Invoice setting saved successfully.');
    // }

    // public function invoice_setting_edit(Request $request)
    // {
    //     $invoice_setting = Setting::where('category', '1')->first();
    //     $invoice_setting->transaction_id = $request->transaction_id;
    //     $invoice_setting->update();
    //     return redirect()->route('setting')->with('success', 'Invoice setting updated successfully.');
    // }

    // public function invoice_setting_delete()
    // {
    //     $invoice_setting = Setting::where('category', '1')->first();
    //     $invoice_setting->delete();
    //     return redirect()->route('setting')->with('success', 'Invoice setting deleted successfully.');
    // }


    // public function pos(Request $request)
    // {
    //     $pos_setting = new Setting();
    //     $pos_setting->transaction_id = $request->transaction_id;
    //     $pos_setting->category = '3';
    //     $pos_setting->save();
    //     return redirect()->route('setting')->with('success', 'POS setting saved successfully.');
    // }

    // public function pos_setting_edit(Request $request)
    // {
    //     $pos_setting = Setting::where('category', '3')->first();
    //     $pos_setting->transaction_id = $request->transaction_id;
    //     $pos_setting->update();
    //     return redirect()->route('setting')->with('success', 'POS setting updated successfully.');
    // }

    // public function pos_setting_delete()
    // {
    //     $pos_setting = Setting::where('category', '3')->first();
    //     $pos_setting->delete();
    //     return redirect()->route('setting')->with('success', 'POS setting deleted successfully.');
    // }


    // public function purchase_order(Request $request)
    // {
    //     $po_setting = new Setting();
    //     $po_setting->transaction_id = $request->transaction_id;
    //     $po_setting->category = '2';
    //     $po_setting->save();
    //     return redirect()->route('setting')->with('success', 'Purchase Order setting saved successfully.');
    // }

    // public function purchase_order_setting_edit(Request $request)
    // {
    //     $po_setting = Setting::where('category', '2')->first();
    //     $po_setting->transaction_id = $request->transaction_id;
    //     $po_setting->update();
    //     return redirect()->route('setting')->with('success', 'Purchase Order setting updated successfully.');
    // }

    // public function purchase_order_setting_delete()
    // {
    //     $po_setting = Setting::where('category', '2')->first();
    //     $po_setting->delete();
    //     return redirect()->route('setting')->with('success', 'Purchase Order setting deleted successfully.');
    // }

    // public function purchase_return(Request $request)
    // {
    //     $pr_setting = new Setting();
    //     $pr_setting->transaction_id = $request->transaction_id;
    //     $pr_setting->category = '4';
    //     $pr_setting->save();
    //     return redirect()->route('setting')->with('success', 'Purchase Return setting saved successfully.');
    // }

    // public function purchase_return_setting_edit(Request $request)
    // {
    //     $pr_setting = Setting::where('category', '4')->first();
    //     $pr_setting->transaction_id = $request->transaction_id;
    //     $pr_setting->update();
    //     return redirect()->route('setting')->with('success', 'Purchase Return setting updated successfully.');
    // }

    // public function purchase_return_setting_delete()
    // {
    //     $pr_setting = Setting::where('category', '4')->first();
    //     $pr_setting->delete();
    //     return redirect()->route('setting')->with('success', 'Purchase Return setting deleted successfully.');
    // }

    // public function sale_return_invoice(Request $request)
    // {
    //     $pr_setting = new Setting();
    //     $pr_setting->transaction_id = $request->transaction_id;
    //     $pr_setting->category = '5';
    //     $pr_setting->save();
    //     return redirect()->route('setting')->with('success', 'Sale Return setting saved successfully.');
    // }

    // public function sale_return_invoice_setting_edit(Request $request)
    // {
    //     $pr_setting = Setting::where('category', '5')->first();
    //     $pr_setting->transaction_id = $request->transaction_id;
    //     $pr_setting->update();
    //     return redirect()->route('setting')->with('success', 'Sale Return setting updated successfully.');
    // }

    // public function sale_return_invoice_setting_delete()
    // {
    //     $pr_setting = Setting::where('category', '5')->first();
    //     $pr_setting->delete();
    //     return redirect()->route('setting')->with('success', 'Sale Return setting deleted successfully.');
    // }


    // public function sale_return_pos(Request $request)
    // {
    //     $pr_setting = new Setting();
    //     $pr_setting->transaction_id = $request->transaction_id;
    //     $pr_setting->category = '6';
    //     $pr_setting->save();
    //     return redirect()->route('setting')->with('success', 'Sale Return setting saved successfully.');
    // }

    // public function sale_return_pos_setting_edit(Request $request)
    // {
    //     $pr_setting = Setting::where('category', '6')->first();
    //     $pr_setting->transaction_id = $request->transaction_id;
    //     $pr_setting->update();
    //     return redirect()->route('setting')->with('success', 'Sale Return setting updated successfully.');
    // }

    // public function sale_return_pos_setting_delete()
    // {
    //     $pr_setting = Setting::where('category', '6')->first();
    //     $pr_setting->delete();
    //     return redirect()->route('setting')->with('success', 'Sale Return setting deleted successfully.');
    // }
}
