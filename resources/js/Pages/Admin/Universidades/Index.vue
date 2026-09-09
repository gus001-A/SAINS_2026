<script setup>
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { PlusOutlined, SearchOutlined, BankOutlined, FilterOutlined } from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import StatCard from '@/Components/StatCard.vue';
import RowActions from '@/Components/RowActions.vue';
import { useIndex, laravelPagination } from '@/lib/useIndex';
import { confirmDelete } from '@/lib/notify';

const props = defineProps({
    universidades: { type: Object, required: true },
    estados: { type: Array, default: () => [] },
    carreras: { type: Array, default: () => [] },
    stats: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const { filters, loading, reset, toPage, hasActiveFilters } = useIndex({
    routeName: 'admin.universidades.index',
    initial: {
        search: props.filters.search ?? '',
        estado: props.filters.estado ?? undefined,
        municipio: props.filters.municipio ?? '',
        carrera_id: props.filters.carrera_id ?? undefined,
        tipo: props.filters.tipo ?? undefined,
    },
    debounced: ['search', 'municipio'],
    only: ['universidades', 'stats', 'filters'],
});

const pagination = computed(() => laravelPagination(props.universidades, 'universidades'));
const carreraOptions = computed(() => props.carreras.map((c) => ({ value: c.id, label: c.nombre })));
const tipos = ['PUBLICA', 'PRIVADA', 'AUTONOMA'];

const modalOpen = ref(false);
const editing = ref(null);
const form = useForm({
    clave: '', estado: undefined, municipio: '', localidad: '',
    carrera_id: undefined, tipo: 'PUBLICA', duracion: '', direccion: '',
});

function openCreate() {
    editing.value = null;
    form.reset();
    form.tipo = 'PUBLICA';
    form.clearErrors();
    modalOpen.value = true;
}
function openEdit(r) {
    editing.value = r;
    Object.assign(form, {
        clave: r.clave, estado: r.estado, municipio: r.municipio, localidad: r.localidad,
        carrera_id: r.carrera_id, tipo: r.tipo, duracion: r.duracion, direccion: r.direccion,
    });
    form.clearErrors();
    modalOpen.value = true;
}
function submit() {
    const opts = { onSuccess: () => (modalOpen.value = false), preserveScroll: true };
    editing.value
        ? form.put(route('admin.universidades.update', editing.value.id), opts)
        : form.post(route('admin.universidades.store'), opts);
}
function eliminar(r) {
    confirmDelete({ title: '¿Eliminar universidad?', content: r.clave, onOk: () => router.delete(route('admin.universidades.destroy', r.id), { preserveScroll: true }) });
}

const tipoColor = (t) => (t === 'PRIVADA' ? 'gold' : t === 'AUTONOMA' ? 'purple' : 'blue');
const columns = [
    { title: 'Clave', dataIndex: 'clave', key: 'clave', width: 130 },
    { title: 'Estado', dataIndex: 'estado', key: 'estado', width: 170 },
    { title: 'Municipio', dataIndex: 'municipio', key: 'municipio', width: 150 },
    { title: 'Carrera', key: 'carrera' },
    { title: 'Tipo', dataIndex: 'tipo', key: 'tipo', width: 120 },
    { title: '', key: 'acciones', width: 96, align: 'right' },
];
</script>

<template>
    <AdminLayout title="Universidades">
        <PageHead title="Universidades" subtitle="Instituciones de destino" :icon="BankOutlined">
            <template #actions>
                <a-button v-if="hasActiveFilters()" @click="reset"><template #icon><FilterOutlined /></template>Limpiar</a-button>
                <a-button type="primary" @click="openCreate"><template #icon><PlusOutlined /></template>Nueva universidad</a-button>
            </template>
        </PageHead>

        <div class="sains-stats sains-stats--4">
            <StatCard label="Total" :value="stats.total" color="indigo" />
            <StatCard label="Públicas" :value="stats.publicas" color="green" />
            <StatCard label="Privadas" :value="stats.privadas" color="amber" />
            <StatCard label="Autónomas" :value="stats.autonomas" color="violet" />
        </div>

        <a-card :bordered="false" :body-style="{ padding: '4px 4px 12px' }">
            <a-table class="filtered-table" :columns="columns" :data-source="universidades.data" :pagination="pagination"
                :loading="loading" row-key="id" size="middle" :scroll="{ x: 940 }" @change="(p) => toPage(p.current)">
                <template #emptyText><a-empty :image="null" description="Sin resultados" /></template>

                <template #summary>
                    <a-table-summary-row>
                        <a-table-summary-cell v-for="(col, i) in columns" :key="col.key ?? i" :index="i">
                            <a-input v-if="col.key === 'clave'" v-model:value="filters.search" size="small" allow-clear placeholder="Clave, estado…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-select v-else-if="col.key === 'estado'" v-model:value="filters.estado" size="small" allow-clear show-search placeholder="Todos"
                                :options="estados.map((e) => ({ value: e, label: e }))" />
                            <a-input v-else-if="col.key === 'municipio'" v-model:value="filters.municipio" size="small" allow-clear placeholder="Municipio…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-select v-else-if="col.key === 'carrera'" v-model:value="filters.carrera_id" size="small" allow-clear show-search
                                option-filter-prop="label" placeholder="Todas las carreras" :options="carreraOptions" />
                            <a-select v-else-if="col.key === 'tipo'" v-model:value="filters.tipo" size="small" allow-clear placeholder="Todos"
                                :options="tipos.map((t) => ({ value: t, label: t }))" />
                        </a-table-summary-cell>
                    </a-table-summary-row>
                </template>

                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'clave'"><span class="sains-strong">{{ record.clave }}</span></template>
                    <template v-else-if="column.key === 'carrera'"><span :class="record.carrera ? '' : 'sains-faint'">{{ record.carrera?.nombre || '—' }}</span></template>
                    <template v-else-if="column.key === 'tipo'"><a-tag :bordered="false" :color="tipoColor(record.tipo)">{{ record.tipo }}</a-tag></template>
                    <template v-else-if="column.key === 'acciones'">
                        <RowActions editable @edit="openEdit(record)" @delete="eliminar(record)" />
                    </template>
                </template>
            </a-table>
        </a-card>

        <a-modal v-model:open="modalOpen" :title="editing ? 'Editar universidad' : 'Nueva universidad'" class="sains-modal" :width="640" :confirm-loading="form.processing" ok-text="Guardar" @ok="submit">
            <a-form layout="vertical">
                <a-row :gutter="16">
                    <a-col :xs="24" :md="8">
                        <a-form-item label="Clave" required :validate-status="form.errors.clave ? 'error' : undefined" :help="form.errors.clave">
                            <a-input v-model:value="form.clave" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="16">
                        <a-form-item label="Carrera" required :validate-status="form.errors.carrera_id ? 'error' : undefined" :help="form.errors.carrera_id">
                            <a-select v-model:value="form.carrera_id" show-search option-filter-prop="label" :options="carreraOptions" placeholder="Selecciona…" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="8">
                        <a-form-item label="Estado" required :validate-status="form.errors.estado ? 'error' : undefined" :help="form.errors.estado">
                            <a-select v-model:value="form.estado" show-search :options="estados.map((e) => ({ value: e, label: e }))" placeholder="Selecciona…" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="8">
                        <a-form-item label="Municipio" required :validate-status="form.errors.municipio ? 'error' : undefined" :help="form.errors.municipio">
                            <a-input v-model:value="form.municipio" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="8">
                        <a-form-item label="Localidad"><a-input v-model:value="form.localidad" /></a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="8">
                        <a-form-item label="Tipo"><a-select v-model:value="form.tipo" :options="tipos.map((t) => ({ value: t, label: t }))" /></a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="16">
                        <a-form-item label="Duración"><a-input v-model:value="form.duracion" placeholder="p. ej. 4 años" /></a-form-item>
                    </a-col>
                    <a-col :span="24">
                        <a-form-item label="Dirección"><a-textarea v-model:value="form.direccion" :rows="2" /></a-form-item>
                    </a-col>
                </a-row>
            </a-form>
        </a-modal>
    </AdminLayout>
</template>
