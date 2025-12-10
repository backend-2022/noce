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
            return redirect()->route('dashboard.welcome');
        }

        return view('dashboard.auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        // Generic error message for security (don't reveal if email exists)
        $genericErrorMessage = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';

        // Try to authenticate first
        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $admin = Auth::guard('admin')->user();

            // Check if account is active after successful authentication
            if (!$admin->is_active) {
                Auth::guard('admin')->logout();

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

            $request->session()->regenerate();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'مرحباً بك ' . $admin->name,
                    'redirect' => route('dashboard.welcome')
                ]);
            }

            return redirect()->intended(route('dashboard.welcome'))
                ->with('success', 'مرحباً بك ' . $admin->name);
        }

        // Authentication failed - return generic message (don't reveal if email exists or password is wrong)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $genericErrorMessage,
                'errors' => []
            ], 422);
        }

        return redirect()->back()
            ->withErrors(['email' => $genericErrorMessage])
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
