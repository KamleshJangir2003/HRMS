<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        Log::info('=== ADMIN LOGIN ATTEMPT ===', $request->except('password'));
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            Log::info('Admin login successful');
            
            // Log activity
            \App\Models\ActivityLog::log(
                'Logged In', 
                'Authentication', 
                'Admin logged into system'
            );
            
            return redirect()->intended('/admin/dashboard');
        }

        Log::error('Admin login failed', ['email' => $request->email]);
        return back()->withErrors([
            'email' => 'Invalid admin credentials. This is ADMIN login.',
        ]);
    }

    public function logout(Request $request)
    {
        // Log activity before logout
        \App\Models\ActivityLog::log(
            'Logged Out', 
            'Authentication', 
            'Admin logged out from system'
        );
        
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}