<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { UserOutlined, UploadOutlined, DeleteOutlined } from '@ant-design/icons-vue';
import EstudianteLayout from '@/Layouts/EstudianteLayout.vue';
import PhoneInput from '@/Components/PhoneInput.vue';
import DateField from '@/Components/DateField.vue';
import { message, confirmAction } from '@/lib/notify';

const props = defineProps({
    estudianteData: { type: Object, required: true },
    universidades: { type: Array, default: () => [] },
});

const d = props.estudianteData;

const perfil = reactive({
    nombre: d.nombre ?? '', paterno: d.paterno ?? '', materno: d.materno ?? '',
    telefono: d.telefono ?? '', fecha_nacimiento: d.fecha_nacimiento ?? null,
    sexo: d.sexo ?? undefined, correo: d.correo ?? '',
});
const perfilErrors = ref({});
const guardando = ref(false);

const pass = reactive({ password_actual: '', password_nueva: '', password_nueva_confirmation: '' });
const passErrors = ref({});
const cambiandoPass = ref(false);

const fotoUrl = ref(d.foto_url);
const subiendoFoto = ref(false);

async function guardarPerfil() {
    guardando.value = true;
    perfilErrors.value = {};
    try {
        const { data } = await axios.put(route('estudiante.perfil.actualizar'), perfil);
        if (data.success) {
            message.success(data.message || 'Perfil actualizado');
            router.reload({ only: ['auth'] });
        } else {
            message.error(data.message || 'No se pudo actualizar');
        }
    } catch (e) {
        if (e.response?.status === 422) perfilErrors.value = e.response.data.errors || {};
        message.error(e.response?.data?.message || 'Error al actualizar el perfil');
    } finally {
        guardando.value = false;
    }
}

async function cambiarPassword() {
    if (pass.password_nueva !== pass.password_nueva_confirmation) {
        passErrors.value = { password_nueva_confirmation: ['Las contraseñas no coinciden'] };
        return;
    }
    cambiandoPass.value = true;
    passErrors.value = {};
    try {
        const { data } = await axios.post(route('estudiante.perfil.cambiar-password'), pass);
        if (data.success) {
            message.success(data.message || 'Contraseña cambiada');
            pass.password_actual = pass.password_nueva = pass.password_nueva_confirmation = '';
        } else {
            message.error(data.message || 'No se pudo cambiar la contraseña');
        }
    } catch (e) {
        if (e.response?.status === 422) passErrors.value = e.response.data.errors || {};
        message.error(e.response?.data?.message || 'Error al cambiar la contraseña');
    } finally {
        cambiandoPass.value = false;
    }
}

async function subirFoto(file) {
    const okType = /^image\//.test(file.type);
    const okSize = file.size / 1024 / 1024 < 2;
    if (!okType) { message.error('Selecciona una imagen.'); return false; }
    if (!okSize) { message.error('La imagen supera los 2 MB.'); return false; }
    subiendoFoto.value = true;
    const fd = new FormData();
    fd.append('foto', file);
    try {
        const { data } = await axios.post(route('estudiante.subir.foto'), fd);
        if (data.success) {
            fotoUrl.value = data.foto_url;
            message.success('Foto actualizada');
            router.reload({ only: ['auth'] });
        } else {
            message.error(data.message || 'No se pudo subir la foto');
        }
    } catch (e) {
        message.error(e.response?.data?.message || 'Error al subir la foto');
    } finally {
        subiendoFoto.value = false;
    }
    return false;
}

function eliminarFoto() {
    confirmAction({
        title: '¿Eliminar tu foto de perfil?',
        content: 'Volverás a mostrar tus iniciales como avatar.',
        okText: 'Sí, eliminar',
        danger: true,
        tone: 'delete',
        onOk: async () => {
            try {
                const { data } = await axios.delete(route('estudiante.eliminar.foto'));
                if (data.success) {
                    fotoUrl.value = null;
                    message.success('Foto eliminada');
                    router.reload({ only: ['auth'] });
                } else {
                    message.error(data.message || 'No se pudo eliminar');
                }
            } catch (e) {
                message.error(e.response?.data?.message || 'Error al eliminar la foto');
            }
        },
    });
}

const err = (bag, k) => (bag && bag[k] ? bag[k][0] : '');
</script>

