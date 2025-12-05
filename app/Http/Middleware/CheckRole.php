<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role user SESUAI dengan yang diminta
        // $role adalah parameter yang kita kirim dari Route (misal: 'admin')
        if (Auth::user()->role !== $role) {
            // Jika customer coba masuk halaman admin -> 403 Forbidden
            abort(403, 'Akses ditolak. Halaman ini khusus ' . ucfirst($role));
        }

        return $next($request);
    }
}