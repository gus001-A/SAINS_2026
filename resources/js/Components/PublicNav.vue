<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { confirmAction } from '@/lib/notify';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
const estudiante = computed(() => page.props.auth?.estudiante ?? null);

const nombre = computed(
    () => estudiante.value?.nombre_completo?.trim() || user.value?.correo || 'Mi cuenta',
);
const iniciales = computed(() => {
    const n = nombre.value.split(' ').filter(Boolean);
    return ((n[0]?.[0] ?? 'S') + (n[1]?.[0] ?? '')).toUpperCase();
});

const mobileOpen = ref(false);
const currentPath = computed(() => {
    try { return new URL(page.url, 'http://x').pathname; } catch (e) { return page.url; }
});
const enInicio = computed(() => currentPath.value === '/' || currentPath.value === '');

const links = [
    { label: 'Inicio', href: '/', icon: 'home', match: () => enInicio.value },
    { label: 'Nosotros', href: '/#nosotros', icon: 'info' },
    { label: 'Método', href: '/#metodo', icon: 'grid' },
    { label: 'Docentes', href: '/#docentes', icon: 'users' },
    { label: 'Plan Premium', href: '/#plan', icon: 'star' },
    { label: 'Contacto', href: '/#contacto', icon: 'mail' },
];

const icons = {
    home: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z',
    grid: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
    users: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    star: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
    mail: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    login: 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
    userplus: 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
    logout: 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
    chevron: 'M19 9l-7 7-7-7',
};

function go(href) {
    mobileOpen.value = false;

    // Enlace "Inicio"
    if (href === '/') {
        if (enInicio.value) window.scrollTo({ top: 0, behavior: 'smooth' });
        else router.visit('/');
        return;
    }

    // Enlaces con ancla hacia el landing (/#seccion)
    if (href.startsWith('/#')) {
        const id = href.slice(2);
        if (enInicio.value) {
            const el = document.getElementById(id);
            history.replaceState(null, '', href);
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        } else {
            // Recarga completa: Welcome.vue hace scroll a la sección al montar.
            window.location.href = href;
        }
        return;
    }

    router.visit(href);
}

function irA(routeName) {
    mobileOpen.value = false;
    router.visit(route(routeName));
}

function logout() {
    mobileOpen.value = false;
    confirmAction({
        title: '¿Cerrar sesión?',
        content: 'Tendrás que volver a iniciar sesión para continuar.',
        okText: 'Sí, salir',
        danger: true,
        tone: 'logout',
        onOk: () => router.post(route('logout')),
    });
}
</script>

<template>
    <nav class="pnav">
        <div class="pnav__inner">
            <!-- Logo -->
            <Link href="/" class="pnav__brand">
                <img src="/images/logo_u.png" alt="SAINS" />
                <span class="pnav__brand-underline"></span>
            </Link>

            <!-- Links desktop -->
            <div class="pnav__links">
                <a
                    v-for="l in links"
                    :key="l.label"
                    :href="l.href"
                    class="pnav__link"
                    :class="{ 'is-active': l.match && l.match() }"
                    @click.prevent="go(l.href)"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path :d="icons[l.icon]" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>{{ l.label }}</span>
                    <span v-if="l.match && l.match()" class="pnav__dot"></span>
                </a>
            </div>

            <!-- Acciones -->
            <div class="pnav__actions">
                <template v-if="user">
                    <a-dropdown placement="bottomRight" :trigger="['click']" overlay-class-name="pnav-drop">
                        <button class="pnav__user">
                            <span class="pnav__avatar">{{ iniciales }}</span>
                            <span class="pnav__uname">{{ nombre }}</span>
                            <svg class="pnav__caret" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path :d="icons.chevron" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <template #overlay>
                            <div class="pnav-menu">
                                <div class="pnav-menu__head">
                                    <span class="pnav-menu__avatar">{{ iniciales }}</span>
                                    <div>
                                        <div class="pnav-menu__name">{{ nombre }}</div>
                                        <div class="pnav-menu__mail">{{ user.correo }}</div>
                                    </div>
                                </div>
                                <button class="pnav-menu__item" @click="irA('estudiante.dashboard')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path :d="icons.home" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                    Mi curso
                                </button>
                                <button class="pnav-menu__item" @click="irA('estudiante.perfil')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path :d="icons.userplus" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                    Mi perfil
                                </button>
                                <div class="pnav-menu__sep"></div>
                                <button class="pnav-menu__item is-danger" @click="logout">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path :d="icons.logout" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                    Cerrar sesión
                                </button>
                            </div>
                        </template>
                    </a-dropdown>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="pnav__btn pnav__btn--ghost">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path :d="icons.login" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        <span>Iniciar sesión</span>
                    </Link>
                    <Link :href="route('registro')" class="pnav__btn pnav__btn--solid">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path :d="icons.userplus" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        <span>Crear cuenta</span>
                    </Link>
                </template>

                <button class="pnav__burger" :class="{ open: mobileOpen }" @click="mobileOpen = !mobileOpen" aria-label="Menú">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>

        <!-- Menú móvil -->
        <transition name="pnav-m">
            <div v-if="mobileOpen" class="pnav__mobile">
                <a
                    v-for="l in links"
                    :key="l.label"
                    :href="l.href"
                    class="pnav__mlink"
                    :class="{ 'is-active': l.match && l.match() }"
                    @click.prevent="go(l.href)"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path :d="icons[l.icon]" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    {{ l.label }}
                </a>
                <div class="pnav__msep"></div>
                <template v-if="user">
                    <button class="pnav__mlink" @click="irA('estudiante.dashboard')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path :d="icons.home" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        Mi curso
                    </button>
                    <button class="pnav__mlink is-danger" @click="logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path :d="icons.logout" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        Cerrar sesión
                    </button>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="pnav__btn pnav__btn--ghost pnav__btn--block" @click="mobileOpen = false">Iniciar sesión</Link>
                    <Link :href="route('registro')" class="pnav__btn pnav__btn--solid pnav__btn--block" @click="mobileOpen = false">Crear cuenta</Link>
                </template>
            </div>
        </transition>
    </nav>
