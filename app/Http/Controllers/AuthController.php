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
        // Validar código de referido obligatorio
        if (!$request->has('referral_code') || empty($request->referral_code)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Para registrarte en nuestro sitio necesitas el link de alguien que ya participe en nuestro sistema'
                ], 422);
            }
            return back()->withErrors(['referral_code' => 'Código de referido obligatorio'])->withInput();
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'whatsapp' => 'required|string|max:20',
            'country' => 'required|string|max:2',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'referral_code' => 'required|exists:users,referral_code',
            'avatar' => 'nullable|url'
        ]);
        
        $referrer = User::where('referral_code', $request->referral_code)->first();
        
        if (!$referrer) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código de referido inválido'
                ], 422);
            }
            return back()->withErrors(['referral_code' => 'Código de referido inválido'])->withInput();
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'whatsapp' => $request->whatsapp,
            'avatar' => $request->avatar,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'referral_code' => strtoupper(Str::random(8)),
            'referred_by' => $referrer->id,
            'is_active' => true
        ]);
        
        Auth::login($user);
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => route('dashboard')]);
        }
        return redirect()->route('dashboard');
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}