<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'active'
        ])) {

            $request->session()->regenerate();

            return redirect()->route('reservations.index');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or inactive account'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function forgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $admin = Admin::where('email',$request->email)->first();

        if(!$admin){
            return back()->withErrors(['email'=>'Email not found']);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email'=>$request->email],
            [
                'token'=>$token,
                'created_at'=>now()
            ]
        );

        $resetLink = route('admin.reset.password', $token);
        
        Mail::html(
            "<p>Click the button below to reset your password:</p>
            <a href='".$resetLink."' 
            style='padding:10px 20px;background:#000;color:#fff;text-decoration:none;border-radius:5px'>
            Reset Password
            </a>",
            function($message) use ($request){
                $message->to($request->email)
                        ->subject('Admin Password Reset');
            }
        );

        return back()->with('success','Reset link sent to your email');
    }

    public function resetPasswordForm($token)
    {
        return view('admin.auth.reset-password', compact('token'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|confirmed|min:6'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email',$request->email)
            ->where('token',$request->token)
            ->first();

        if(!$record){
            return back()->withErrors(['email'=>'Invalid request']);
        }

        Admin::where('email',$request->email)
            ->update([
                'password'=>Hash::make($request->password)
            ]);

        DB::table('password_reset_tokens')
            ->where('email',$request->email)
            ->delete();

        return redirect()->route('admin.login')
            ->with('success','Password updated successfully');
    }
}
