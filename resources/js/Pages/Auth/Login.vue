<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import {
    MailOutlined, LockOutlined, LoginOutlined, GoogleOutlined, UserAddOutlined,
} from '@ant-design/icons-vue';
import AuthShell from '@/Components/AuthShell.vue';
import { message } from '@/lib/notify';

const form = useForm({ email: '', password: '', remember: false });

function submit() {
    form.post(route('login'), {
        onSuccess: () => message.success('¡Bienvenido de vuelta!'),
        onError: (e) => {
            form.reset('password');
            message.error(e.email || e.password || 'No pudimos iniciar tu sesión. Revisa tus datos.');
        },
    });
}
</script>

<template>
    <AuthShell title="Iniciar sesión">
        <template #title>Bienvenido de vuelta</template>
        <template #subtitle>Ingresa a tu cuenta para continuar</template>

        <a-form layout="vertical" class="au-form" @submitcapture.prevent>
            <a-form-item :validate-status="form.errors.email ? 'error' : ''" :help="form.errors.email">
                <a-input v-model:value="form.email" type="email" size="large" placeholder="Correo" autocomplete="email" @press-enter="submit">
                    <template #prefix><MailOutlined /></template>
                </a-input>
            </a-form-item>

            <a-form-item :validate-status="form.errors.password ? 'error' : ''" :help="form.errors.password">
                <a-input-password v-model:value="form.password" size="large" placeholder="Contraseña" autocomplete="current-password" @press-enter="submit">
                    <template #prefix><LockOutlined /></template>
                </a-input-password>
            </a-form-item>

            <div class="au-row">
                <a-checkbox v-model:checked="form.remember">Recordarme</a-checkbox>
                <Link :href="route('password.request')" class="au-link">¿Olvidaste tu contraseña?</Link>
            </div>

            <a-button type="primary" size="large" block :loading="form.processing" @click="submit">
                <template #icon><LoginOutlined /></template>
                Iniciar sesión
            </a-button>
        </a-form>

        <template #google>
            <a-button size="large" block class="au-google" :href="route('auth.google')">
                <template #icon><GoogleOutlined /></template>
                Continuar con Google
            </a-button>
        </template>

        <template #footer>
            ¿No tienes cuenta?
            <Link :href="route('registro')" class="au-link au-link--strong">
                <UserAddOutlined /> Crear cuenta gratis
            </Link>
        </template>
    </AuthShell>
</template>

<style scoped>
.au-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: -2px 0 16px;
    font-size: 13px;
}
.au-link { color: #1d4ed8; font-weight: 600; }
.au-link:hover { color: #1e3a8a; }
.au-link--strong { display: inline-flex; align-items: center; gap: 5px; }
.au-google { color: #334155; }
.au-google:hover { border-color: #1d4ed8; color: #1e3a8a; }
</style>
