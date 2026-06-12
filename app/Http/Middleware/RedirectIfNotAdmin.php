<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    /**
     * Jika user sudah login tapi bukan admin, redirect ke panel sesuai role-nya.
     * Kecuali di halaman login admin (agar semua role bisa login lewat situ).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Jika user mengunjungi halaman login admin
        if ($request->is('admin/login')) {
            if ($user) {
                if ($user->role === 'admin') {
                    return redirect()->to('/admin');
                } else {
                    auth()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->to('/admin/login');
                }
            }
            return $next($request);
        }


        // Jika user sudah login tapi bukan admin, redirect ke panel mereka
        if ($user && $user->role !== 'admin') {
            $url = match ($user->role) {
                'kepala' => '/kepala',
                'pegawai' => '/pegawai',
                default => '/',
            };

            return redirect()->to($url);
        }

        return $next($request);
    }
}
