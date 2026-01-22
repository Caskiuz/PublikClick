<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (Auth::attempt($request->only('email', 'password'))) {
            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('dashboard');
        }
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }
    
    public function showRegister()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'referral_code' => 'nullable|exists:users,referral_code'
        ]);
        
        $referrer = null;
        if ($request->referral_code) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referral_code' => strtoupper(Str::random(8)),
            'referred_by' => $referrer ? $referrer->id : null,
            'wallet_balance' => 0,
            'is_active' => true
        ]);
        
        Auth::login($user);
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('dashboard');
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}