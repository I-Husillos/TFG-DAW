<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('user_currency')) {
    /**
     * Devuelve la moneda del usuario autenticado o EUR por defecto.
     *
     * @return string
     */
    function user_currency(): string
    {
        return Auth::check() && Auth::user()->profile?->currency
            ? Auth::user()->profile->currency
            : 'EUR';
    }
}
