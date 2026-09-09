<script setup>
import { useForm, router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { EditOutlined, KeyOutlined, TeamOutlined } from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import StatCard from '@/Components/StatCard.vue';

const props = defineProps({
    estudiante: { type: Object, required: true },
    stats: { type: Object, required: true },
    estudioDiario: { type: Array, default: () => [] },
    examenes: { type: Array, default: () => [] },
    pagos: { type: Array, default: () => [] },
});

const pwdOpen = ref(false);
const pwd = useForm({ new_password: '', new_password_confirmation: '' });
function resetPwd() {
    pwd.post(route('admin.estudiantes.reset-password', props.estudiante.id), {
        preserveScroll: true,
        onSuccess: () => { pwdOpen.value = false; pwd.reset(); },
    });
}

const money = (n) => '$' + Number(n || 0).toLocaleString('es-MX', { maximumFractionDigits: 2 });
const maxHoras = Math.max(1, ...props.estudioDiario.map((d) => d.horas));

const examColumns = [
    { title: 'Tipo', dataIndex: 'tipo', key: 'tipo' },
    { title: 'Calificación', dataIndex: 'calificacion', key: 'calif', width: 130, align: 'center' },
    { title: 'Fecha', dataIndex: 'fecha', key: 'fecha', width: 130, align: 'right' },
];
const pagoColumns = [
    { title: 'Referencia', dataIndex: 'referencia', key: 'ref' },
    { title: 'Método', dataIndex: 'tipo', key: 'tipo', width: 130 },
    { title: 'Monto', dataIndex: 'monto', key: 'monto', width: 120, align: 'right' },
    { title: 'Estado', dataIndex: 'estatus', key: 'estatus', width: 130 },
    { title: 'Fecha', dataIndex: 'fecha', key: 'fecha', width: 120, align: 'right' },
];
</script>

<template>
    <AdminLayout :title="estudiante.nombre_completo">
        <PageHead :title="estudiante.nombre_completo" :subtitle="estudiante.correo" :icon="TeamOutlined"
            back @back="router.visit(route('admin.estudiantes.index'))">
            <template #actions>
                <a-button @click="pwdOpen = true"><template #icon><KeyOutlined /></template>Restablecer contraseña</a-button>
                <Link :href="route('admin.estudiantes.edit', estudiante.id)">
                    <a-button type="primary"><template #icon><EditOutlined /></template>Editar</a-button>
                </Link>
            </template>
        </PageHead>

        <a-row :gutter="16">
            <a-col :xs="24" :lg="8">
                <a-card :bordered="false" class="ficha-card">
                    <div class="ficha-head">
                        <span class="ficha-avatar">{{ (estudiante.nombre_completo || '?').slice(0, 1).toUpperCase() }}</span>
                        <div>
                            <div class="ficha-name">{{ estudiante.nombre_completo }}</div>
                            <a-tag :bordered="false" :color="estudiante.plan_activo ? 'green' : 'default'">
                                {{ estudiante.plan_activo ? 'Plan Premium activo' : 'Sin plan' }}
                            </a-tag>
                        </div>
                    </div>
                    <a-descriptions :column="1" size="small">
                        <a-descriptions-item label="Correo">{{ estudiante.correo }}</a-descriptions-item>
                        <a-descriptions-item label="Teléfono">{{ estudiante.telefono || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Tel. casa">{{ estudiante.telefono_casa || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Sexo">{{ estudiante.sexo === 'M' ? 'Masculino' : estudiante.sexo === 'F' ? 'Femenino' : '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Nacimiento">{{ estudiante.fecha_nacimiento || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Inscripción">{{ estudiante.fecha_inscripcion || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Preparatoria">{{ estudiante.escuela || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Universidad">{{ estudiante.universidad || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Cupón">{{ estudiante.cupon || '—' }}</a-descriptions-item>
                        <a-descriptions-item label="Última actividad">{{ stats.ultima_actividad }}</a-descriptions-item>
                    </a-descriptions>
                </a-card>
            </a-col>

            <a-col :xs="24" :lg="16">
                <div class="sains-stats sains-stats--4">
                    <StatCard label="Horas de estudio" :value="stats.tiempo_horas" color="indigo" />
                    <StatCard label="Sesiones" :value="stats.total_sesiones" color="violet" />
                    <StatCard label="Exámenes" :value="stats.total_examenes" color="amber" />
                    <StatCard label="Promedio" :value="`${stats.promedio_calificaciones} / 100`" color="green" />
                </div>

                <a-card :bordered="false" title="Progreso de videos" style="margin-top: 16px">
                    <a-progress :percent="stats.porcentaje_progreso" />
                    <div style="font-size: 13px; color: #64748b; margin-top: 6px">
                        {{ stats.videos_completos }} completados · {{ stats.videos_en_progreso }} en progreso · {{ stats.total_videos }} totales
                    </div>
                </a-card>

                <a-card :bordered="false" title="Estudio últimos 7 días" style="margin-top: 16px">
                    <div v-if="estudioDiario.length" class="week-chart">
                        <div v-for="(d, i) in estudioDiario" :key="i" class="day">
                            <div class="bar-wrap">
                                <div class="bar" :style="{ height: (d.horas / maxHoras * 100) + '%' }"></div>
                            </div>
                            <div class="day-label">{{ d.dia.slice(0, 3) }}</div>
                            <div class="day-val">{{ d.horas }}h</div>
                        </div>
                    </div>
                    <a-empty v-else description="Sin actividad reciente" />
                </a-card>
            </a-col>
        </a-row>

        <a-row :gutter="16" style="margin-top: 16px">
            <a-col :xs="24" :lg="12">
                <a-card :bordered="false" title="Exámenes realizados">
                    <a-table :columns="examColumns" :data-source="examenes" :pagination="false" row-key="id" size="small">
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'calif'">
                                <a-tag :color="record.calificacion >= 60 ? 'green' : 'red'">{{ record.calificacion }}</a-tag>
                            </template>
                        </template>
                    </a-table>
                </a-card>
            </a-col>
            <a-col :xs="24" :lg="12">
                <a-card :bordered="false" title="Pagos">
                    <a-table :columns="pagoColumns" :data-source="pagos" :pagination="false" row-key="id" size="small">
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'monto'">{{ money(record.monto) }}</template>
                            <template v-else-if="column.key === 'estatus'">
                                <a-tag :color="record.estatus === 'completado' ? 'green' : record.estatus === 'rechazado' || record.estatus === 'cancelado' ? 'red' : 'orange'">{{ record.estatus }}</a-tag>
                            </template>
                        </template>
                    </a-table>
                </a-card>
            </a-col>
        </a-row>

        <a-modal v-model:open="pwdOpen" title="Restablecer contraseña" class="sains-modal" :confirm-loading="pwd.processing" ok-text="Guardar" @ok="resetPwd">
            <a-form layout="vertical">
                <a-form-item label="Nueva contraseña" required :validate-status="pwd.errors.new_password ? 'error' : undefined" :help="pwd.errors.new_password">
                    <a-input-password v-model:value="pwd.new_password" />
                </a-form-item>
                <a-form-item label="Confirmar nueva contraseña" required>
                    <a-input-password v-model:value="pwd.new_password_confirmation" />
                </a-form-item>
            </a-form>
        </a-modal>
    </AdminLayout>
</template>

<style scoped>
.ficha-card :deep(.ant-card-body) { padding-top: 18px; }
.ficha-head { display: flex; gap: 13px; align-items: center; margin-bottom: 14px; padding-bottom: 14px; border-bottom: 1px solid var(--sains-line); }
.ficha-avatar {
    width: 46px; height: 46px; border-radius: 13px; flex: none;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 17px; color: #fff;
    background: linear-gradient(135deg, #4f46e5, #9333ea);
}
.ficha-name { font-weight: 700; font-size: 15px; color: #0f172a; margin-bottom: 4px; }

.week-chart {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    height: 140px;
}

.day {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.bar-wrap {
    flex: 1;
    width: 100%;
    display: flex;
    align-items: flex-end;
}

.bar {
    width: 100%;
    background: linear-gradient(180deg, #4361ee, #7c3aed);
    border-radius: 6px 6px 0 0;
    min-height: 3px;
}

.day-label {
    font-size: 11px;
    color: #64748b;
    margin-top: 6px;
}

.day-val {
    font-size: 11px;
    font-weight: 600;
}
</style>
