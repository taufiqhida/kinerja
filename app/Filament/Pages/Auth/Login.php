<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;

class Login extends BaseLogin
{
    /**
     * After successful authentication, redirect user based on their role.
     */
    public function authenticate(): ?LoginResponse
    {
        return parent::authenticate();
    }
}
