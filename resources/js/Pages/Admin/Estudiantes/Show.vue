<script setup>
import { useForm, router, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { EditOutlined, KeyOutlined, TeamOutlined, BarChartOutlined, FileDoneOutlined } from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import StatCard from '@/Components/StatCard.vue';

const props = defineProps({
    estudiante: { type: Object, required: true },
    stats: { type: Object, required: true },
    estudioDiario: { type: Array, default: () => [] },
    examenes: { type: Array, default: () => [] },
    pagos: { type: Array, default: () => [] }, // el controlador aún lo envía; no se muestra
});

const pwdOpen = ref(false);
const pwd = useForm({ new_password: '', new_password_confirmation: '' });
function resetPwd() {
    pwd.post(route('admin.estudiantes.reset-password', props.estudiante.id), {
        preserveScroll: true,
        onSuccess: () => { pwdOpen.value = false; pwd.reset(); },
    });
}

const maxHoras = computed(() => Math.max(1, ...props.estudioDiario.map((d) => d.horas)));
const hayEstudio = computed(() => props.estudioDiario.some((d) => d.horas > 0));
const iniciales = computed(() => (props.estudiante.nombre_completo || '?').trim().split(/\s+/).slice(0, 2).map((w) => w[0]).join('').toUpperCase());

const fotoOk = ref(!!props.estudiante.foto_url);

const facts = computed(() => [
    { label: 'Correo', value: props.estudiante.correo },
    { label: 'Teléfono', value: props.estudiante.telefono || '—' },
    { label: 'Tel. casa', value: props.estudiante.telefono_casa || '—' },
    { label: 'Sexo', value: props.estudiante.sexo === 'M' ? 'Masculino' : props.estudiante.sexo === 'F' ? 'Femenino' : '—' },
    { label: 'Nacimiento', value: props.estudiante.fecha_nacimiento || '—' },
    { label: 'Inscripción', value: props.estudiante.fecha_inscripcion || '—' },
    { label: 'Preparatoria', value: props.estudiante.escuela || '—' },
    { label: 'Universidad', value: props.estudiante.universidad || '—' },
    { label: 'Cupón', value: props.estudiante.cupon || '—' },
    { label: 'Última actividad', value: props.stats.ultima_actividad || '—' },
]);

/* ---- Progreso de videos ---- */
const videosSinVer = computed(() => Math.max(0, (props.stats.total_videos || 0) - (props.stats.videos_completos || 0) - (props.stats.videos_en_progreso || 0)));
const totalV = computed(() => Math.max(1, props.stats.total_videos || 0));
const seg = computed(() => ({
    done: (props.stats.videos_completos || 0) / totalV.value * 100,
    prog: (props.stats.videos_en_progreso || 0) / totalV.value * 100,
    none: videosSinVer.value / totalV.value * 100,
}));

/* ---- Exámenes ---- */
const tipoMeta = (t) => {
    const k = String(t || '').toLowerCase();
    if (k.includes('materia')) return { color: '#2563eb', bg: '#eff6ff', label: 'Por materia' };
    if (k.includes('curso')) return { color: '#16a34a', bg: '#f0fdf4', label: 'Por curso' };
    if (k.includes('simula')) return { color: '#dc2626', bg: '#fef2f2', label: 'Simulación' };
    return { color: '#7c3aed', bg: '#f5f3ff', label: t ? (t[0].toUpperCase() + t.slice(1)) : 'Examen' };
};
const califColor = (n) => (n >= 80 ? '#16a34a' : n >= 60 ? '#f59e0b' : '#dc2626');
const examColumns = [
    { title: 'Tipo', key: 'tipo' },
    { title: 'Calificación', key: 'calif' },
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

        <div class="bento">
            <!-- Cabecera de perfil -->
            <section class="card profile">
                <span class="profile__avatar">
                    <img v-if="fotoOk" :src="estudiante.foto_url" :alt="estudiante.nombre_completo" @error="fotoOk = false" />
                    <template v-else>{{ iniciales }}</template>
                </span>
                <div class="profile__body">
                    <div class="profile__top">
                        <h2 class="profile__name">{{ estudiante.nombre_completo }}</h2>
                        <a-tag :bordered="false" :color="estudiante.plan_activo ? 'green' : 'default'">
                            {{ estudiante.plan_activo ? 'Plan Premium activo' : 'Sin plan' }}
                        </a-tag>
                    </div>
                    <div class="facts">
                        <div v-for="f in facts" :key="f.label" class="fact">
                            <span class="fact__label">{{ f.label }}</span>
                            <span class="fact__value">{{ f.value }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- KPIs -->
            <div class="sains-stats sains-stats--4 bento__stats">
                <StatCard label="Horas de estudio" :value="stats.tiempo_horas" color="indigo" />
                <StatCard label="Sesiones" :value="stats.total_sesiones" color="violet" />
                <StatCard label="Exámenes" :value="stats.total_examenes" color="amber" />
                <StatCard label="Promedio" :value="`${stats.promedio_calificaciones} / 100`" color="green" />
            </div>

            <!-- Progreso + gráfica -->
            <div class="bento__row">
                <section class="card">
                    <div class="card__title">Progreso de videos</div>
                    <div class="vp">
                        <a-progress type="circle" :percent="stats.porcentaje_progreso" :size="128" :stroke-width="9"
                            :stroke-color="{ '0%': '#6366f1', '100%': '#9333ea' }" />
                        <div class="vp__side">
                            <div class="vp__bar">
                                <span class="vp__bar-seg vp--done" :style="{ width: seg.done + '%' }"></span>
                                <span class="vp__bar-seg vp--prog" :style="{ width: seg.prog + '%' }"></span>
                                <span class="vp__bar-seg vp--none" :style="{ width: seg.none + '%' }"></span>
                            </div>
                            <ul class="vp__legend">
                                <li><i class="vp--done"></i><b>{{ stats.videos_completos }}</b> completados</li>
                                <li><i class="vp--prog"></i><b>{{ stats.videos_en_progreso }}</b> en progreso</li>
                                <li><i class="vp--none"></i><b>{{ videosSinVer }}</b> sin ver</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section class="card">
                    <div class="card__title">Estudio · últimos 7 días</div>
                    <div v-if="hayEstudio" class="week">
                        <div v-for="(d, i) in estudioDiario" :key="i" class="week__day">
                            <div class="week__bar-wrap">
                                <span class="week__bar" :class="{ 'is-zero': !d.horas }"
                                    :style="{ height: Math.max(4, d.horas / maxHoras * 100) + '%' }"></span>
                            </div>
                            <span class="week__lbl">{{ d.dia.slice(0, 3) }}</span>
                            <span class="week__val">{{ d.horas }}h</span>
                        </div>
                    </div>
                    <div v-else class="week-empty">
                        <BarChartOutlined />
                        <span>Sin sesiones de estudio esta semana</span>
                    </div>
                </section>
            </div>

            <!-- Exámenes realizados -->
            <section class="card card--flush">
                <div class="card__title">
                    Exámenes realizados
                    <span class="card__meta">{{ stats.total_examenes }} en total · promedio {{ stats.promedio_calificaciones }}/100</span>
                </div>
                <a-table v-if="examenes.length" :columns="examColumns" :data-source="examenes" :pagination="false" row-key="id" size="middle" :scroll="{ x: 460 }">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'tipo'">
                            <span class="exam-tipo" :style="{ color: tipoMeta(record.tipo).color, background: tipoMeta(record.tipo).bg }">
                                {{ tipoMeta(record.tipo).label }}
                            </span>
                        </template>
                        <template v-else-if="column.key === 'calif'">
                            <div class="exam-score">
                                <span class="exam-score__bar">
                                    <span :style="{ width: Math.min(100, record.calificacion) + '%', background: califColor(record.calificacion) }"></span>
                                </span>
                                <b :style="{ color: califColor(record.calificacion) }">{{ record.calificacion }}</b>
                            </div>
                        </template>
                    </template>
                </a-table>
                <div v-else class="exam-empty">
                    <FileDoneOutlined />
                    <span>Este estudiante aún no ha presentado exámenes</span>
                </div>
            </section>
        </div>

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
.bento { display: flex; flex-direction: column; gap: 14px; }
.bento__stats { margin-bottom: 0 !important; }
.bento__row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    align-items: stretch;
}
@media (max-width: 900px) { .bento__row { grid-template-columns: 1fr; } }

.card {
    background: #fff;
    border: 1px solid var(--sains-line);
    border-radius: 16px;
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
}
.card--flush { padding-bottom: 8px; }
.card__title {
    display: flex; align-items: baseline; gap: 10px; flex-wrap: wrap;
    font-size: 14px; font-weight: 700; color: #0f172a;
    padding-bottom: 12px; margin-bottom: 14px;
    border-bottom: 1px solid var(--sains-line);
}
.card--flush .card__title { margin-bottom: 4px; }
.card__meta { font-size: 12px; font-weight: 500; color: var(--sains-faint); }

/* ---- Cabecera de perfil ---- */
.profile { flex-direction: row; align-items: flex-start; gap: 18px; }
@media (max-width: 640px) {
    .profile { flex-direction: column; gap: 14px; }
    .profile__avatar { width: 56px; height: 56px; }
    .fact__value { white-space: normal; }
}
.profile__avatar {
    width: 64px; height: 64px; flex: none; border-radius: 18px; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 22px; color: #fff;
    background: linear-gradient(135deg, #4f46e5, #9333ea);
}
.profile__avatar img { width: 100%; height: 100%; object-fit: cover; }
.profile__body { flex: 1; min-width: 0; }
.profile__top { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
.profile__name { font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -.02em; }

.facts {
    display: flex;
    flex-wrap: wrap;
    gap: 1px;
    background: var(--sains-line);
    border: 1px solid var(--sains-line);
    border-radius: 12px;
    overflow: hidden;
}
.fact {
    flex: 1 1 160px;
    display: flex; flex-direction: column; gap: 2px;
    padding: 9px 13px; background: #fff;
}
.fact__label { font-size: 10.5px; text-transform: uppercase; letter-spacing: .04em; color: var(--sains-faint); font-weight: 600; }
.fact__value { font-size: 13px; font-weight: 600; color: #1e293b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ---- Progreso de videos ---- */
.vp { display: flex; align-items: center; gap: 26px; flex: 1; padding: 6px 0; }
.vp__side { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 14px; }
.vp__bar {
    display: flex; height: 10px; border-radius: 999px; overflow: hidden;
    background: #eef2f7;
}
.vp__bar-seg { height: 100%; transition: width .5s cubic-bezier(.16, 1, .3, 1); }
.vp__legend { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 8px; }
.vp__legend li { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: var(--sains-muted); }
.vp__legend b { color: #0f172a; font-weight: 800; font-size: 14px; }
.vp__legend i { width: 9px; height: 9px; border-radius: 3px; flex: none; }
.vp--done { background: #6366f1; }
.vp--prog { background: #f59e0b; }
.vp--none { background: #cbd5e1; }
@media (max-width: 480px) { .vp { flex-direction: column; gap: 18px; } }

/* ---- Gráfica semanal ---- */
.week { display: flex; align-items: flex-end; gap: 10px; flex: 1; min-height: 156px; }
.week__day { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; }
.week__bar-wrap { flex: 1; width: 100%; display: flex; align-items: flex-end; }
.week__bar {
    width: 100%; border-radius: 6px 6px 0 0; min-height: 4px;
    background: linear-gradient(180deg, #6366f1, #7c3aed);
    transition: height .4s cubic-bezier(.16, 1, .3, 1);
}
.week__bar.is-zero { background: #e2e8f0; }
.week__lbl { font-size: 11px; color: var(--sains-muted); margin-top: 8px; }
.week__val { font-size: 11px; font-weight: 700; color: #475569; }
.week-empty {
    flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
    min-height: 130px; color: var(--sains-faint);
}
.week-empty :deep(.anticon) { font-size: 30px; }
.week-empty span { font-size: 13px; }

/* ---- Exámenes ---- */
.exam-tipo {
    display: inline-block; padding: 3px 10px; border-radius: 999px;
    font-size: 12px; font-weight: 700;
}
.exam-score { display: flex; align-items: center; gap: 12px; max-width: 240px; }
.exam-score__bar {
    flex: 1; height: 8px; border-radius: 999px; background: #eef2f7; overflow: hidden;
}
.exam-score__bar span { display: block; height: 100%; border-radius: 999px; transition: width .4s ease; }
.exam-score b { font-size: 14px; font-weight: 800; min-width: 26px; text-align: right; }
.exam-empty {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    padding: 40px 20px; color: var(--sains-faint);
}
.exam-empty :deep(.anticon) { font-size: 30px; }
.exam-empty span { font-size: 13px; }

:deep(.ant-table-wrapper) { flex: 1; }
:deep(.ant-table) { background: transparent; }
:deep(.ant-table-thead > tr > th) { background: #f8fafc; font-size: 11.5px; text-transform: uppercase; letter-spacing: .03em; color: var(--sains-faint); }
</style>
