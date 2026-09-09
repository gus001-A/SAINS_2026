<script setup>
import { ref } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { CheckOutlined, CloseOutlined, EditOutlined, DollarOutlined, FileImageOutlined, DownloadOutlined } from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import { confirmAction } from '@/lib/notify';

const props = defineProps({ pago: { type: Object, required: true } });

const money = (n) => '$' + Number(n || 0).toLocaleString('es-MX', { maximumFractionDigits: 2 });
const estadoColor = (s) => ({ aprobado: 'green', completado: 'green', rechazado: 'red', cancelado: 'red', pendiente: 'orange' }[s] || 'blue');
const estadoTone = computed(() => {
    if (['aprobado', 'completado'].includes(props.pago.estatus)) return 'ok';
    if (['rechazado', 'cancelado'].includes(props.pago.estatus)) return 'bad';
    return 'wait';
});
const esPdf = computed(() => (props.pago.comprobante_url || '').toLowerCase().endsWith('.pdf'));

const revisado = ['aprobado', 'rechazado', 'completado'].includes(props.pago.estatus);

function aprobar() {
    confirmAction({
        title: '¿Aprobar este pago?',
        content: 'Se activará el plan del estudiante y se le enviará un correo.',
        okText: 'Sí, aprobar',
        onOk: () => router.post(route('admin.pagos.aprobar', props.pago.id), {}, { preserveScroll: true }),
    });
}

const rechazoOpen = ref(false);
const rechazoForm = useForm({ motivo_rechazo: '' });
function rechazar() {
    rechazoForm.post(route('admin.pagos.rechazar', props.pago.id), {
        preserveScroll: true,
        onSuccess: () => { rechazoOpen.value = false; rechazoForm.reset(); },
    });
}
</script>

