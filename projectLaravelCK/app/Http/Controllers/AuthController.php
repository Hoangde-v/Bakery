<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

            return redirect()->route(
                $user->role === 'admin' ? 'admin.dashboard' : 'customer.products.index'
            );
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return redirect()->intended(
                $user && $user->role === 'admin'
                    ? route('admin.dashboard')
                    : route('customer.products.index')
            );
        }

        throw ValidationException::withMessages([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ]);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route(
                Auth::user()->role === 'admin'
                    ? 'admin.dashboard'
                    : 'customer.products.index'
            );
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'user', 
        ]);

        Auth::login($user);

        return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'customer.products.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        if ($user->orders()->exists()) {
            return back()->withErrors([
                'account' => 'Không thể xóa tài khoản đã có đơn hàng.',
            ]);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Tài khoản đã được xóa thành công.');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Mật khẩu hiện tại không đúng.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $redirectRoute = $user->role === 'admin' ? 'admin.products.index' : 'customer.products.index';

        return redirect()->route($redirectRoute)->with('status', 'Mật khẩu đã được đổi thành công.');
    }
}
