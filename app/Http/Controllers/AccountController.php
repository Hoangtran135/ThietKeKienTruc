<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function loginForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }

        return view('frontend.account.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->onlyInput('email');
    }

    public function registerForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }

        return view('frontend.account.register');
    }

    public function register(RegisterRequest $request)
    {
        Customer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone ?? '',
            'address'  => $request->address ?? '',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('account.login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
