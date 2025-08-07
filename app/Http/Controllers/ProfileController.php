<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil user yang sedang login.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        return view('profile', compact('user'));
    }

    /**
     * Mengupdate profil user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama harus diisi.',
        ]);

        // Jika email berubah, minta verifikasi password
        if ($request->email !== $user->email) {
            $request->validate([
                'current_password' => ['required', 'string'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            ], [
                'current_password.required' => 'Password saat ini harus diisi untuk mengubah email.',
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan oleh user lain.',
            ]);

            // Verifikasi password saat ini
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini tidak sesuai.'
                ])->withInput();
            }
        }

        // Simpan email asli sebelum update
        $originalEmail = $user->email;
        
        // Update data user menggunakan method update
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $message = 'Profil berhasil diperbarui!';
        if ($request->email !== $originalEmail) {
            $message .= ' Email telah diubah.';
        }

        return redirect()->route('profile')->with('success', $message);
    }
} 