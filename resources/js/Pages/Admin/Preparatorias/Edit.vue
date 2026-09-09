<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { BankOutlined } from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import FormPage from '@/Components/FormPage.vue';
import PreparatoriaForm from './PreparatoriaForm.vue';

const props = defineProps({
    preparatoria: { type: Object, required: true },
    estados: { type: Array, default: () => [] },
});

const p = props.preparatoria;
const form = useForm({
    centro_educativo: p.centro_educativo ?? '',
    clave: p.clave ?? '',
    estado: p.estado || undefined,
    municipio: p.municipio ?? '',
    localidad: p.localidad ?? '',
    tipo: p.tipo || undefined,
    ambito: p.ambito || undefined,
    turno: p.turno || undefined,
    servicio: p.servicio ?? '',
    direccion: p.direccion ?? '',
});

const back = () => router.visit(route('admin.preparatorias.index'));
const submit = () => form.put(route('admin.preparatorias.update', p.id));
</script>

<template>
    <AdminLayout title="Editar preparatoria">
        <PageHead :title="p.centro_educativo" subtitle="Editar preparatoria" :icon="BankOutlined" back @back="back" />

        <FormPage submit-label="Actualizar" :processing="form.processing" max-width="900px" @submit="submit" @cancel="back">
            <PreparatoriaForm :form="form" :estados="estados" />
        </FormPage>
    </AdminLayout>
</template>