<template>
    <EstudianteLayout title="Mi perfil">
        <section class="sains-hero">
            <div class="sains-hero__grid">
                <div>
                    <span class="sains-hero__eyebrow"><UserOutlined /> Mi cuenta</span>
                    <h1 class="sains-hero__title">{{ perfil.nombre }} {{ perfil.paterno }}</h1>
                    <p class="sains-hero__sub">Administra tus datos personales, tu foto y tu contraseña.</p>
                </div>
            </div>
        </section>

        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :md="8">
                <a-card :bordered="false" class="perfil-foto">
                    <div class="perfil-foto__cover"></div>
                    <div class="perfil-foto__avatar">
                        <a-avatar :src="fotoUrl" :size="96">
                            <template v-if="!fotoUrl" #icon><UserOutlined /></template>
                        </a-avatar>
                    </div>
                    <h3>{{ perfil.nombre }} {{ perfil.paterno }}</h3>
                    <p class="perfil-foto__mail">{{ perfil.correo }}</p>
                    <a-tag :color="estudianteData.plan_activo ? 'green' : 'default'" style="margin-bottom: 4px">
                        {{ estudianteData.plan_activo ? 'Plan Premium activo' : 'Plan básico' }}
                    </a-tag>
                    <a-space direction="vertical" style="width: 100%; margin-top: 14px">
                        <a-upload :before-upload="subirFoto" :show-upload-list="false" accept="image/*">
                            <a-button block :loading="subiendoFoto"><template #icon><UploadOutlined /></template>Cambiar foto</a-button>
                        </a-upload>
                        <a-button v-if="fotoUrl" block danger @click="eliminarFoto">
                            <template #icon><DeleteOutlined /></template>Quitar foto
                        </a-button>
                    </a-space>
                </a-card>
            </a-col>

            <a-col :xs="24" :md="16">
                <a-card :bordered="false" title="Datos personales">
                    <a-form layout="vertical">
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="12">
                                <a-form-item label="Nombre(s)" :validate-status="err(perfilErrors,'nombre') ? 'error' : ''" :help="err(perfilErrors,'nombre')">
                                    <a-input v-model:value="perfil.nombre" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12">
                                <a-form-item label="Apellido paterno">
                                    <a-input v-model:value="perfil.paterno" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12">
                                <a-form-item label="Apellido materno">
                                    <a-input v-model:value="perfil.materno" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12">
                                <a-form-item label="Teléfono">
                                    <PhoneInput v-model:value="perfil.telefono" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12">
                                <a-form-item label="Fecha de nacimiento">
                                    <DateField v-model:value="perfil.fecha_nacimiento" limite="nacimiento" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12">
                                <a-form-item label="Sexo">
                                    <a-select v-model:value="perfil.sexo" :options="[
                                        { value: 'M', label: 'Masculino' }, { value: 'F', label: 'Femenino' }]" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24">
                                <a-form-item label="Correo electrónico" :validate-status="err(perfilErrors,'correo') ? 'error' : ''" :help="err(perfilErrors,'correo')">
                                    <a-input v-model:value="perfil.correo" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <div class="sains-section-actions">
                            <a-button type="primary" :loading="guardando" @click="guardarPerfil">Guardar cambios</a-button>
                        </div>
                    </a-form>
                </a-card>

                <a-card :bordered="false" title="Cambiar contraseña" style="margin-top: 16px">
                    <a-form layout="vertical">
                        <a-form-item label="Contraseña actual" :validate-status="err(passErrors,'password_actual') ? 'error' : ''" :help="err(passErrors,'password_actual')">
                            <a-input-password v-model:value="pass.password_actual" />
                        </a-form-item>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="12">
                                <a-form-item label="Nueva contraseña" :validate-status="err(passErrors,'password_nueva') ? 'error' : ''" :help="err(passErrors,'password_nueva')">
                                    <a-input-password v-model:value="pass.password_nueva" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="12">
                                <a-form-item label="Confirmar contraseña" :validate-status="err(passErrors,'password_nueva_confirmation') ? 'error' : ''" :help="err(passErrors,'password_nueva_confirmation')">
                                    <a-input-password v-model:value="pass.password_nueva_confirmation" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <div class="sains-section-actions">
                            <a-button :loading="cambiandoPass" @click="cambiarPassword">Actualizar contraseña</a-button>
                        </div>
                    </a-form>
                </a-card>
            </a-col>
        </a-row>
    </EstudianteLayout>
</template>

<style scoped>
.perfil-foto { text-align: center; overflow: hidden; position: relative; padding-top: 0; }
.perfil-foto :deep(.ant-card-body) { padding-top: 0; }
.perfil-foto__cover {
    height: 78px; margin: 0 -24px 0; background: linear-gradient(135deg, #4f46e5, #9333ea);
}
.perfil-foto__avatar {
    margin-top: -48px; display: inline-block; padding: 4px; border-radius: 50%; background: #fff;
    box-shadow: 0 6px 18px -8px rgba(15, 23, 42, .3);
}
.perfil-foto h3 { margin: 12px 0 2px; }
.perfil-foto__mail { color: #94a3b8; font-size: 12.5px; margin: 0 0 10px; word-break: break-all; }
</style>
