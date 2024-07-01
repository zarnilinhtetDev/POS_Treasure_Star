<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Credit;
use Illuminate\Http\Request;
use App\Models\UploadCoinHistory;
use App\Models\UserType;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function user_register()
    {

        $showUser_datas =  User::latest()->get();
        $branchs = Warehouse::all();

        return view('user.user', compact('showUser_datas', 'branchs'));
    }

    public function user_store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@gmail.com$/i',
            'password' => 'required',

        ]);
        $existingUser = User::where('email', $data['email'])->first();
        // return $data['userRole'];

        if ($existingUser) {
            return redirect()->back()->with('error', 'Email address already exists.');
        } else {
            // dd($request->input('userRole', true));
            // var_dump($request->input('userRole', true));
            // return $request->userRole;
            // User::create([
            //     'name' => $data['name'],
            //     'email' => $data['email'],
            //     // 'is_admin' => $request['userRole'],
            //     // 'is_admin' => $request->userRole,
            //     'is_admin' => $request->input('userRole', true),
            //     'password' => Hash::make($data['password']),

            // ]);
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->type = $request->type;
            $user->level = $request->level;
            $user->password = Hash::make($data['password']);
            $user->save();



            return redirect()->back()->with('success', 'User registration is successful');
        }
    }

    public function delete_user($id)
    {
        $user_delete = User::find($id);
        $user_delete->delete();

        return redirect()->back()->with('delete', ' User delete is successful');
    }
    public function userShow($id)
    {

        $userShow = User::find($id);
        $branchs = Warehouse::all();

        return view('user.userEdit', compact('userShow', 'branchs'));
    }


    public function update_user(Request $request, $id)
    {

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect(url('user'))->with('success', 'User update is successful');
    }


    public function permission($id)
    {

        $userShow = User::find($id);
        $branchs = Warehouse::all();
        $userTypes = UserType::all();
        return view('user.user_permission', compact('userShow', 'branchs', 'userTypes'));
    }

    public function permissionStore($id, Request $request)
    {

        $userShow = User::find($id);

        $userShow->user_type_id = $request->user_type_id;
        $userShow->level = $request->level;
        $userShow->permission = $request->input('permission', []);
        $userShow->update();

        return redirect(url('user'))->with('success', 'User Permission Set is successful');
    }




    public function logout(Request $request)
    {
        // Your logout logic here
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
