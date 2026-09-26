<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AuthenticateController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

     public function postLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if ($user) {

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->route('admin.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Wrong credentials.Please Check Email Or Password Once',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.auth.login');
    }
    
}

