<script setup>
import { computed, reactive, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { PlusOutlined, SearchOutlined, TeamOutlined } from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import StatCard from '@/Components/StatCard.vue';
import RowActions from '@/Components/RowActions.vue';
import { confirmDelete } from '@/lib/notify';

const props = defineProps({
    estudiantes: { type: Object, required: true },
    estadosPrepa: { type: Array, default: () => [] },
    estadosUniversidad: { type: Array, default: () => [] },
    stats: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const state = reactive({
    search: props.filters.search ?? '',
    telefono: props.filters.telefono ?? '',
    sexo: props.filters.sexo ?? undefined,
    plan_activo: props.filters.plan_activo ?? undefined,
    estado_prepa: props.filters.estado_prepa ?? undefined,
    estado_universidad: props.filters.estado_universidad ?? undefined,
});

let debounce = null;
function reload(extra = {}) {
    router.get(route('admin.estudiantes.index'), {
        search: state.search || undefined,
        telefono: state.telefono || undefined,
        sexo: state.sexo || undefined,
        plan_activo: state.plan_activo ?? undefined,
        estado_prepa: state.estado_prepa || undefined,
        estado_universidad: state.estado_universidad || undefined,
        ...extra,
    }, { preserveState: true, replace: true, preserveScroll: true });
}
watch([() => state.search, () => state.telefono], () => { clearTimeout(debounce); debounce = setTimeout(() => reload(), 350); });
watch([() => state.sexo, () => state.plan_activo, () => state.estado_prepa, () => state.estado_universidad], () => reload());

function eliminar(r) {
    confirmDelete({ title: '¿Eliminar estudiante?', content: r.nombre_completo, onOk: () => router.delete(route('admin.estudiantes.destroy', r.id), { preserveScroll: true }) });
}

const pagination = computed(() => ({
    current: props.estudiantes.current_page,
    pageSize: props.estudiantes.per_page,
    total: props.estudiantes.total,
    showSizeChanger: false,
    showTotal: (t) => `${t} estudiantes`,
}));
function onChange(pag) { reload({ page: pag.current }); }

const columns = [
    { title: 'Estudiante', key: 'nombre' },
    { title: 'Teléfono', dataIndex: 'telefono', key: 'telefono', width: 130 },
    { title: 'Escuela de procedencia', key: 'escuela', width: 220 },
    { title: 'Universidad', key: 'universidad', width: 170 },
    { title: 'Plan', key: 'plan', width: 120 },
    { title: '', key: 'acciones', width: 130, align: 'right' },
];
</script>

<template>
    <AdminLayout title="Estudiantes">
        <PageHead title="Estudiantes" subtitle="Alumnos registrados en la plataforma" :icon="TeamOutlined">
            <template #actions>
                <Link :href="route('admin.estudiantes.create')">
                    <a-button type="primary"><template #icon><PlusOutlined /></template>Nuevo estudiante</a-button>
                </Link>
            </template>
        </PageHead>

        <div class="sains-stats sains-stats--4">
            <StatCard label="Total" :value="stats.total" color="indigo" />
            <StatCard label="Con plan activo" :value="stats.activos" color="green" />
            <StatCard label="Sin plan" :value="stats.inactivos" color="slate" />
            <StatCard label="Con cupón" :value="stats.conCupon" color="pink" />
        </div>

        <a-card :bordered="false">
            <a-table class="filtered-table" :columns="columns" :data-source="estudiantes.data" :pagination="pagination" row-key="id" size="middle" :scroll="{ x: 1000 }" @change="onChange">
                <template #summary>
                    <a-table-summary-row>
                        <a-table-summary-cell v-for="(col, i) in columns" :key="col.key ?? i" :index="i">
                            <a-input v-if="col.key === 'nombre'" v-model:value="state.search" size="small" allow-clear placeholder="Nombre o correo…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-input v-else-if="col.key === 'telefono'" v-model:value="state.telefono" size="small" allow-clear placeholder="Teléfono…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-select v-else-if="col.key === 'escuela'" v-model:value="state.estado_prepa" size="small" allow-clear show-search
                                placeholder="Estado" :options="estadosPrepa.map((e) => ({ value: e, label: e }))" />
                            <a-select v-else-if="col.key === 'universidad'" v-model:value="state.estado_universidad" size="small" allow-clear show-search
                                placeholder="Estado" :options="estadosUniversidad.map((e) => ({ value: e, label: e }))" />
                            <a-select v-else-if="col.key === 'plan'" v-model:value="state.plan_activo" size="small" allow-clear placeholder="Todos"
                                :options="[{ value: 1, label: 'Activo' }, { value: 0, label: 'Sin plan' }]" />
                        </a-table-summary-cell>
                    </a-table-summary-row>
                </template>

                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'nombre'">
                        <div style="font-weight:600">{{ record.nombre_completo }}</div>
                        <div style="font-size:11px;color:#94a3b8">
                            {{ record.correo }}
                            <a-tag v-if="record.sin_perfil" color="orange" style="margin-left:6px">Perfil incompleto</a-tag>
                        </div>
                    </template>
                    <template v-else-if="column.key === 'telefono'">{{ record.telefono || '—' }}</template>
                    <template v-else-if="column.key === 'escuela'">
                        <div>{{ record.escuela || '—' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ record.escuela_estado }}</div>
                    </template>
                    <template v-else-if="column.key === 'universidad'">
                        <div>{{ record.universidad || '—' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ record.universidad_estado }}</div>
                    </template>
                    <template v-else-if="column.key === 'plan'">
                        <a-tag :color="record.plan_activo ? 'green' : 'default'">{{ record.plan_activo ? 'Activo' : 'Sin plan' }}</a-tag>
                        <a-tag v-if="record.cupon" color="blue">{{ record.cupon }}</a-tag>
                    </template>
                    <template v-else-if="column.key === 'acciones'">
                        <RowActions
                            :view-href="route('admin.estudiantes.show', record.id)"
                            :edit-href="route('admin.estudiantes.edit', record.id)"
                            @delete="eliminar(record)"
                        />
                    </template>
                </template>
            </a-table>
        </a-card>
    </AdminLayout>
</template>