<template>
    <AdminLayout :title="`Pago #${pago.id}`">
        <PageHead :title="`Pago #${pago.id}`" :subtitle="pago.referencia" :icon="DollarOutlined"
            back @back="router.visit(route('admin.pagos.index'))">
            <template #actions>
                <template v-if="!revisado">
                    <a-button type="primary" @click="aprobar"><template #icon><CheckOutlined /></template>Aprobar</a-button>
                    <a-button danger @click="rechazoOpen = true"><template #icon><CloseOutlined /></template>Rechazar</a-button>
                </template>
                <Link :href="route('admin.pagos.edit', pago.id)"><a-button><template #icon><EditOutlined /></template>Editar</a-button></Link>
            </template>
        </PageHead>

        <section class="pay-banner" :class="`is-${estadoTone}`">
            <div>
                <span class="pay-banner__label">Monto del pago</span>
                <b class="pay-banner__amount">{{ money(pago.monto) }}</b>
            </div>
            <div class="pay-banner__meta">
                <a-tag :bordered="false" :color="estadoColor(pago.estatus)" style="font-size: 13px; padding: 3px 12px">
                    {{ pago.estatus }}
                </a-tag>
                <span>{{ pago.tipo_pago }} · {{ pago.fecha_pago || 'sin fecha' }}</span>
            </div>
        </section>

        <a-row :gutter="16">
            <a-col :xs="24" :lg="14">
                <a-card :bordered="false" title="Detalle del pago">
                    <a-descriptions :column="2" size="small" bordered>
                        <a-descriptions-item label="Estado"><a-tag :bordered="false" :color="estadoColor(pago.estatus)">{{ pago.estatus }}</a-tag></a-descriptions-item>
                        <a-descriptions-item label="Monto"><b style="color: #4f46e5">{{ money(pago.monto) }}</b></a-descriptions-item>
                        <a-descriptions-item label="Método">{{ pago.tipo_pago }}</a-descriptions-item>
                        <a-descriptions-item label="Fecha de pago">{{ pago.fecha_pago || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Revisado por">{{ pago.revisor || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Fecha de revisión">{{ pago.fecha_aprueba || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Nota" :span="2">
                            <div style="white-space: pre-wrap">{{ pago.nota_usuario || '—' }}</div>
                        </a-descriptions-item>
                    </a-descriptions>
                </a-card>

                <a-card v-if="pago.comprobante_url" :bordered="false" style="margin-top: 16px">
                    <template #title><FileImageOutlined style="color: #0284c7" /> Comprobante de pago</template>
                    <template #extra>
                        <a-button type="link" size="small" :href="pago.comprobante_url" target="_blank">
                            <template #icon><DownloadOutlined /></template>Abrir
                        </a-button>
                    </template>
                    <a-result v-if="esPdf" status="info" title="Comprobante en PDF" sub-title="Ábrelo para revisarlo.">
                        <template #extra>
                            <a-button type="primary" :href="pago.comprobante_url" target="_blank">Ver PDF</a-button>
                        </template>
                    </a-result>
                    <a v-else :href="pago.comprobante_url" target="_blank" rel="noopener">
                        <img :src="pago.comprobante_url" alt="Comprobante" style="max-width: 100%; border-radius: 10px; border: 1px solid #e9edf4" />
                    </a>
                </a-card>
                <a-card v-else :bordered="false" style="margin-top: 16px">
                    <a-empty :image="null" description="El estudiante no ha subido comprobante" />
                </a-card>
            </a-col>

            <a-col :xs="24" :lg="10">
                <a-card :bordered="false" title="Estudiante">
                    <template v-if="pago.alumno">
                        <a-descriptions :column="1" size="small">
                            <a-descriptions-item label="Nombre">{{ pago.alumno.nombre }}</a-descriptions-item>
                            <a-descriptions-item label="Correo">{{ pago.alumno.correo }}</a-descriptions-item>
                            <a-descriptions-item label="Plan">
                                <a-tag :color="pago.alumno.plan_activo ? 'green' : 'default'">{{ pago.alumno.plan_activo ? 'Activo' : 'Sin plan' }}</a-tag>
                            </a-descriptions-item>
                        </a-descriptions>
                        <Link :href="route('admin.estudiantes.show', pago.alumno.id)"><a-button block style="margin-top: 8px">Ver ficha del estudiante</a-button></Link>
                    </template>
                    <a-empty v-else description="Sin estudiante asociado" />
                </a-card>
            </a-col>
        </a-row>

        <a-modal v-model:open="rechazoOpen" title="Rechazar pago" :confirm-loading="rechazoForm.processing" ok-text="Rechazar" ok-type="danger" @ok="rechazar">
            <a-form layout="vertical" style="margin-top: 8px">
                <a-form-item label="Motivo del rechazo (opcional)">
                    <a-textarea v-model:value="rechazoForm.motivo_rechazo" :rows="3" placeholder="Se incluirá en el correo al estudiante" />
                </a-form-item>
            </a-form>
        </a-modal>
    </AdminLayout>
</template>

<style scoped>
.pay-banner {
    display: flex; justify-content: space-between; align-items: center; gap: 18px; flex-wrap: wrap;
    border-radius: 18px; padding: 20px 26px; margin-bottom: 18px; color: #fff;
}
.pay-banner.is-ok { background: linear-gradient(135deg, #059669, #34d399); box-shadow: 0 18px 40px -22px rgba(16, 185, 129, .6); }
.pay-banner.is-bad { background: linear-gradient(135deg, #dc2626, #f87171); box-shadow: 0 18px 40px -22px rgba(239, 68, 68, .55); }
.pay-banner.is-wait { background: linear-gradient(135deg, #d97706, #fbbf24); box-shadow: 0 18px 40px -22px rgba(245, 158, 11, .55); }
.pay-banner__label { font-size: 11px; text-transform: uppercase; letter-spacing: .08em; opacity: .85; }
.pay-banner__amount { display: block; font-size: 1.9rem; font-weight: 800; }
.pay-banner__meta { display: flex; align-items: center; gap: 10px; font-size: 13px; opacity: .95; }
</style>
