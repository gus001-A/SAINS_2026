@extends('estudiante.layouts.app')

@section('title', 'Checkout - SAINS')

@section('content')
<div style="background: linear-gradient(135deg, #f5f7fa 0%, #eef2f7 100%); min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1100px; margin: 0 auto;">

        <!-- Encabezado -->
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; color: transparent; font-weight: 800; font-size: 2.2rem; margin-bottom: 10px;">Finalizar compra</h1>
            <p style="color: #64748b; font-size: 1rem;">Curso Premium SAINS 2026</p>
        </div>

        <div style="display: flex; gap: 30px; flex-wrap: wrap;">

            <!-- COLUMNA IZQUIERDA (Formulario) -->
            <div style="flex: 1; min-width: 280px;">
                <div style="background: white; border-radius: 24px; padding: 30px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.02);">

                    <!-- Datos del estudiante (rediseñados) -->
                    <div style="margin-bottom: 32px;">
                        <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-user-circle" style="color: #667eea;"></i> Datos personales
                        </h3>
                        <div style="background: #f8fafc; border-radius: 20px; padding: 20px;">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                                <div>
                                    <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Nombre completo</span>
                                    <p style="margin: 4px 0 0; font-weight: 500; color: #0f172a;">{{ $estudiante->nombre_completo }}</p>
                                </div>
                                <div>
                                    <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Correo electrónico</span>
                                    <p style="margin: 4px 0 0; font-weight: 500; color: #0f172a;">{{ auth()->user()->correo }}</p>
                                </div>
                                <div>
                                    <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Teléfono</span>
                                    <p style="margin: 4px 0 0; font-weight: 500; color: #0f172a;">{{ $estudiante->telefono ?? 'No especificado' }}</p>
                                </div>
                                <div>
                                    <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Método de pago</span>
                                    <p style="margin: 4px 0 0; font-weight: 500; color: #0f172a;" id="metodoSeleccionado">Transferencia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cupón (igual que antes, pero con mejor estilo) -->
                    <div style="margin-bottom: 32px;">
                        <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-ticket-alt" style="color: #f59e0b;"></i> ¿Tienes un cupón?
                        </h3>

                        @if(session('error_cupon'))
                        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 16px; margin-bottom: 16px; font-size: 0.875rem;">
                            {{ session('error_cupon') }}
                        </div>
                        @endif

                        @if(session('success_cupon'))
                        <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 16px; margin-bottom: 16px; font-size: 0.875rem;">
                            {{ session('success_cupon') }}
                        </div>
                        @endif

                        <form action="{{ route('estudiante.aplicar-cupon') }}" method="POST" style="display: flex; gap: 12px;">
                            @csrf
                            <input type="text" name="codigo" placeholder="Código del cupón"
                                value="{{ session('cupon_aplicado') }}"
                                style="flex: 1; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 16px; font-size: 0.9rem; transition: all 0.2s;">
                            @if(session('cupon_aplicado'))
                            <a href="{{ route('estudiante.eliminar-cupon') }}"
                                style="background: #fee2e2; color: #dc2626; border: none; padding: 0 24px; border-radius: 16px; text-decoration: none; display: flex; align-items: center; font-weight: 500; transition: all 0.2s;">Eliminar</a>
                            @else
                            <button type="submit"
                                style="background: #667eea; color: white; border: none; padding: 0 24px; border-radius: 16px; cursor: pointer; font-weight: 500; transition: all 0.2s;">Aplicar</button>
                            @endif
                        </form>
                    </div>

                    <!-- Métodos de pago -->
                    <div>
                        <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-credit-card" style="color: #10b981;"></i> Método de pago
                        </h3>

                        <form id="formTradicional" action="{{ route('estudiante.procesar-solicitud-pago') }}" method="POST">
                            @csrf

                            <!-- Mercado Pago -->
                            <label style="display: block; border: 2px solid #e2e8f0; border-radius: 20px; margin-bottom: 16px; padding: 16px; cursor: pointer; transition: all 0.2s;" id="labelMP">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <input type="radio" name="metodo_pago" value="mercadopago" style="width: 20px; height: 20px;" id="radioMP">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #009ee3, #007bc4); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px;">
                                        <i class="fab fa-cc-mastercard"></i>
                                    </div>
                                    <div>
                                        <strong>Mercado Pago</strong>
                                        <small style="display: block; color: #64748b;">Tarjetas de crédito y débito</small>
                                    </div>
                                </div>
                            </label>

                            <!-- Transferencia -->
                            <label style="display: block; border: 2px solid #e2e8f0; border-radius: 20px; margin-bottom: 16px; padding: 16px; cursor: pointer; transition: all 0.2s;" id="labelTransferencia">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <input type="radio" name="metodo_pago" value="transferencia" style="width: 20px; height: 20px;" id="radioTransferencia" checked>
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px;">
                                        <i class="fas fa-building-columns"></i>
                                    </div>
                                    <div>
                                        <strong>Transferencia bancaria</strong>
                                        <small style="display: block; color: #64748b;">BBVA, Banorte, Santander, etc.</small>
                                    </div>
                                </div>
                            </label>

                            <!-- OXXO -->
                            <label style="display: block; border: 2px solid #e2e8f0; border-radius: 20px; margin-bottom: 16px; padding: 16px; cursor: pointer; transition: all 0.2s;" id="labelOxxo">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <input type="radio" name="metodo_pago" value="oxxo" style="width: 20px; height: 20px;" id="radioOxxo">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px;">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div>
                                        <strong>Pago en tienda</strong>
                                        <small style="display: block; color: #64748b;">OXXO, 7-Eleven, Circle K</small>
                                    </div>
                                </div>
                            </label>

                            <button type="submit" id="btnTradicional"
                                style="width: 100%; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 14px; border-radius: 50px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 24px; transition: all 0.3s;">
                                <i class="fas fa-file-invoice"></i> Generar ficha de pago
                            </button>
                        </form>

                        <button type="button" id="btnMP"
                            style="width: 100%; background: linear-gradient(135deg, #009ee3, #007bc4); color: white; border: none; padding: 14px; border-radius: 50px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 24px; transition: all 0.3s; display: none;">
                            <i class="fab fa-cc-mastercard"></i> Pagar con Mercado Pago
                        </button>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA (Resumen) -->
            <div style="width: 350px;">
                <div style="background: white; border-radius: 24px; padding: 30px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.02); position: sticky; top: 100px;">
                    <h3 style="font-size: 1.2rem; font-weight: 700; color: #1e293b; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-receipt"></i> Resumen
                    </h3>

                    <!-- Producto -->
                    <div style="display: flex; gap: 16px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0; margin-bottom: 20px;">
                        <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #667eea15, #764ba215); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 26px; color: #667eea;">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0 0 6px 0; font-size: 1rem; font-weight: 700;">Curso Premium SAINS</h4>
                            <p style="margin: 0; color: #64748b; font-size: 0.75rem;">Acceso completo • 1 año de renovación</p>
                        </div>
                    </div>

                    <!-- Precios -->
                    <div style="margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: #475569;">Precio original</span>
                            <span>${{ number_format($precios['precio_original'], 2) }}</span>
                        </div>
                        @if($precios['tiene_descuento'])
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: #10b981;">
                            <span>Descuento aplicado</span>
                            <span>-${{ number_format($precios['monto_descuento'], 2) }}</span>
                        </div>
                        @endif
                        <div style="height: 1px; background: #e2e8f0; margin: 16px 0;"></div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 800;">
                            <span>Total a pagar</span>
                            <span style="color: #667eea;">${{ number_format($precios['precio_final'], 2) }}</span>
                        </div>
                    </div>

                    @if($precios['porcentaje_descuento'])
                    <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 16px; font-size: 0.8rem; text-align: center; margin-bottom: 24px;">
                        <i class="fas fa-tag"></i> Cupón aplicado: {{ $precios['porcentaje_descuento'] }}% de descuento
                    </div>
                    @endif

                    <!-- Información adicional del curso (relleno) -->
                    <div style="background: #f8fafc; border-radius: 20px; padding: 20px; margin-bottom: 24px;">
                        <p style="font-size: 0.8rem; font-weight: 600; margin-bottom: 12px;">✨ ¿Qué incluye?</p>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 10px; font-size: 0.8rem; color: #334155;"><i class="fas fa-check-circle" style="color: #10b981; width: 18px;"></i> Más de 50 clases en video</li>
                            <li style="margin-bottom: 10px; font-size: 0.8rem; color: #334155;"><i class="fas fa-check-circle" style="color: #10b981; width: 18px;"></i> Material descargable en PDF</li>
                            <li style="margin-bottom: 10px; font-size: 0.8rem; color: #334155;"><i class="fas fa-check-circle" style="color: #10b981; width: 18px;"></i> Simuladores de examen</li>
                            <li style="margin-bottom: 10px; font-size: 0.8rem; color: #334155;"><i class="fas fa-check-circle" style="color: #10b981; width: 18px;"></i> Certificado de finalización</li>
                            <li style="font-size: 0.8rem; color: #334155;"><i class="fas fa-check-circle" style="color: #10b981; width: 18px;"></i> Acceso por 1 año</li>
                        </ul>
                    </div>

                    <!-- Garantía -->
                    <div style="text-align: center;">
                        <i class="fas fa-lock" style="color: #10b981; font-size: 14px;"></i>
                        <span style="font-size: 0.7rem; color: #64748b;"> Pago 100% seguro · Datos encriptados</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://sdk.mercadopago.com/js/v2"></script>

