<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan form ubah profil.
     */
    public function edit(Request $request): View
    {
        if ($request->user()->type == 1) {
            abort(403, 'Admin tidak diizinkan mengedit profil.');
        }

        return view('edit-profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profil user.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->type == 1) {
            abort(403, 'Admin tidak diizinkan mengubah profil.');
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        $emailChanged = $user->email !== $validated['email'];

        // Update nama dan email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Upload foto jika ada
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        // Reset email verifikasi jika email diubah
        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        // Ganti password jika diisi
        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }

            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        // Autologin ulang jika email/password berubah
        if ($emailChanged || !empty($validated['new_password'])) {
            Auth::login($user);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->type == 1) {
            abort(403, 'Admin tidak diizinkan menghapus akun.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
