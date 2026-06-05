<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Estudiante; // Asegúrate de importar el modelo Estudiante
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Registro de estudiante - SOLO email y contraseña
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:usuario,correo',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            $user = User::create([
                'correo' => $request->email,
                'contraseña' => Hash::make($request->password),
                'rol' => 'estudiante',
            ]);

            Auth::loginUsingId($user->id);
            
            // Guardar datos en sesión
            session([
                'MM_Username' => $user->correo,
                'MM_UserGroup' => $user->rol,
                'user_id' => $user->id,
                'user_nombre' => $user->nombre ?? $user->correo,
                'user_rol' => $user->rol
            ]);

            // Verificar si ya tiene perfil y plan activo
            $estudiante = Estudiante::where('usuario', $user->id)->first();
            $tienePlanActivo = $estudiante && $estudiante->plan_activo;
            
            // Determinar la ruta de redirección
            if ($tienePlanActivo) {
                $redirectRoute = route('estudiante.clases-premium');
            } else {
                $redirectRoute = route('estudiante.dashboard');
            }

            return response()->json([
                'success' => true,
                'message' => '¡Registro exitoso!',
                'redirect' => $redirectRoute
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Buscar usuario por correo
        $user = User::where('correo', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '❌ El correo no está registrado'
            ], 401);
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $user->contraseña)) {
            return response()->json([
                'success' => false,
                'message' => '❌ Contraseña incorrecta'
            ], 401);
        }

        // Iniciar sesión con Laravel Auth
        Auth::login($user);
        
        // Guardar datos adicionales en sesión para compatibilidad con código legacy
        session([
            'MM_Username' => $user->correo,
            'MM_UserGroup' => $user->rol,
            'user_id' => $user->id,
            'user_nombre' => $user->nombre ?? $user->correo,
            'user_rol' => $user->rol
        ]);

        // Si es administrador
        if ($user->rol === 'Administrador') {
            return response()->json([
                'success' => true,
                'message' => '✅ Inicio de sesión exitoso',
                'redirect' => route('admin.dashboard')
            ]);
        }

        // ========== PARA ESTUDIANTES - VERIFICAR SI TIENE PLAN ACTIVO ==========
        $estudiante = Estudiante::where('usuario', $user->id)->first();
        $tienePlanActivo = $estudiante && $estudiante->plan_activo;
        
        // Determinar la ruta de redirección
        if ($tienePlanActivo) {
            $redirectRoute = route('estudiante.clases-premium');
        } else {
            $redirectRoute = route('estudiante.dashboard');
        }

        return response()->json([
            'success' => true,
            'message' => '✅ Inicio de sesión exitoso',
            'redirect' => $redirectRoute
        ]);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        session()->forget(['MM_Username', 'MM_UserGroup', 'user_id', 'user_nombre', 'user_rol']);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }
}