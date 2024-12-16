<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    public function accountManagement($branch = null)
    {
        // $accounts = Account::latest()->get();
        if (auth()->user()->is_admin == '1' || auth()->user()->level == 'Admin') {
            if ($branch) {
                $accounts = Account::where(
                    'location',
                    $branch
                )->get();
            } else {
                $accounts = Account::latest()->get();
            }
        } else {
            $accounts = Account::where('location', auth()->user()->level)->get();
        }

        $branches = Warehouse::latest()->get();
        $branch_drop = Warehouse::all();
        $branchNames = $branch_drop->pluck('name', 'id');

        $currentBranchName = $branch ? $branchNames[$branch] : 'All Accounts';
        return view('finance.account.accountManagement', compact('accounts', 'branches', 'branch_drop', 'currentBranchName'));
    }

    public function account_register(Request $request)
    {
        Account::create($request->all());
        return redirect()->back()->with('success', 'Account Created Successful!');
    }

    public function account_edit($id)
    {
        $account = Account::find($id);
        $branches = Warehouse::all();
        $accounts = Account::all();
        return view('finance.account.accountManagementEdit', compact('account', 'branches', 'accounts'));
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
