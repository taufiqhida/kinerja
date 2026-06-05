<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/export/hasil-penilaian/{pegawaiId?}', [ExportController::class, 'exportHasilPenilaian'])
        ->name('export.hasil-penilaian');
});

// Default login route redirect to prevent RouteNotFoundException
Route::redirect('/login', '/pegawai/login')->name('login');

// Auto-login route for testing
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

