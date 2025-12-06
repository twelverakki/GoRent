<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
            'phone' => $request->phone, // ⚠️ Pastikan ini disimpan
            'address' => $request->address,
            'role' => 'customer', // Default role
        ]);

        event(new Registered($user));
        // ===============================================
        // 💡 NOTIFIKASI WHATSAPP UNTUK PENGGUNA BARU 💡
        // ===============================================
        $customerPhone = $user->phone;

        $message = "🥳 *Selamat Datang di GoRent, {$user->name}!* 🥳\n\n"
                 . "Akun Anda berhasil didaftarkan sebagai Customer.\n"
                 . "Anda sekarang dapat melihat katalog kami dan mulai menyewa peralatan terbaik.\n\n"
                 . "Jika ada pertanyaan, jangan ragu hubungi Admin kami.";

        // Kirim pesan sambutan
        $this->waService->sendMessage($customerPhone, $message);
        // ===============================================

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
