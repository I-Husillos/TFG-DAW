<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    // Muestra el formulario con el token del email.
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.password.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Procesa el restablecimiento de contraseña.
    // Password::reset() valida el token contra
    // password_reset_tokens, actualiza la contraseña
    // y elimina el token usado para que no pueda
    // reutilizarse.
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Dispara el evento PasswordReset para que
                // Laravel pueda invalidar sesiones anteriores
                // si está configurado para ello.
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}