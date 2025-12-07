<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

// Hapus use untuk Hash/Rule karena create/update sudah dibuang
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna. (READ/INDEX)
     */
    public function index()
    {
        $users = User::latest()->paginate(15);
        // Mengarahkan ke view admin/users/index.blade.php
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        // View ini akan mencari resources/views/admin/users/edit.blade.php
        return view('admin.users.edit', compact('user'));
    }

    // ==========================================
    // 💡 METODE UPDATE (Menyimpan Perubahan)
    // ==========================================
    /**
     * Memperbarui pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Rule::unique diabaikan untuk user yang sedang diedit
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed', // Password opsional saat update
            'role' => ['required', 'string', Rule::in(['admin', 'customer'])],
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }
    // Metode CREATE, STORE DIHAPUS.

    /**
     * Menghapus pengguna dari database. (DELETE/DESTROY)
     */
    public function destroy(User $user)
    {
        // Pencegahan sederhana: Jangan biarkan admin menghapus akunnya sendiri
        if (auth()->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}