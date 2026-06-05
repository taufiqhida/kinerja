<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = auth()->user();

        if ($user) {
            // Redirect langsung ke panel sesuai role, tanpa intended()
            // agar tidak terjebak redirect ke /admin
            $url = match ($user->role) {
                'admin' => '/admin',
                'kepala' => '/kepala',
                'pegawai' => '/pegawai',
                default => '/admin',
            };

            return redirect()->to($url);
        }

        return redirect()->to('/admin/login');
    }
}
