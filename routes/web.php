<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\TtdController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/export/hasil-penilaian/{pegawaiId?}', [ExportController::class, 'exportHasilPenilaian'])
        ->name('export.hasil-penilaian');

    Route::post('/verifikasi-ttd/{token}/konfirmasi', [TtdController::class, 'konfirmasi'])
        ->name('ttd.konfirmasi');
});

// Verifikasi QR code BOLEH diakses publik (tidak perlu login) — siapapun bisa lihat status
Route::get('/verifikasi-ttd/{token}', [TtdController::class, 'verify'])
    ->name('ttd.verify');

// Default login route redirect to prevent RouteNotFoundException
Route::redirect('/login', '/admin/login')->name('login');

// Redirect old login routes to the unified login portal
Route::redirect('/pegawai/login', '/admin/login');
Route::redirect('/kepala/login', '/admin/login');

// Safe GET logout route handlers to prevent 405 MethodNotAllowed errors
Route::get('/{panel}/logout', function (string $panel) {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->to('/admin/login');
})->where('panel', 'admin|kepala|pegawai');


// Auto-login route for testing (only in local environment)
if (app()->environment('local')) {
    Route::get('/auto-login/{role}', function (string $role) {
        $email = match ($role) {
            'admin' => 'admin@simkin.test',
            'kepala' => 'kepala1@simkin.test',
            'pegawai' => 'budi@simkin.test',
            default => abort(404),
        };
        $user = \App\Models\User::where('email', $email)->firstOrFail();
        auth()->login($user);
        return redirect('/' . ($role === 'admin' ? 'admin' : $role));
    });
}

