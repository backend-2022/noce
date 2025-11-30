<?php

namespace App\Http\Controllers\Dashboard\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Auth\AdminLoginRequest;

class AdminAuthController extends Controller
{

    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard.dashboard');
        }

        return view('dashboard.auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البريد الإلكتروني غير مسجل',
                    'errors' => ['email' => 'البريد الإلكتروني غير مسجل']
                ], 422);
            }
            return redirect()->back()
                ->withErrors(['email' => 'البريد الإلكتروني غير مسجل'])
                ->withInput($request->except('password'));
        }

        if (!$admin->is_active) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حسابك غير مفعل',
                    'errors' => ['email' => 'حسابك غير مفعل']
                ], 422);
            }
            return redirect()->back()
                ->withErrors(['email' => 'حسابك غير مفعل'])
                ->withInput($request->except('password'));
        }

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'مرحباً بك ' . $admin->name,
                    'redirect' => route('dashboard.dashboard')
                ]);
            }

            return redirect()->intended(route('dashboard.dashboard'))
                ->with('success', 'مرحباً بك ' . $admin->name);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور غير صحيحة',
                'errors' => ['password' => 'كلمة المرور غير صحيحة']
            ], 422);
        }

        return redirect()->back()
            ->withErrors(['password' => 'كلمة المرور غير صحيحة'])
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.login')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
