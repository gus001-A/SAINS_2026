<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use App\Models\Estudiante;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    const PRECIO_CURSO = 2499; // $2,499 MXN
    
    /**
     * Mostrar formulario de checkout
     */
    public function index()
    {
        $estudiante = auth()->user()->estudiante;
        
        // Obtener precios base
        $precioOriginal = self::PRECIO_CURSO;
        $precioFinal = self::PRECIO_CURSO;
        $montoDescuento = 0;
        $cuponAplicado = session('cupon_aplicado');
        
        // Si hay un cupón en sesión, calcular descuento
        if ($cuponAplicado) {
            $cupon = Cupon::where('codigo', $cuponAplicado)->first();
            if ($cupon && $cupon->isValid()) {
                if ($cupon->tipo_descuento === 'porcentaje') {
                    $montoDescuento = ($precioOriginal * $cupon->valor_descuento) / 100;
                } else {
                    $montoDescuento = $cupon->valor_descuento;
                }
                $precioFinal = max(0, $precioOriginal - $montoDescuento);
            } else {
                // Si el cupón ya no es válido, limpiar sesión
                session()->forget('cupon_aplicado');
                $cuponAplicado = null;
            }
        }
        
        $precios = [
            'precio_original' => $precioOriginal,
            'precio_final' => round($precioFinal, 2),
            'monto_descuento' => round($montoDescuento, 2),
            'tiene_descuento' => $montoDescuento > 0,
            'cupon_aplicado' => $cuponAplicado
        ];
        
        return view('estudiante.checkout', compact('estudiante', 'precios'));
    }
    
    /**
     * Validar y aplicar cupón vía AJAX
     */
    public function aplicarCupon(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50'
        ]);
        
        $codigo = strtoupper($request->codigo);
        $cupon = Cupon::where('codigo', $codigo)->first();
        
        // Validaciones
        if (!$cupon) {
            return response()->json([
                'success' => false,
                'message' => 'El cupón no existe'
            ]);
        }
        
        if ($cupon->usado) {
            return response()->json([
                'success' => false,
                'message' => 'Este cupón ya ha sido utilizado'
            ]);
        }
        
        if ($cupon->fecha_expiracion && Carbon::now()->greaterThan($cupon->fecha_expiracion)) {
            return response()->json([
                'success' => false,
                'message' => 'Este cupón ha expirado'
            ]);
        }
        
        if ($cupon->estatus !== 'activo') {
            return response()->json([
                'success' => false,
                'message' => 'Este cupón no está activo'
            ]);
        }
        
        // Calcular descuento
        $precioOriginal = self::PRECIO_CURSO;
        
        if ($cupon->tipo_descuento === 'porcentaje') {
            $montoDescuento = ($precioOriginal * $cupon->valor_descuento) / 100;
            $precioFinal = $precioOriginal - $montoDescuento;
            $mensaje = "¡Cupón aplicado! {$cupon->valor_descuento}% de descuento";
        } else {
            $montoDescuento = $cupon->valor_descuento;
            $precioFinal = max(0, $precioOriginal - $montoDescuento);
            $mensaje = "¡Cupón aplicado! $" . number_format($cupon->valor_descuento, 2) . " de descuento";
        }
        
        // Guardar cupón en sesión
        session(['cupon_aplicado' => $codigo]);
        
        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'precio_original' => $precioOriginal,
            'monto_descuento' => round($montoDescuento, 2),
            'precio_final' => round($precioFinal, 2),
            'tipo_descuento' => $cupon->tipo_descuento,
            'valor_descuento' => $cupon->valor_descuento
        ]);
    }
    
    /**
     * Eliminar cupón aplicado
     */
    public function eliminarCupon()
    {
        session()->forget('cupon_aplicado');
        
        return response()->json([
            'success' => true,
            'message' => 'Cupón eliminado',
            'precio_original' => self::PRECIO_CURSO,
            'precio_final' => self::PRECIO_CURSO,
            'monto_descuento' => 0
        ]);
    }
    
    /**
     * Procesar el pago
     */
    public function procesarPago(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:transferencia,oxxo,tarjeta'
        ]);
        
        $estudiante = auth()->user()->estudiante;
        $codigoCupon = session('cupon_aplicado');
        
        // Calcular precios
        $precioOriginal = self::PRECIO_CURSO;
        $montoDescuento = 0;
        $precioFinal = $precioOriginal;
        $tipoDescuento = null;
        $valorDescuento = null;
        
        if ($codigoCupon) {
            $cupon = Cupon::where('codigo', $codigoCupon)->first();
            if ($cupon && $cupon->isValid()) {
                if ($cupon->tipo_descuento === 'porcentaje') {
                    $montoDescuento = ($precioOriginal * $cupon->valor_descuento) / 100;
                    $tipoDescuento = 'porcentaje';
                    $valorDescuento = $cupon->valor_descuento;
                } else {
                    $montoDescuento = $cupon->valor_descuento;
                    $tipoDescuento = 'fijo';
                    $valorDescuento = $cupon->valor_descuento;
                }
                $precioFinal = max(0, $precioOriginal - $montoDescuento);
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Generar referencia de pago
            $referencia = $this->generarReferencia($request->metodo_pago, $estudiante->id);
            
            // Crear registro de pago
            $pago = Pago::create([
                'alumno_pago' => $estudiante->id,
                'tipo_pago' => $request->metodo_pago,
                'monto_pago' => round($precioFinal, 2),
                'estatus' => 'pendiente',
                'referencia_pago' => $referencia,
                'fecha_pago' => now(),
                'nota_usuario' => $codigoCupon ? "Cupón aplicado: $codigoCupon ({$tipoDescuento}: {$valorDescuento})" : null
            ]);
            
            // Si se aplicó un cupón, marcarlo como usado
            if ($codigoCupon) {
                $cupon = Cupon::where('codigo', $codigoCupon)->first();
                if ($cupon) {
                    $cupon->update([
                        'usado' => true,
                        'usuario_uso' => $estudiante->usuario_id ?? $estudiante->usuario,
                        'fecha_uso' => now()
                    ]);
                    
                    // Actualizar campo cupon en estudiante
                    $estudiante->update(['cupon' => $codigoCupon]);
                }
            }
            
            DB::commit();
            
            // Limpiar sesión del cupón
            session()->forget('cupon_aplicado');
            
            return redirect()->route('checkout.exito', $pago->id)
                ->with('success', 'Pago registrado correctamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar pago: ' . $e->getMessage());
            
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }
    
    /**
     * Página de éxito después del pago
     */
    public function exito($pagoId)
    {
        $pago = Pago::with('alumno')->findOrFail($pagoId);
        
        return view('estudiante.pago-exito', compact('pago'));
    }
    
    /**
     * Generar referencia única de pago
     */
    private function generarReferencia($metodo, $estudianteId)
    {
        $prefix = match($metodo) {
            'transferencia' => 'TRA',
            'oxxo' => 'OXX',
            'tarjeta' => 'TAR',
            default => 'PAG'
        };
        
        return $prefix . date('Ymd') . str_pad($estudianteId, 6, '0', STR_PAD_LEFT) . rand(100, 999);
    }
}