<script>
// Elementos
const radioMP = document.getElementById('radioMP');
const radioTransferencia = document.getElementById('radioTransferencia');
const radioOxxo = document.getElementById('radioOxxo');
const btnTradicional = document.getElementById('btnTradicional');
const btnMP = document.getElementById('btnMP');
const formTradicional = document.getElementById('formTradicional');
const metodoSeleccionadoSpan = document.getElementById('metodoSeleccionado');

let preferenciaId = null;

// Inicializar Mercado Pago
const mp = new MercadoPago('{{ config("mercadopago.public_key") }}');

// Función para actualizar estilos de los labels y el texto del método
function updateLabelStyle() {
    const labels = [
        { id: 'labelMP', radio: radioMP },
        { id: 'labelTransferencia', radio: radioTransferencia },
        { id: 'labelOxxo', radio: radioOxxo }
    ];
    
    labels.forEach(({ id, radio }) => {
        const label = document.getElementById(id);
        if (radio && label) {
            if (radio.checked) {
                label.style.borderColor = '#667eea';
                label.style.background = '#f8f7ff';
                // Actualizar texto del método seleccionado
                if (radio === radioMP) metodoSeleccionadoSpan.textContent = 'Mercado Pago';
                if (radio === radioTransferencia) metodoSeleccionadoSpan.textContent = 'Transferencia bancaria';
                if (radio === radioOxxo) metodoSeleccionadoSpan.textContent = 'Pago en tienda';
            } else {
                label.style.borderColor = '#e2e8f0';
                label.style.background = 'white';
            }
        }
    });
}

