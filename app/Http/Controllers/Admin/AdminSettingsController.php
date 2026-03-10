<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
         // Validation
        $request->validate([
            'email' => ['required', 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/'],
            'contact' => 'required|digits:10',
            'opening_hours' => 'required',
            'address' => 'required'
        ], [
            'email.regex' => 'Please enter a valid email address with a proper domain (e.g., name@gmail.com).',
            'contact.digits' => 'Contact number must be exactly 10 digits.'
        ]);

        $settings = Setting::first();

        if(!$settings){
            $settings = new Setting();
        }

        $data = $request->only([
            'email',
            'contact',
            'address',
            'opening_hours'
        ]);

      if($request->hasFile('logo')){

        // delete old logo
        if($settings->logo && file_exists(public_path($settings->logo))){
            unlink(public_path($settings->logo));
        }

        $logo = $request->file('logo');

        $filename = 'logo_'.time().'.'.$logo->getClientOriginalExtension();

        $logo->move(public_path('images/logo'), $filename);

        $data['logo'] = 'images/logo/' .$filename;
    }
        $settings->fill($data);
        $settings->save();

        return back()->with('success','Settings updated successfully');
    }

    public function changePassword(Request $request)
    {
       /* $request->validate([
            'password' => 'required|confirmed|min:6'
        ]);

        $admin = auth()->user();

        $admin->password = bcrypt($request->password);
        $admin->save();

        return back()->with('success','Password changed successfully!'); */

        $request->validate([
            'password' => 'required|confirmed|min:6'
          ], [
            'password.confirmed' => 'New password and confirm password must match.',
            'password.min' => 'Password must be at least 6 characters.'
        ]);

        //$admin = Admin::firstorFail();   // get admin record
        $admin = auth()->guard('admin')->user();

        $admin->password = Hash::make($request->password);

        $admin->save();

        return back()->with('success','Password changed successfully!');
     }

    /*public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $admin = auth()->user();

        $admin->email = $request->email;
        $admin->save();

        return back()->with('success', 'Admin email updated successfully!');
    }*/
        public function changeEmail(Request $request)
        {
            $request->validate([
                'email' => 'required|email'
                ], [
                'email.email' => 'Please enter a valid email address.'
            ]);

            $admin = auth()->guard('admin')->user();

            $admin->email = $request->email;
            $admin->save();

            return back()->with('success','Admin email updated successfully!');
        }
}
