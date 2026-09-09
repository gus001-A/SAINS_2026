<script setup>
import { computed, ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { PlusOutlined, SearchOutlined, ReadOutlined } from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import StatCard from '@/Components/StatCard.vue';
import RowActions from '@/Components/RowActions.vue';
import { confirmDelete } from '@/lib/notify';

const props = defineProps({
    carreras: { type: Object, required: true },
    asignaturas: { type: Array, default: () => [] },
    troncos: { type: Array, default: () => [] },
    stats: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search ?? '');
const troncoId = ref(props.filters.tronco_id ?? undefined);
const calif = ref(props.filters.calif ?? undefined);
let debounce = null;

function reload(extra = {}) {
    router.get(route('admin.carreras.index'),
        { search: search.value || undefined, tronco_id: troncoId.value || undefined, calif: calif.value || undefined, ...extra },
        { preserveState: true, replace: true, preserveScroll: true });
}
watch(search, () => { clearTimeout(debounce); debounce = setTimeout(() => reload(), 350); });
watch([troncoId, calif], () => reload());
const califOpts = [{ value: 'definida', label: 'Con mínima' }, { value: 'sin', label: 'Sin mínima' }];

const asignaturaOptions = computed(() => props.asignaturas.map((a) => ({ value: a.id, label: a.nombre })));
const troncoOptions = computed(() => props.troncos.map((t) => ({ value: t.id, label: t.nombre })));

const modalOpen = ref(false);
const editing = ref(null);
const form = useForm({
    nombre: '', tronco_id: undefined, calificacion_minima: null,
    id_asignatura_1: undefined, id_asignatura_2: undefined, id_asignatura_3: undefined,
});

function openCreate() {
    editing.value = null;
    form.reset();
    form.clearErrors();
    modalOpen.value = true;
}
function openEdit(r) {
    editing.value = r;
    form.nombre = r.nombre;
    form.tronco_id = r.tronco_id;
    form.calificacion_minima = r.calificacion_minima != null ? Number(r.calificacion_minima) : null;
    form.id_asignatura_1 = r.id_asignatura_1;
    form.id_asignatura_2 = r.id_asignatura_2;
    form.id_asignatura_3 = r.id_asignatura_3;
    form.clearErrors();
    modalOpen.value = true;
}
function submit() {
    const opts = { onSuccess: () => { modalOpen.value = false; } };
    if (editing.value) form.put(route('admin.carreras.update', editing.value.id), opts);
    else form.post(route('admin.carreras.store'), opts);
}
function eliminar(r) {
    confirmDelete({ title: '¿Eliminar carrera?', content: r.nombre, onOk: () => router.delete(route('admin.carreras.destroy', r.id), { preserveScroll: true }) });
}

const pagination = computed(() => ({
    current: props.carreras.current_page,
    pageSize: props.carreras.per_page,
    total: props.carreras.total,
    showSizeChanger: false,
    showTotal: (t) => `${t} carreras`,
}));
const sortFieldMap = { unis: 'universidades', calif: 'calificacion_minima', nombre: 'nombre', tronco: 'tronco' };
function onChange(pag, _f, sorter) {
    const extra = { page: pag.current };
    if (sorter && sorter.order) {
        extra.orden_campo = sortFieldMap[sorter.columnKey] || sorter.columnKey;
        extra.orden_direccion = sorter.order === 'ascend' ? 'asc' : 'desc';
    }
    reload(extra);
}

const columns = [
    { title: 'Carrera', dataIndex: 'nombre', key: 'nombre' },
    { title: 'Tronco', dataIndex: 'tronco', key: 'tronco', width: 160 },
    { title: 'Calif. mínima', dataIndex: 'calificacion_minima', key: 'calif', width: 120, align: 'center' },
    { title: 'Universidades', dataIndex: 'total_universidades', key: 'unis', width: 130, align: 'center', sorter: true },
    { title: '', key: 'acciones', width: 100, align: 'right' },
];
</script>

<template>
    <AdminLayout title="Carreras">
        <PageHead title="Carreras" subtitle="Programas académicos" :icon="ReadOutlined">
            <template #actions>
                <a-button type="primary" @click="openCreate"><template #icon><PlusOutlined /></template>Nueva carrera</a-button>
            </template>
        </PageHead>

        <div class="sains-stats sains-stats--4">
            <StatCard label="Total" :value="stats.total" color="indigo" />
            <StatCard label="Con tronco" :value="stats.conTronco" color="violet" />
            <StatCard label="Con universidades" :value="stats.conUniversidades" color="green" />
            <StatCard label="Con calif. mínima" :value="stats.conCalificacionMinima" color="amber" />
        </div>

        <a-card :bordered="false" :body-style="{ padding: '4px 4px 12px' }">
            <a-table class="filtered-table" :columns="columns" :data-source="carreras.data" :pagination="pagination" row-key="id" size="middle" :scroll="{ x: 800 }" @change="onChange">
                <template #emptyText><a-empty :image="null" description="Sin resultados" /></template>

                <template #summary>
                    <a-table-summary-row>
                        <a-table-summary-cell v-for="(col, i) in columns" :key="col.key ?? i" :index="i">
                            <a-input v-if="col.key === 'nombre'" v-model:value="search" size="small" allow-clear placeholder="Buscar carrera…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-select v-else-if="col.key === 'tronco'" v-model:value="troncoId" size="small" allow-clear
                                placeholder="Todos" :options="troncoOptions" />
                            <a-select v-else-if="col.key === 'calif'" v-model:value="calif" size="small" allow-clear
                                placeholder="Todas" :options="califOpts" />
                        </a-table-summary-cell>
                    </a-table-summary-row>
                </template>

                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'nombre'">
                        <div style="font-weight:600">{{ record.nombre }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ record.asignaturas.join(' · ') || 'Sin materias' }}</div>
                    </template>
                    <template v-else-if="column.key === 'tronco'">{{ record.tronco || '—' }}</template>
                    <template v-else-if="column.key === 'calif'">
                        <a-tag v-if="record.calificacion_minima != null" color="blue">{{ Number(record.calificacion_minima) }}</a-tag>
                        <span v-else>—</span>
                    </template>
                    <template v-else-if="column.key === 'unis'">
                        <span class="count-pill" :class="{ 'is-zero': !record.total_universidades }">{{ record.total_universidades }}</span>
                    </template>
                    <template v-else-if="column.key === 'acciones'">
                        <RowActions editable @edit="openEdit(record)" @delete="eliminar(record)" />
                    </template>
                </template>
            </a-table>
        </a-card>

        <a-modal v-model:open="modalOpen" :title="editing ? 'Editar carrera' : 'Nueva carrera'" class="sains-modal" :width="620" :confirm-loading="form.processing" ok-text="Guardar" @ok="submit">
            <a-form layout="vertical">
                <a-form-item label="Nombre" required :validate-status="form.errors.nombre ? 'error' : undefined" :help="form.errors.nombre">
                    <a-input v-model:value="form.nombre" />
                </a-form-item>
                <a-row :gutter="16">
                    <a-col :xs="24" :md="14">
                        <a-form-item label="Tronco" required :validate-status="form.errors.tronco_id ? 'error' : undefined" :help="form.errors.tronco_id">
                            <a-select v-model:value="form.tronco_id" :options="troncoOptions" placeholder="Selecciona…" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="10">
                        <a-form-item label="Calificación mínima" :validate-status="form.errors.calificacion_minima ? 'error' : undefined" :help="form.errors.calificacion_minima">
                            <a-input-number v-model:value="form.calificacion_minima" :min="0" :max="100" :precision="2" style="width: 100%" />
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-form-item label="Materia principal" required :validate-status="form.errors.id_asignatura_1 ? 'error' : undefined" :help="form.errors.id_asignatura_1">
                    <a-select v-model:value="form.id_asignatura_1" show-search option-filter-prop="label" :options="asignaturaOptions" placeholder="Selecciona…" />
                </a-form-item>
                <a-row :gutter="16">
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Materia 2 (opcional)">
                            <a-select v-model:value="form.id_asignatura_2" allow-clear show-search option-filter-prop="label" :options="asignaturaOptions" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Materia 3 (opcional)">
                            <a-select v-model:value="form.id_asignatura_3" allow-clear show-search option-filter-prop="label" :options="asignaturaOptions" />
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-form>
        </a-modal>
    </AdminLayout>
</template>
