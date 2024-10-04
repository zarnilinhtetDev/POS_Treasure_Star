<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    public function accountManagement()
    {
        $accounts = Account::latest()->get();
        return view('finance.account.accountManagement', compact('accounts'));
    }

    public function account_register(Request $request)
    {
        Account::create($request->all());
        return redirect()->back()->with('success', 'Account Created Successful!');
    }

    public function account_edit($id)
    {
        $account = Account::find($id);
        return view('finance.account.accountManagementEdit', compact('account'));
    }
    public function account_update(Request $request, $id)
    {
        Account::find($id)->update($request->all());
        return redirect('accountManagement')->with('success', 'Account Updated Successful!');
    }
    public function account_delete($id)
    {
        Account::find($id)->delete();
        return redirect('accountManagement')->with('success', 'Account Deleted Successful!');
    }
}
