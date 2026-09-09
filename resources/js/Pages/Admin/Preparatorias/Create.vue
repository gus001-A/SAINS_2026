<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { BankOutlined } from '@ant-design/icons-vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHead from '@/Components/PageHead.vue';
import FormPage from '@/Components/FormPage.vue';
import PreparatoriaForm from './PreparatoriaForm.vue';

defineProps({ estados: { type: Array, default: () => [] } });

const form = useForm({
    centro_educativo: '', clave: '', estado: undefined, municipio: '', localidad: '',
    tipo: undefined, ambito: undefined, turno: undefined, servicio: '', direccion: '',
});

const back = () => router.visit(route('admin.preparatorias.index'));
const submit = () => form.post(route('admin.preparatorias.store'));
</script>

<template>
    <AdminLayout title="Nueva preparatoria">
        <PageHead title="Nueva preparatoria" subtitle="Registrar un plantel de origen" :icon="BankOutlined" back @back="back" />

        <FormPage submit-label="Guardar" :processing="form.processing" max-width="900px" @submit="submit" @cancel="back">
            <PreparatoriaForm :form="form" :estados="estados" />
        </FormPage>
    </AdminLayout>
</template>
