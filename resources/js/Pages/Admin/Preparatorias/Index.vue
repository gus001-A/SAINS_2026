<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import {
    PlusOutlined, SearchOutlined, BankOutlined, FilterOutlined,
} from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import StatCard from '@/Components/StatCard.vue';
import RowActions from '@/Components/RowActions.vue';
import { useIndex, laravelPagination } from '@/lib/useIndex';
import { confirmDelete } from '@/lib/notify';

const props = defineProps({
    preparatorias: { type: Object, required: true },
    estados: { type: Array, default: () => [] },
    stats: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const { filters, loading, reset, toPage, hasActiveFilters } = useIndex({
    routeName: 'admin.preparatorias.index',
    initial: {
        search: props.filters.search ?? '',
        clave: props.filters.clave ?? '',
        municipio: props.filters.municipio ?? '',
        estado: props.filters.estado ?? undefined,
        tipo: props.filters.tipo ?? undefined,
    },
    debounced: ['search', 'clave', 'municipio'],
    only: ['preparatorias', 'stats', 'filters'],
});

const pagination = computed(() => laravelPagination(props.preparatorias, 'preparatorias'));

function eliminar(record) {
    confirmDelete({
        title: '¿Eliminar preparatoria?',
        content: record.centro_educativo,
        onOk: () => router.delete(route('admin.preparatorias.destroy', record.id), { preserveScroll: true }),
    });
}

const columns = [
    { title: 'Centro educativo', dataIndex: 'centro_educativo', key: 'centro_educativo' },
    { title: 'Clave', dataIndex: 'clave', key: 'clave', width: 140 },
    { title: 'Estado', dataIndex: 'estado', key: 'estado', width: 180 },
    { title: 'Municipio', dataIndex: 'municipio', key: 'municipio', width: 160 },
    { title: 'Tipo', dataIndex: 'tipo', key: 'tipo', width: 130 },
    { title: '', key: 'acciones', width: 96, align: 'right' },
];
</script>

<template>
    <AdminLayout title="Preparatorias">
        <PageHead title="Preparatorias" subtitle="Catálogo de planteles de origen" :icon="BankOutlined">
            <template #actions>
                <a-button v-if="hasActiveFilters()" @click="reset">
                    <template #icon><FilterOutlined /></template>Limpiar filtros
                </a-button>
                <Link :href="route('admin.preparatorias.create')">
                    <a-button type="primary"><template #icon><PlusOutlined /></template>Nueva preparatoria</a-button>
                </Link>
            </template>
        </PageHead>

        <div class="sains-stats sains-stats--3">
            <StatCard label="Total" :value="stats.total" color="indigo" />
            <StatCard label="Públicas" :value="stats.publicas" color="green" />
            <StatCard label="Privadas" :value="stats.privadas" color="amber" />
        </div>

        <a-card :bordered="false" :body-style="{ padding: '4px 4px 12px' }">
            <a-table
                class="filtered-table"
                :columns="columns"
                :data-source="preparatorias.data"
                :pagination="pagination"
                :loading="loading"
                row-key="id"
                size="middle"
                :scroll="{ x: 900 }"
                @change="(p) => toPage(p.current)"
            >
                <template #emptyText><a-empty :image="null" description="Sin resultados" /></template>

                <template #summary>
                    <a-table-summary-row>
                        <a-table-summary-cell v-for="(col, i) in columns" :key="col.key ?? i" :index="i">
                            <a-input v-if="col.key === 'centro_educativo'" v-model:value="filters.search" size="small" allow-clear placeholder="Buscar nombre o clave…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-input v-else-if="col.key === 'clave'" v-model:value="filters.clave" size="small" allow-clear placeholder="Clave…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-input v-else-if="col.key === 'municipio'" v-model:value="filters.municipio" size="small" allow-clear placeholder="Municipio…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-select v-else-if="col.key === 'estado'" v-model:value="filters.estado" size="small" allow-clear show-search placeholder="Todos"
                                :options="estados.map((e) => ({ value: e, label: e }))" />
                            <a-select v-else-if="col.key === 'tipo'" v-model:value="filters.tipo" size="small" allow-clear placeholder="Todos"
                                :options="[{ value: 'PUBLICO', label: 'Público' }, { value: 'PRIVADO', label: 'Privado' }]" />
                        </a-table-summary-cell>
                    </a-table-summary-row>
                </template>

                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'centro_educativo'">
                        <span class="sains-strong">{{ record.centro_educativo }}</span>
                    </template>
                    <template v-else-if="column.key === 'clave'">
                        <span class="sains-faint" style="font-size: 12.5px">{{ record.clave || '—' }}</span>
                    </template>
                    <template v-else-if="column.key === 'tipo'">
                        <a-tag v-if="record.tipo" :bordered="false" :color="record.tipo === 'PRIVADO' ? 'gold' : 'blue'">{{ record.tipo }}</a-tag>
                        <span v-else class="sains-faint">—</span>
                    </template>
                    <template v-else-if="column.key === 'acciones'">
                        <RowActions :edit-href="route('admin.preparatorias.edit', record.id)" @delete="eliminar(record)" />
                    </template>
                </template>
            </a-table>
        </a-card>
    </AdminLayout>
</template>
