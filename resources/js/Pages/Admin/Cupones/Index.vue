<script setup>
import { computed, ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import {
    PlusOutlined, SearchOutlined, TagsOutlined, ThunderboltOutlined, ReloadOutlined,
    ShareAltOutlined, WhatsAppOutlined, MailOutlined, CopyOutlined,
} from '@ant-design/icons-vue';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import StatCard from '@/Components/StatCard.vue';
import RowActions from '@/Components/RowActions.vue';
import { confirmDelete, message } from '@/lib/notify';

const props = defineProps({
    cupones: { type: Object, required: true },
    codigoSugerido: { type: String, default: '' },
    stats: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search ?? '');
const estatus = ref(props.filters.estatus ?? undefined);
const tipo = ref(props.filters.tipo_descuento ?? undefined);
const expira = ref(props.filters.expira ?? undefined);
const generador = ref(props.filters.generador ?? '');
let debounce = null;

function reload(extra = {}) {
    router.get(route('admin.cupones.index'),
        {
            search: search.value || undefined,
            estatus: estatus.value || undefined,
            tipo_descuento: tipo.value || undefined,
            expira: expira.value || undefined,
            generador: generador.value || undefined,
            ...extra,
        },
        { preserveState: true, replace: true, preserveScroll: true });
}
watch([search, generador], () => { clearTimeout(debounce); debounce = setTimeout(() => reload(), 350); });
watch([estatus, tipo, expira], () => reload());

const estatusOpts = [{ value: 'activo', label: 'Activo' }, { value: 'inactivo', label: 'Inactivo' }, { value: 'expirado', label: 'Expirado' }];
const tipoOpts = [{ value: 'porcentaje', label: 'Porcentaje (%)' }, { value: 'cantidad_fija', label: 'Cantidad fija ($)' }];
const expiraOpts = [
    { value: 'vigentes', label: 'Vigentes' },
    { value: 'expirados', label: 'Expirados' },
    { value: 'proximos_7_dias', label: 'Próx. 7 días' },
    { value: 'con_fecha', label: 'Con fecha' },
    { value: 'sin_fecha', label: 'Sin fecha' },
];

// ---- Crear / editar ----
const modalOpen = ref(false);
const editing = ref(null);
const form = useForm({
    codigo: '', estatus: 'activo', tipo_descuento: 'porcentaje', valor_descuento: 10,
    fecha_expiracion: null, usado: false,
});

function openCreate() {
    editing.value = null;
    form.reset();
    form.codigo = props.codigoSugerido;
    form.clearErrors();
    modalOpen.value = true;
}
function openEdit(r) {
    editing.value = r;
    form.codigo = r.codigo;
    form.estatus = r.estatus;
    form.tipo_descuento = r.tipo_descuento;
    form.valor_descuento = r.valor_descuento;
    form.fecha_expiracion = r.fecha_expiracion;
    form.usado = r.usado;
    form.clearErrors();
    modalOpen.value = true;
}
async function regenerar() {
    try {
        const { data } = await axios.post(route('admin.cupones.regenerar'));
        if (data.success) form.codigo = data.codigo;
    } catch (e) { /* noop */ }
}
function submit() {
    const opts = { onSuccess: () => { modalOpen.value = false; } };
    if (editing.value) form.put(route('admin.cupones.update', editing.value.id), opts);
    else form.post(route('admin.cupones.store'), opts);
}
function eliminar(r) {
    confirmDelete({ title: '¿Eliminar cupón?', content: r.codigo, onOk: () => router.delete(route('admin.cupones.destroy', r.id), { preserveScroll: true }) });
}

// ---- Masivo ----
const masivoOpen = ref(false);
const masivo = useForm({ cantidad: 10, estatus: 'activo', tipo_descuento: 'porcentaje', valor_descuento: 10, fecha_expiracion: null });
function submitMasivo() {
    masivo.post(route('admin.cupones.masivo'), { onSuccess: () => { masivoOpen.value = false; masivo.reset(); } });
}

const pagination = computed(() => ({
    current: props.cupones.current_page,
    pageSize: props.cupones.per_page,
    total: props.cupones.total,
    showSizeChanger: false,
    showTotal: (t) => `${t} cupones`,
}));
function onChange(pag) { reload({ page: pag.current }); }

function descuentoLabel(r) {
    return r.tipo_descuento === 'porcentaje' ? `${r.valor_descuento}%` : `$${Number(r.valor_descuento).toLocaleString('es-MX')}`;
}

// ---- Compartir ----
const shareOpen = ref(false);
const shareCupon = ref(null);
const shareTelefono = ref('');
const shareCorreo = ref('');

const shareMsg = computed(() => {
    const r = shareCupon.value;
    if (!r) return '';
    const origin = typeof window !== 'undefined' ? window.location.origin : '';
    return `¡Hola! Te comparto un cupón de descuento para el Curso Premium SAINS 2026.\n\n`
        + `Código: ${r.codigo}\n`
        + `Descuento: ${descuentoLabel(r)}\n`
        + (r.fecha_expiracion ? `Válido hasta: ${r.fecha_expiracion}\n` : '')
        + `\nRegístrate y aplícalo en: ${origin}`;
});
const telDigits = computed(() => shareTelefono.value.replace(/\D/g, ''));
const whatsappUrl = computed(() => {
    const base = telDigits.value ? `https://wa.me/${telDigits.value}` : 'https://wa.me/';
    return `${base}?text=${encodeURIComponent(shareMsg.value)}`;
});
const mailtoUrl = computed(() => {
    const to = shareCorreo.value.trim();
    return `mailto:${encodeURIComponent(to)}?subject=${encodeURIComponent('Cupón de descuento · Curso Premium SAINS 2026')}&body=${encodeURIComponent(shareMsg.value)}`;
});
const correoValido = computed(() => !shareCorreo.value || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(shareCorreo.value.trim()));

function openShare(r) {
    shareCupon.value = r;
    shareTelefono.value = '';
    shareCorreo.value = '';
    shareOpen.value = true;
}
async function copiar(text) {
    try {
        await navigator.clipboard.writeText(text);
        message.success('Copiado al portapapeles');
    } catch (e) {
        message.error('No se pudo copiar');
    }
}
function estadoTag(r) {
    if (r.usado) return { color: 'default', text: 'Usado' };
    if (r.expirado || r.estatus === 'expirado') return { color: 'red', text: 'Expirado' };
    if (r.estatus === 'inactivo') return { color: 'orange', text: 'Inactivo' };
    return { color: 'green', text: 'Activo' };
}

const columns = [
    { title: 'Código', dataIndex: 'codigo', key: 'codigo', width: 134 },
    { title: 'Descuento', key: 'descuento', width: 130 },
    { title: 'Estado', key: 'estado', width: 120 },
    { title: 'Expira', dataIndex: 'fecha_expiracion', key: 'expira', width: 150 },
    { title: 'Generó', dataIndex: 'generador', key: 'generador', width: 180 },
    { title: '', key: 'acciones', width: 110, align: 'right' },
];
</script>

<template>
    <AdminLayout title="Cupones">
        <PageHead title="Cupones" subtitle="Descuentos para el curso premium" :icon="TagsOutlined">
            <template #actions>
                <a-button @click="masivoOpen = true"><template #icon><ThunderboltOutlined /></template>Generar masivo</a-button>
                <a-button type="primary" @click="openCreate"><template #icon><PlusOutlined /></template>Nuevo cupón</a-button>
            </template>
        </PageHead>

        <div class="sains-stats sains-stats--4">
            <StatCard label="Total" :value="stats.total" color="indigo" />
            <StatCard label="Activos" :value="stats.activos" color="green" />
            <StatCard label="Usados" :value="stats.usados" color="slate" />
            <StatCard label="Expirados" :value="stats.expirados" color="red" />
        </div>

        <a-card :bordered="false">
            <a-table class="filtered-table" :columns="columns" :data-source="cupones.data" :pagination="pagination" row-key="id" size="middle" :scroll="{ x: 900 }" @change="onChange">
                <template #summary>
                    <a-table-summary-row>
                        <a-table-summary-cell v-for="(col, i) in columns" :key="col.key ?? i" :index="i">
                            <a-input v-if="col.key === 'codigo'" v-model:value="search" size="small" allow-clear placeholder="Código…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                            <a-select v-else-if="col.key === 'estado'" v-model:value="estatus" size="small" allow-clear placeholder="Todos" :options="estatusOpts" />
                            <a-select v-else-if="col.key === 'descuento'" v-model:value="tipo" size="small" allow-clear placeholder="Todos" :options="tipoOpts" />
                            <a-select v-else-if="col.key === 'expira'" v-model:value="expira" size="small" allow-clear placeholder="Todas" :options="expiraOpts" />
                            <a-input v-else-if="col.key === 'generador'" v-model:value="generador" size="small" allow-clear placeholder="Generó…">
                                <template #prefix><SearchOutlined /></template>
                            </a-input>
                        </a-table-summary-cell>
                    </a-table-summary-row>
                </template>
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'codigo'">
                        <a-tooltip title="Copiar código">
                            <span class="cup-code" @click="copiar(record.codigo)">
                                <span class="cup-code__txt">{{ record.codigo }}</span>
                                <CopyOutlined class="cup-code__ic" />
                            </span>
                        </a-tooltip>
                    </template>
                    <template v-else-if="column.key === 'descuento'">
                        <a-tag :bordered="false" :color="record.tipo_descuento === 'porcentaje' ? 'purple' : 'geekblue'">
                            {{ descuentoLabel(record) }}
                        </a-tag>
                    </template>
                    <template v-else-if="column.key === 'estado'">
                        <a-tag :bordered="false" :color="estadoTag(record).color">{{ estadoTag(record).text }}</a-tag>
                    </template>
                    <template v-else-if="column.key === 'expira'">
                        <span :class="record.fecha_expiracion ? '' : 'sains-faint'">{{ record.fecha_expiracion || 'Sin fecha' }}</span>
                    </template>
                    <template v-else-if="column.key === 'acciones'">
                        <RowActions editable :delete-disabled="record.usado" @edit="openEdit(record)" @delete="eliminar(record)">
                            <template #extra>
                                <a-tooltip title="Compartir">
                                    <button type="button" class="row-actions__btn is-copy" @click.stop="openShare(record)">
                                        <ShareAltOutlined />
                                    </button>
                                </a-tooltip>
                            </template>
                        </RowActions>
                    </template>
                </template>
            </a-table>
        </a-card>

        <!-- Crear / editar -->
        <a-modal v-model:open="modalOpen" :title="editing ? 'Editar cupón' : 'Nuevo cupón'" class="sains-modal" :width="560" :confirm-loading="form.processing" ok-text="Guardar" @ok="submit">
            <a-form layout="vertical">
                <a-form-item label="Código" required :validate-status="form.errors.codigo ? 'error' : undefined" :help="form.errors.codigo">
                    <a-input-group compact>
                        <a-input v-model:value="form.codigo" style="width: calc(100% - 40px)" />
                        <a-button @click="regenerar"><template #icon><ReloadOutlined /></template></a-button>
                    </a-input-group>
                </a-form-item>
                <a-row :gutter="16">
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Tipo de descuento" required>
                            <a-select v-model:value="form.tipo_descuento" :options="tipoOpts" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Valor" required :validate-status="form.errors.valor_descuento ? 'error' : undefined" :help="form.errors.valor_descuento">
                            <a-input-number v-model:value="form.valor_descuento" :min="0" :max="form.tipo_descuento === 'porcentaje' ? 100 : undefined" style="width: 100%" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Estado" required>
                            <a-select v-model:value="form.estatus" :options="estatusOpts" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Fecha de expiración" :validate-status="form.errors.fecha_expiracion ? 'error' : undefined" :help="form.errors.fecha_expiracion">
                            <a-date-picker v-model:value="form.fecha_expiracion" value-format="YYYY-MM-DD" style="width: 100%" />
                        </a-form-item>
                    </a-col>
                    <a-col v-if="editing" :span="24">
                        <a-form-item>
                            <a-checkbox v-model:checked="form.usado">Marcar como usado</a-checkbox>
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-form>
        </a-modal>

        <!-- Masivo -->
        <a-modal v-model:open="masivoOpen" title="Generar cupones en masa" class="sains-modal" :width="520" :confirm-loading="masivo.processing" ok-text="Generar" @ok="submitMasivo">
            <a-form layout="vertical">
                <a-form-item label="Cantidad (1-100)" required :validate-status="masivo.errors.cantidad ? 'error' : undefined" :help="masivo.errors.cantidad">
                    <a-input-number v-model:value="masivo.cantidad" :min="1" :max="100" style="width: 100%" />
                </a-form-item>
                <a-row :gutter="16">
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Tipo de descuento" required>
                            <a-select v-model:value="masivo.tipo_descuento" :options="tipoOpts" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Valor" required>
                            <a-input-number v-model:value="masivo.valor_descuento" :min="0" style="width: 100%" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Estado" required>
                            <a-select v-model:value="masivo.estatus" :options="estatusOpts" />
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :md="12">
                        <a-form-item label="Fecha de expiración">
                            <a-date-picker v-model:value="masivo.fecha_expiracion" value-format="YYYY-MM-DD" style="width: 100%" />
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-form>
        </a-modal>

        <!-- Compartir -->
        <a-modal v-model:open="shareOpen" :width="470" :footer="null" centered destroy-on-close
            wrap-class-name="share-modal" :closable="true">
            <div v-if="shareCupon" class="share-box">
                <div class="share-hero">
                    <span class="share-hero__ic"><ShareAltOutlined /></span>
                    <div>
                        <div class="share-hero__title">Compartir cupón</div>
                        <div class="share-hero__sub">Envíalo por WhatsApp o correo</div>
                    </div>
                </div>

                <div class="share-ticket">
                    <div class="share-ticket__left">
                        <span class="share-ticket__label">Código</span>
                        <span class="share-ticket__code">{{ shareCupon.codigo }}</span>
                    </div>
                    <div class="share-ticket__right">{{ descuentoLabel(shareCupon) }}</div>
                </div>

                <div class="share-field">
                    <label>Número de WhatsApp <span>(opcional)</span></label>
                    <a-input v-model:value="shareTelefono" placeholder="Ej. 55 1234 5678" inputmode="tel">
                        <template #prefix><WhatsAppOutlined style="color:#25D366" /></template>
                    </a-input>
                </div>
                <div class="share-field">
                    <label>Correo electrónico <span>(opcional)</span></label>
                    <a-input v-model:value="shareCorreo" placeholder="alumno@correo.com"
                        :status="correoValido ? undefined : 'error'">
                        <template #prefix><MailOutlined style="color:#6366f1" /></template>
                    </a-input>
                </div>

                <a-textarea :value="shareMsg" :rows="5" readonly class="share-msg" />

                <div class="share-actions">
                    <a class="share-btn is-wa" :href="whatsappUrl" target="_blank" rel="noopener">
                        <WhatsAppOutlined /> {{ telDigits ? 'Enviar por WhatsApp' : 'Abrir WhatsApp' }}
                    </a>
                    <a class="share-btn is-mail" :class="{ 'is-disabled': !correoValido }"
                        :href="correoValido ? mailtoUrl : undefined">
                        <MailOutlined /> {{ shareCorreo.trim() ? 'Enviar por correo' : 'Abrir correo' }}
                    </a>
                    <button type="button" class="share-btn is-copy" @click="copiar(shareMsg)">
                        <CopyOutlined /> Copiar mensaje
                    </button>
                </div>
            </div>
        </a-modal>
    </AdminLayout>
</template>

<style>
.share-modal .ant-modal-content { padding: 0 !important; overflow: hidden; }
.share-modal .ant-modal-body { padding: 0 !important; }
.share-modal .ant-modal-close { color: #fff; top: 16px; inset-inline-end: 16px; }
.share-modal .ant-modal-close:hover { background: rgba(255, 255, 255, .18); color: #fff; }
</style>

<style scoped>
.cup-code {
    display: inline-flex; align-items: center; gap: 5px; cursor: pointer; max-width: 100%;
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-weight: 700; font-size: 12px;
    color: #4f46e5; background: #eef2ff; padding: 2px 8px; border-radius: 7px;
    transition: background .14s ease;
}
.cup-code:hover { background: #e0e7ff; }
.cup-code__txt { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cup-code__ic { font-size: 10px; opacity: .5; flex: none; }

.share-box { display: flex; flex-direction: column; }
.share-hero {
    display: flex; align-items: center; gap: 13px;
    padding: 20px 24px; background: var(--sains-grad); color: #fff;
}
.share-hero__ic {
    width: 42px; height: 42px; flex: none; border-radius: 12px; font-size: 19px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255, 255, 255, .18);
}
.share-hero__title { font-weight: 700; font-size: 16px; letter-spacing: -.01em; }
.share-hero__sub { font-size: 12.5px; opacity: .85; }

.share-ticket {
    display: flex; align-items: center; justify-content: space-between; gap: 14px;
    margin: 18px 24px 4px; padding: 14px 18px; border-radius: 14px;
    background: #f8fafc; border: 1px dashed #c7d2fe;
}
.share-ticket__label { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; margin-bottom: 3px; }
.share-ticket__code { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-weight: 700; font-size: 17px; color: #0f172a; }
.share-ticket__right {
    flex: none; font-weight: 800; font-size: 15px; color: #4f46e5;
    background: #eef2ff; padding: 6px 12px; border-radius: 10px;
}

.share-field { margin: 12px 24px 0; }
.share-field label { display: block; font-size: 12.5px; font-weight: 600; color: #475569; margin-bottom: 5px; }
.share-field label span { color: #94a3b8; font-weight: 400; }
.share-field :deep(.ant-input-affix-wrapper) { border-radius: 10px; }

.share-msg { margin: 14px 24px 0; font-size: 12.5px; border-radius: 10px; }

.share-actions { display: flex; flex-direction: column; gap: 9px; padding: 16px 24px 22px; }
.share-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    height: 42px; border-radius: 11px; border: 0; cursor: pointer;
    font-size: 13.5px; font-weight: 600; transition: transform .12s ease, box-shadow .12s ease, filter .12s ease;
}
.share-btn:hover { transform: translateY(-1px); }
.share-btn.is-wa { background: #25D366; color: #fff; box-shadow: 0 8px 18px -8px rgba(37, 211, 102, .6); }
.share-btn.is-mail { background: #4f46e5; color: #fff; box-shadow: 0 8px 18px -8px rgba(79, 70, 229, .6); }
.share-btn.is-copy { background: #f1f5f9; color: #475569; }
.share-btn.is-copy:hover { background: #e2e8f0; }
.share-btn.is-disabled { opacity: .45; pointer-events: none; }
</style>