// Eventos de selección
radioMP.addEventListener('change', function() {
    if (this.checked) {
        updateLabelStyle();
        formTradicional.style.display = 'none';
        btnMP.style.display = 'block';
        crearPreferencia();
    }
});

radioTransferencia.addEventListener('change', function() {
    if (this.checked) {
        updateLabelStyle();
        formTradicional.style.display = 'block';
        btnMP.style.display = 'none';
    }
});

radioOxxo.addEventListener('change', function() {
    if (this.checked) {
        updateLabelStyle();
        formTradicional.style.display = 'block';
        btnMP.style.display = 'none';
    }
});

// Crear preferencia en Mercado Pago
async function crearPreferencia() {
    btnMP.disabled = true;
    btnMP.innerHTML = '<i class="fab fa-cc-mastercard"></i> Preparando pago...';

    try {
        const response = await fetch('{{ route("estudiante.pago.mercadopago.crear") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();

        if (data.success) {
            preferenciaId = data.preference_id;
            btnMP.disabled = false;
            btnMP.innerHTML = '<i class="fab fa-cc-mastercard"></i> Pagar con Mercado Pago';
        } else {
            Swal.fire('Error', data.message, 'error');
            // Fallback a transferencia
            radioTransferencia.checked = true;
            radioTransferencia.dispatchEvent(new Event('change'));
        }
    } catch (error) {
        console.error(error);
        Swal.fire('Error', 'Error de conexión', 'error');
        radioTransferencia.checked = true;
        radioTransferencia.dispatchEvent(new Event('change'));
    }
}

// Pagar con Mercado Pago
btnMP.addEventListener('click', function() {
    if (!preferenciaId) {
        Swal.fire('Info', 'Preparando el pago...', 'info');
        crearPreferencia();
        return;
    }

    btnMP.disabled = true;
    btnMP.innerHTML = '<i class="fab fa-cc-mastercard"></i> Redirigiendo...';

    mp.checkout({
        preference: {
            id: preferenciaId
        },
        autoOpen: true
    });
});

// Inicializar estilos y estado
updateLabelStyle();
formTradicional.style.display = 'block';
btnMP.style.display = 'none';
</script>
@endsection