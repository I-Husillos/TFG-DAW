<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    // Muestra el formulario para solicitar el enlace de restablecimiento de contraseña.
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Procesa el envío del formulario para solicitar el enlace de restablecimiento de contraseña.
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
