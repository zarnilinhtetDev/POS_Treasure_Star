<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function config()
    {
        $user_profile = UserProfile::find(1);
        return view('Configuration.user_profile', compact('user_profile'));
    }


    public function config_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logos' => 'nullable|file|image|max:2048', // Corrected name here
            'address' => 'required|string',
            'phno1' => 'required|string|max:15',
            'phno2' => 'nullable|string|max:15',
            'email' => 'required|email',
        ]);

        $profile = new UserProfile();
        $profile->name = $request->input('name');
        $profile->address = $request->input('address');
        $profile->description = $request->input('description');
        $profile->phno1 = $request->input('phno1');
        $profile->phno2 = $request->input('phno2');
        $profile->email = $request->input('email');
        $profile->address = $request->input('address');
        $logo = $request->file('logos'); // Corrected name here
        if ($logo) {
            $imagename = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('logos'), $imagename); // Ensure the path is correct and accessible
            $profile->logos = $imagename; // Corrected attribute name here
        }

        $profile->save();

        return redirect()->back()->with('success', 'Configuration Successfully');
    }

    public function config_edit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logos' => 'nullable|file|image|max:2048',
            'address' => 'required|string',
            'phno1' => 'required|string|max:15',
            'phno2' => 'nullable|string|max:15',
            'email' => 'required|email',
        ]);

        $profile = UserProfile::find(1);
        $profile->name = $request->input('name');
        $profile->address = $request->input('address');
        $profile->description = $request->input('description');
        $profile->phno1 = $request->input('phno1');
        $profile->phno2 = $request->input('phno2');
        $profile->email = $request->input('email');
        $profile->address = $request->input('address');
        $logo = $request->file('logos');
        if ($logo) {
            $imagename = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('logos'), $imagename);
            $profile->logos = $imagename;
        }

        $profile->update();

        return redirect()->back()->with('success', 'Configuration Successfully');
    }
}