</template>

<style scoped>
.pnav {
    position: sticky;
    top: 0;
    z-index: 900;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: saturate(180%) blur(14px);
    border-bottom: 1px solid rgba(226, 232, 240, 0.7);
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
}
.pnav__inner {
    max-width: 1200px;
    margin: 0 auto;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 0 clamp(16px, 4vw, 32px);
}

/* Marca */
.pnav__brand {
    position: relative;
    display: flex;
    align-items: center;
    flex: none;
}
.pnav__brand img {
    height: 42px;
    display: block;
    transition: transform 0.3s ease;
}
.pnav__brand:hover img { transform: scale(1.05); }
.pnav__brand-underline {
    position: absolute;
    left: 0;
    bottom: -2px;
    height: 2.5px;
    width: 0;
    border-radius: 3px;
    background: linear-gradient(90deg, #1d4ed8, #4f46e5, #f59e0b);
    transition: width 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.pnav__brand:hover .pnav__brand-underline { width: 100%; }

/* Links */
.pnav__links {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
    justify-content: center;
}
.pnav__link {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 13px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    white-space: nowrap;
    transition: color 0.2s ease, background 0.2s ease;
}
.pnav__link svg { width: 15px; height: 15px; opacity: 0.75; transition: transform 0.2s ease; }
.pnav__link:hover { color: #1d4ed8; background: rgba(29, 78, 216, 0.06); }
.pnav__link:hover svg { transform: translateY(-1px); }
.pnav__link.is-active { color: #1e3a8a; background: rgba(29, 78, 216, 0.09); }
.pnav__dot {
    position: absolute;
    left: 50%;
    bottom: 1px;
    transform: translateX(-50%);
    width: 18px;
    height: 2.5px;
    border-radius: 3px;
    background: #1d4ed8;
}

/* Acciones */
.pnav__actions { display: flex; align-items: center; gap: 8px; flex: none; }
.pnav__btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 650;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.2s ease, color 0.2s ease;
}
.pnav__btn svg { width: 15px; height: 15px; }
.pnav__btn--ghost {
    color: #1d4ed8;
    background: transparent;
    box-shadow: inset 0 0 0 1.5px rgba(29, 78, 216, 0.35);
}
.pnav__btn--ghost:hover { background: rgba(29, 78, 216, 0.08); box-shadow: inset 0 0 0 1.5px #1d4ed8; }
.pnav__btn--solid {
    position: relative;
    overflow: hidden;
    color: #fff;
    background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
    box-shadow: 0 12px 26px -12px rgba(79, 70, 229, 0.6);
}
.pnav__btn--solid:hover { transform: translateY(-2px); box-shadow: 0 16px 34px -12px rgba(79, 70, 229, 0.75); color: #fff; }
.pnav__btn--solid::after {
    content: '';
    position: absolute;
    top: 0;
    left: -60%;
    width: 40%;
    height: 100%;
    background: linear-gradient(115deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transform: skewX(-20deg);
    animation: pnav-shine 4.5s ease-in-out infinite;
}
@keyframes pnav-shine { 0%, 60% { left: -60%; } 80%, 100% { left: 130%; } }
.pnav__btn--block { width: 100%; justify-content: center; }

/* Menú de usuario */
.pnav__user {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 5px 12px 5px 5px;
    border: 1px solid #e8eaf6;
    border-radius: 999px;
    background: linear-gradient(135deg, #f6f7fb, #eef1fb);
    cursor: pointer;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
}
.pnav__user:hover { border-color: #c7d2fe; box-shadow: 0 6px 16px -10px rgba(29, 78, 216, 0.5); transform: translateY(-1px); }
.pnav__avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    flex: none;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1d4ed8, #4f46e5);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 4px 10px -4px rgba(29, 78, 216, 0.6);
}
.pnav__uname {
    font-size: 12.5px;
    font-weight: 650;
    color: #0f172a;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.pnav__caret { width: 14px; height: 14px; color: #94a3b8; }

/* Hamburguesa */
.pnav__burger {
    display: none;
    width: 34px;
    height: 30px;
    padding: 5px 3px;
    border: none;
    background: none;
    cursor: pointer;
    flex-direction: column;
    justify-content: space-between;
}
.pnav__burger span {
    display: block;
    height: 3px;
    border-radius: 3px;
    background: #1e3a8a;
    transition: transform 0.28s ease, opacity 0.2s ease;
}
.pnav__burger.open span:nth-child(1) { transform: translateY(8.5px) rotate(45deg); }
.pnav__burger.open span:nth-child(2) { opacity: 0; }
.pnav__burger.open span:nth-child(3) { transform: translateY(-8.5px) rotate(-45deg); }

/* Menú móvil */
.pnav__mobile {
    display: none;
    flex-direction: column;
    gap: 3px;
    padding: 10px 16px 16px;
    border-top: 1px solid rgba(226, 232, 240, 0.7);
    background: #fff;
}
.pnav__mlink {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 11px 12px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    border: none;
    background: none;
    cursor: pointer;
    text-align: left;
    transition: background 0.15s ease, color 0.15s ease, transform 0.15s ease;
}
.pnav__mlink svg { width: 17px; height: 17px; opacity: 0.75; }
.pnav__mlink:hover { background: rgba(29, 78, 216, 0.06); color: #1d4ed8; transform: translateX(3px); }
.pnav__mlink.is-active { background: rgba(29, 78, 216, 0.09); color: #1e3a8a; }
.pnav__mlink.is-danger { color: #dc2626; }
.pnav__mlink.is-danger:hover { background: #fee2e2; color: #b91c1c; }
.pnav__msep { height: 1px; background: #eef1f6; margin: 6px 2px; }

.pnav-m-enter-active, .pnav-m-leave-active { transition: opacity 0.22s ease, transform 0.22s ease; }
.pnav-m-enter-from, .pnav-m-leave-to { opacity: 0; transform: translateY(-8px); }

@media (max-width: 900px) {
    .pnav__links { display: none; }
    /* Los CTAs de invitado viven en el menú móvil */
    .pnav__actions > .pnav__btn:not(.pnav__btn--block) { display: none; }
    .pnav__burger { display: flex; }
    .pnav__mobile { display: flex; }
    .pnav__uname { display: none; }
}
</style>

<style>
/* Overlay del dropdown de usuario (no scoped, se renderiza fuera) */
.pnav-drop .ant-dropdown-menu { padding: 0; background: transparent; box-shadow: none; }
.pnav-menu {
    width: 248px;
    background: #fff;
    border-radius: 16px;
    border: 1px solid #eef1f6;
    box-shadow: 0 22px 50px -18px rgba(15, 23, 42, 0.3);
    overflow: hidden;
    padding: 6px;
}
.pnav-menu__head {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 13px 12px 14px;
    margin: -6px -6px 6px;
    background: linear-gradient(135deg, #1d4ed8, #4f46e5);
    color: #fff;
}
.pnav-menu__avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    flex: none;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.16);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
}
.pnav-menu__name { font-size: 13.5px; font-weight: 700; line-height: 1.2; }
.pnav-menu__mail {
    font-size: 11.5px;
    color: rgba(255, 255, 255, 0.8);
    margin-top: 2px;
    max-width: 172px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.pnav-menu__item {
    display: flex;
    align-items: center;
    gap: 11px;
    width: 100%;
    padding: 9px 11px;
    border: 0;
    background: transparent;
    cursor: pointer;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 550;
    color: #475569;
    transition: background 0.14s ease, color 0.14s ease;
}
.pnav-menu__item svg { width: 15px; height: 15px; color: #4f46e5; }
.pnav-menu__item:hover { background: #eef2ff; color: #1e3a8a; }
.pnav-menu__item.is-danger { color: #dc2626; }
.pnav-menu__item.is-danger svg { color: #dc2626; }
.pnav-menu__item.is-danger:hover { background: #fee2e2; color: #b91c1c; }
.pnav-menu__sep { height: 1px; background: #eef1f6; margin: 5px 2px; }
</style>
