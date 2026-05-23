<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar por google_id o por correo
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('correo', $googleUser->getEmail())
                        ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
            } else {
                $user = User::create([
                    'correo'     => $googleUser->getEmail(),
                    'google_id'  => $googleUser->getId(),
                    'contraseña' => bcrypt(uniqid()),
                    'rol'        => 'estudiante',
                ]);
            }

            Auth::login($user);

            // ⭐ Redirigir según el rol
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isEstudiante()) {
                return redirect()->route('estudiante.dashboard');
            }

            return redirect('/');

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Error al iniciar sesión: ' . $e->getMessage());
        }
    }
}