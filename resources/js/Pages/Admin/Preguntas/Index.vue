<script setup>
import { computed, ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { PlusOutlined, SearchOutlined, QuestionCircleOutlined, CheckCircleFilled, MinusCircleOutlined } from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import StatCard from '@/Components/StatCard.vue';
import RowActions from '@/Components/RowActions.vue';
import { confirmDelete } from '@/lib/notify';

const props = defineProps({
    preguntas: { type: Object, required: true },
    areas: { type: Array, default: () => [] },
    stats: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search ?? '');
const respCorrecta = ref(props.filters.respuesta_correcta ?? '');
const idArea = ref(props.filters.id_area ?? undefined);
const hasJust = ref(props.filters.has_justificacion ?? undefined);
let debounce = null;

function reload(extra = {}) {
    router.get(route('admin.preguntas.index'),
        {
            search: search.value || undefined,
            respuesta_correcta: respCorrecta.value || undefined,
            id_area: idArea.value || undefined,
            has_justificacion: hasJust.value || undefined,
            ...extra,
        },
        { preserveState: true, replace: true, preserveScroll: true });
}
watch([search, respCorrecta], () => { clearTimeout(debounce); debounce = setTimeout(() => reload(), 350); });
watch([idArea, hasJust], () => reload());

const areaOptions = computed(() => props.areas.map((a) => ({ value: a.id, label: a.nombre })));

const modalOpen = ref(false);
const editing = ref(null);
const form = useForm({
    id_area: undefined, pregunta: '', respuesta_correcta: '', respuesta1: '', respuesta2: '', justificacion: '',
});

function openCreate() {
    editing.value = null;
    form.reset();
    form.clearErrors();
    modalOpen.value = true;
}
function openEdit(r) {
    editing.value = r;
    form.id_area = r.id_area;
    form.pregunta = r.pregunta;
    form.respuesta_correcta = r.respuesta_correcta;
    form.respuesta1 = r.respuesta1;
    form.respuesta2 = r.respuesta2;
    form.justificacion = r.justificacion ?? '';
    form.clearErrors();
    modalOpen.value = true;
}
function submit() {
    const opts = { onSuccess: () => { modalOpen.value = false; } };
    if (editing.value) form.put(route('admin.preguntas.update', editing.value.id), opts);
    else form.post(route('admin.preguntas.store'), opts);
}
function eliminar(r) {
    confirmDelete({ title: '¿Eliminar pregunta?', content: r.pregunta.slice(0, 80), onOk: () => router.delete(route('admin.preguntas.destroy', r.id), { preserveScroll: true }) });
}

const pagination = computed(() => ({
    current: props.preguntas.current_page,
    pageSize: props.preguntas.per_page,
    total: props.preguntas.total,
    showSizeChanger: false,
    showTotal: (t) => `${t} preguntas`,
}));
function onChange(pag) { reload({ page: pag.current }); }

const columns = [
    { title: 'Pregunta', key: 'pregunta' },
    { title: 'Área', dataIndex: 'area', key: 'area', width: 170 },
    { title: 'Respuesta correcta', dataIndex: 'respuesta_correcta', key: 'correcta', width: 200 },
    { title: 'Justif.', key: 'justif', width: 90, align: 'center' },
    { title: '', key: 'acciones', width: 100, align: 'right' },
];
</script>

<template>
    <AdminLayout title="Preguntas">
        <PageHead title="Preguntas" subtitle="Banco de reactivos por área" :icon="QuestionCircleOutlined">
            <template #actions>
                <a-button type="primary" @click="openCreate"><template #icon><PlusOutlined /></template>Nueva pregunta</a-button>
            </template>
        </PageHead>

        <div class="sains-stats sains-stats--4">
            <StatCard label="Preguntas" :value="stats.total" color="indigo" />
            <StatCard label="Áreas" :value="stats.areas" color="violet" />
            <StatCard label="Con justificación" :value="stats.conJustificacion" color="green" />
            <StatCard label="Sin justificación" :value="stats.sinJustificacion" color="slate" />
        </div>

        <a-card :bordered="false">
            <a-table class="filtered-table" :columns="columns" :data-source="preguntas.data" :pagination="pagination" row-key="id" size="middle" :scroll="{ x: 900 }" @change="onChange">
                <template #summary>
                    <a-table-summary-row>
                        <a-table-summary-cell v-for="(col, i) in columns" :key="col.key ?? i" :index="i">
                            <a-input v-if="col.key === 'pregunta'" v-model:value="search" size="small" allow-clear placeholder="Buscar texto…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-input v-else-if="col.key === 'correcta'" v-model:value="respCorrecta" size="small" allow-clear placeholder="Respuesta…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-select v-else-if="col.key === 'area'" v-model:value="idArea" size="small" allow-clear show-search
                                option-filter-prop="label" placeholder="Todas" :options="areaOptions" />
                            <a-select v-else-if="col.key === 'justif'" v-model:value="hasJust" size="small" allow-clear placeholder="Todas"
                                :options="[{ value: 'si', label: 'Con' }, { value: 'no', label: 'Sin' }]" />
                        </a-table-summary-cell>
                    </a-table-summary-row>
                </template>
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'pregunta'">
                        <div style="max-width: 420px">{{ record.pregunta }}</div>
                    </template>
                    <template v-else-if="column.key === 'correcta'">
                        <span class="sains-strong">{{ record.respuesta_correcta }}</span>
                    </template>
                    <template v-else-if="column.key === 'justif'">
                        <a-tooltip :title="record.justificacion || 'Sin justificación'">
                            <span class="just-flag" :class="record.justificacion ? 'is-yes' : 'is-no'">
                                <CheckCircleFilled v-if="record.justificacion" />
                                <MinusCircleOutlined v-else />
                                {{ record.justificacion ? 'Sí' : 'No' }}
                            </span>
                        </a-tooltip>
                    </template>
                    <template v-else-if="column.key === 'acciones'">
                        <RowActions editable @edit="openEdit(record)" @delete="eliminar(record)" />
                    </template>
                </template>
            </a-table>
        </a-card>

        <a-modal v-model:open="modalOpen" :title="editing ? 'Editar pregunta' : 'Nueva pregunta'" class="sains-modal" :width="640" :confirm-loading="form.processing" ok-text="Guardar" @ok="submit">
            <a-form layout="vertical">
                <a-form-item label="Área" required :validate-status="form.errors.id_area ? 'error' : undefined" :help="form.errors.id_area">
                    <a-select v-model:value="form.id_area" show-search option-filter-prop="label" :options="areaOptions" placeholder="Selecciona…" />
                </a-form-item>
                <a-form-item label="Pregunta" required :validate-status="form.errors.pregunta ? 'error' : undefined" :help="form.errors.pregunta">
                    <a-textarea v-model:value="form.pregunta" :rows="3" />
                </a-form-item>
                <a-form-item label="Respuesta correcta" required :validate-status="form.errors.respuesta_correcta ? 'error' : undefined" :help="form.errors.respuesta_correcta">
                    <a-input v-model:value="form.respuesta_correcta" />
                </a-form-item>
                <a-row :gutter="16">
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Distractor 1" required :validate-status="form.errors.respuesta1 ? 'error' : undefined" :help="form.errors.respuesta1">
                            <a-input v-model:value="form.respuesta1" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Distractor 2" required :validate-status="form.errors.respuesta2 ? 'error' : undefined" :help="form.errors.respuesta2">
                            <a-input v-model:value="form.respuesta2" />
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-form-item label="Justificación (opcional)">
                    <a-textarea v-model:value="form.justificacion" :rows="2" />
                </a-form-item>
            </a-form>
        </a-modal>
    </AdminLayout>
</template>

<style scoped>
.just-flag {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 999px;
}
.just-flag.is-yes { background: #dcfce7; color: #15803d; }
.just-flag.is-no { background: #f1f5f9; color: #94a3b8; }
</style>
