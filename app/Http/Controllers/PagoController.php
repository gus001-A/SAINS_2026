<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'tipo_pago' => 'required|in:oxxo,banamex,paypal',
            'referencia_pago' => 'required|string|max:100',
            'monto_pago' => 'required|numeric|min:1',
            'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'nota_usuario' => 'nullable|string|max:500'
        ]);
        
        try {
            $estudiante = Estudiante::where('usuario', Auth::id())->first();
            
            // Guardar comprobante
            $comprobantePath = $request->file('comprobante')->store('comprobantes', 'public');
            
            Pago::create([
                'tipo_pago' => $request->tipo_pago,
                'alumno_pago' => $estudiante->id,
                'fecha_pago' => now(),
                'monto_pago' => $request->monto_pago,
                'estatus' => 'pendiente',
                'referencia_pago' => $request->referencia_pago,
                'usuario_revision' => null,
                'fecha_aprueba' => null,
                'nota_usuario' => $request->nota_usuario,
                'comprobante' => $comprobantePath
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pago: ' . $e->getMessage()
            ], 500);
        }
    }
}