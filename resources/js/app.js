import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import dayjs from 'dayjs';
import 'dayjs/locale/es-mx';

import 'ant-design-vue/dist/reset.css';
import '../css/app.css';

// Calendarios / fechas relativas en español (México).
dayjs.locale('es-mx');

const appName = import.meta.env.VITE_APP_NAME || 'SAINS';

/* Tras un deploy, los chunks viejos dejan de existir: recargar en vez de romper. */
window.addEventListener('vite:preloadError', () => window.location.reload());
router.on('exception', (event) => {
    const msg = event.detail?.exception?.message ?? '';
    if (/dynamically imported module|Importing a module script failed/i.test(msg)) {
        event.preventDefault();
        window.location.reload();
    }
});

createInertiaApp({
    title: (title) => (title ? `${title} · ${appName}` : appName),

    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },

    progress: {
        color: '#4f46e5',
        showSpinner: false,
    },
});
