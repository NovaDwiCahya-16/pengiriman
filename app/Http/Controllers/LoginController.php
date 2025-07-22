<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // ✅ Tampilkan halaman login
    public function showLogin()
    {
        return view('login');
    }

    // ✅ Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (auth()->user()->type == 1) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, Kepala Gudang (Admin)!');
            } else {
                return redirect()->route('user.dashboard')->with('success', 'Selamat datang, Kepala Cabang!');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // ✅ Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    // ✅ Tampilkan halaman register
    public function showRegister()
    {
        return view('register');
    }

    // ✅ Proses register (tanpa login otomatis)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload Foto Profil jika ada
        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Role otomatis: admin jika email = admin@gmail.com
        $type = strtolower($request->email) === 'admin@gmail.com' ? 1 : 0;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo' => $photoPath,
            'type' => $type,
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login terlebih dahulu.');
    }
}
