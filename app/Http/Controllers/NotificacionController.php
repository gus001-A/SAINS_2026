<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Página completa de notificaciones (estudiante o admin).
     */
    public function index()
    {
        $user = Auth::user();
        $esAdmin = $user->isAdmin();

        $notificaciones = Notificacion::where('id_usuario', $user->id)
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->map->paraVista();

        return \Inertia\Inertia::render(
            $esAdmin ? 'Admin/Notificaciones' : 'Estudiante/Notificaciones',
            [
                'notificaciones' => $notificaciones,
                'noLeidas' => $notificaciones->where('leida', false)->count(),
            ]
        );
    }

    /**
     * Feed en JSON para la campana (últimas 12 + conteo de no leídas).
     */
    public function feed()
    {
        $user = Auth::user();

        return response()->json([
            'items' => Notificacion::where('id_usuario', $user->id)
                ->orderByDesc('created_at')
                ->limit(12)
                ->get()
                ->map->paraVista(),
            'no_leidas' => Notificacion::where('id_usuario', $user->id)->noLeidas()->count(),
        ]);
    }

    public function marcarLeida($id)
    {
        Notificacion::where('id_usuario', Auth::id())
            ->where('id', $id)
            ->whereNull('leida_at')
            ->update(['leida_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function marcarTodas()
    {
        Notificacion::where('id_usuario', Auth::id())
            ->whereNull('leida_at')
            ->update(['leida_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function eliminar($id)
    {
        Notificacion::where('id_usuario', Auth::id())->where('id', $id)->delete();

        return response()->json(['ok' => true]);
    }

    public function eliminarLeidas()
    {
        $n = Notificacion::where('id_usuario', Auth::id())
            ->whereNotNull('leida_at')
            ->delete();

        return response()->json(['ok' => true, 'eliminadas' => $n]);
    }
}
