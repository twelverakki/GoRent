<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUnpaidFines
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */

        $user = Auth::user();

        // Cari apakah user punya rental yang punya denda status 'unpaid'
        // Kita pakai whereHas untuk query ke relasi: User -> Rentals -> Fine
        $hasFine = $user->rentals()
            ->whereHas('fine', function ($query) {
                $query->where('status', 'unpaid');
            })
            ->exists();

        if ($hasFine) {
            // Kalau punya utang, lempar ke halaman daftar denda
            // with() mengirim pesan error session (Flash Message)
            return redirect()->route('fines.index')
                ->with('error', 'Anda memiliki denda yang belum dibayar. Lunasi dulu sebelum menyewa lagi.');
        }

        return $next($request);
    }
}