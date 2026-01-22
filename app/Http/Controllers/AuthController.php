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
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
        
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('dashboard')
                ]);
            }
            return redirect()->intended(route('dashboard'));
        }
        
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 422);
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