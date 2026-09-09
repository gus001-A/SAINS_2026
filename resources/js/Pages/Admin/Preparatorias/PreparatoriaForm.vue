<script setup>
import {
    BankOutlined, NumberOutlined, EnvironmentOutlined, PushpinOutlined,
    ApartmentOutlined, ClockCircleOutlined, HomeOutlined,
} from '@ant-design/icons-vue';

defineProps({
    form: { type: Object, required: true },
    estados: { type: Array, default: () => [] },
});

const tipos = ['PUBLICO', 'PRIVADO'];
const ambitos = ['URBANO', 'RURAL'];
const turnos = ['MATUTINO', 'VESPERTINO', 'NOCTURNO', 'MIXTO', 'DISCONTINUO'];

const err = (form, field) => (form.errors[field] ? 'error' : undefined);
</script>

<template>
    <a-form layout="vertical" class="sains-form-inner">
        <a-divider orientation="left" style="margin-top: 0"><span class="fsec"><BankOutlined />Identificación</span></a-divider>
        <a-row :gutter="16">
            <a-col :xs="24" :md="16">
                <a-form-item required :validate-status="err(form, 'centro_educativo')" :help="form.errors.centro_educativo">
                    <template #label><BankOutlined />Centro educativo</template>
                    <a-input v-model:value="form.centro_educativo" placeholder="Nombre del plantel">
                        <template #prefix><BankOutlined /></template>
                    </a-input>
                </a-form-item>
            </a-col>
            <a-col :xs="24" :md="8">
                <a-form-item :validate-status="err(form, 'clave')" :help="form.errors.clave">
                    <template #label><NumberOutlined />Clave</template>
                    <a-input v-model:value="form.clave" />
                </a-form-item>
            </a-col>
        </a-row>

        <a-divider orientation="left"><span class="fsec"><EnvironmentOutlined />Ubicación</span></a-divider>
        <a-row :gutter="16">
            <a-col :xs="24" :md="8">
                <a-form-item required :validate-status="err(form, 'estado')" :help="form.errors.estado">
                    <template #label><EnvironmentOutlined />Estado</template>
                    <a-select v-model:value="form.estado" show-search placeholder="Selecciona…" :options="estados.map((e) => ({ value: e, label: e }))" />
                </a-form-item>
            </a-col>
            <a-col :xs="24" :md="8">
                <a-form-item required :validate-status="err(form, 'municipio')" :help="form.errors.municipio">
                    <template #label><PushpinOutlined />Municipio</template>
                    <a-input v-model:value="form.municipio" />
                </a-form-item>
            </a-col>
            <a-col :xs="24" :md="8">
                <a-form-item :validate-status="err(form, 'localidad')" :help="form.errors.localidad">
                    <template #label><PushpinOutlined />Localidad</template>
                    <a-input v-model:value="form.localidad" />
                </a-form-item>
            </a-col>
            <a-col :span="24">
                <a-form-item label="Dirección">
                    <a-textarea v-model:value="form.direccion" :rows="2" placeholder="Calle, número, colonia…" />
                </a-form-item>
            </a-col>
        </a-row>

        <a-divider orientation="left"><span class="fsec"><ApartmentOutlined />Clasificación</span></a-divider>
        <a-row :gutter="16">
            <a-col :xs="24" :md="8">
                <a-form-item>
                    <template #label><ApartmentOutlined />Tipo</template>
                    <a-select v-model:value="form.tipo" allow-clear placeholder="Selecciona…" :options="tipos.map((t) => ({ value: t, label: t }))" />
                </a-form-item>
            </a-col>
            <a-col :xs="24" :md="8">
                <a-form-item>
                    <template #label><EnvironmentOutlined />Ámbito</template>
                    <a-select v-model:value="form.ambito" allow-clear placeholder="Selecciona…" :options="ambitos.map((a) => ({ value: a, label: a }))" />
                </a-form-item>
            </a-col>
            <a-col :xs="24" :md="8">
                <a-form-item>
                    <template #label><ClockCircleOutlined />Turno</template>
                    <a-select v-model:value="form.turno" allow-clear placeholder="Selecciona…" :options="turnos.map((t) => ({ value: t, label: t }))" />
                </a-form-item>
            </a-col>
            <a-col :xs="24" :md="12">
                <a-form-item>
                    <template #label><HomeOutlined />Servicio</template>
                    <a-input v-model:value="form.servicio" />
                </a-form-item>
            </a-col>
        </a-row>
    </a-form>
</template>
