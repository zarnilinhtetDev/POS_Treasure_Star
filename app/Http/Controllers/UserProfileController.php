<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{

    public function index()
    {


        $warehousePermission = auth()->user()->level ? json_decode(auth()->user()->level) : [];

        if (auth()->user()->is_admin == '1') {
            $user_profiles = UserProfile::latest()->get();
            $branchs = Warehouse::all();
        } else {
            $user_profiles = UserProfile::whereIn('branch', $warehousePermission)->latest()->get();
            $branchs = Warehouse::all();
        }

        return view('Configuration.user_profile_manage', compact('user_profiles', 'branchs'));
    }
    public function config()
    {
        $branchs = Warehouse::all();
        return view('Configuration.user_profile', compact('branchs'));
    }


    public function config_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logos' => 'nullable|file|image|max:2048',
            'address' => 'required|string',
            'phno1' => 'required|string|max:15',
            'phno2' => 'nullable|string|max:15',
            'email' => 'required|email',
            'branch' => 'required',
        ]);

        $profile = new UserProfile();
        $profile->name = $request->input('name');
        $profile->address = $request->input('address');
        $profile->description = $request->input('description');
        $profile->phno1 = $request->input('phno1');
        $profile->phno2 = $request->input('phno2');
        $profile->email = $request->input('email');
        $profile->address = $request->input('address');
        $profile->branch = $request->input('branch');
        $logo = $request->file('logos');
        if ($logo) {
            $imagename = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('logos'), $imagename);
            $profile->logos = $imagename;
        }

        $profile->save();

        return redirect(url('config_manage'))->with('success', 'Configuration Successfully');
    }

    public function edit($id)
    {
        $user_profile = UserProfile::find($id);
        $branchs = Warehouse::all();
        return view('Configuration.user_profile_edit', compact('user_profile', 'branchs'));
    }

    public function config_edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logos' => 'nullable|file|image|max:2048',
            'address' => 'required|string',
            'phno1' => 'required|string|max:15',
            'phno2' => 'nullable|string|max:15',
            'email' => 'required|email',
            'branch' => 'required',
        ]);

        $profile = UserProfile::find($id);
        $profile->name = $request->input('name');
        $profile->address = $request->input('address');
        $profile->description = $request->input('description');
        $profile->phno1 = $request->input('phno1');
        $profile->phno2 = $request->input('phno2');
        $profile->email = $request->input('email');
        $profile->branch = $request->input('branch');

        $oldLogo = $profile->logos;
        if ($request->hasFile('logos')) {
            $logo = $request->file('logos');
            $imagename = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('logos'), $imagename);
            $profile->logos = $imagename;

            if ($oldLogo && $oldLogo !== $imagename) {
                $oldImagePath = public_path('logos') . '/' . $oldLogo;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }

        $profile->update();

        return redirect(url('config_manage'))->with('success', 'Configuration Update Successfully');
    }


    public function details($id)
    {
        $user_profile = UserProfile::find($id);
        return view('Configuration.user_profile_details', compact('user_profile'));
    }

    public function delete($id)
    {
        $user_profile = UserProfile::find($id);

        if ($user_profile->logos) {
            $logoPath = public_path('logos') . '/' . $user_profile->logos;
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }
        }

        $user_profile->delete();

        return redirect()->back()->with('delete', 'Configuration Delete Successfully');
    }
}
