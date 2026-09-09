<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { confirmAction } from '@/lib/notify';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const activePath = ref(1);

/* ---------- Revelado al hacer scroll ---------- */
const io = typeof IntersectionObserver !== 'undefined'
    ? new IntersectionObserver((entries) => {
        for (const e of entries) {
            if (e.isIntersecting) {
                e.target.classList.add('is-in');
                io.unobserve(e.target);
            }
        }
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' })
    : null;

const revealEls = new Set();
const vReveal = {
    mounted(el, binding) {
        const reduce = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;
        if (!io || reduce) { el.classList.add('is-in'); return; }
        if (binding.value != null) el.style.setProperty('--reveal-delay', `${binding.value}ms`);
        el.classList.add('reveal');
        revealEls.add(el);
        // Si ya está visible o por encima al montar (p. ej. entras con #ancla), muéstralo.
        if (el.getBoundingClientRect().top < window.innerHeight * 0.9) {
            requestAnimationFrame(() => el.classList.add('is-in'));
            revealEls.delete(el);
            return;
        }
        io.observe(el);
    },
    unmounted(el) { io?.unobserve(el); revealEls.delete(el); },
};

/* Respaldo del observer: nada debe quedar invisible si el usuario ya pasó de largo. */
let revealRaf = null;
function sweepReveal() {
    revealRaf = null;
    for (const el of revealEls) {
        if (el.getBoundingClientRect().top < window.innerHeight * 0.95) {
            el.classList.add('is-in');
            revealEls.delete(el);
        }
    }
    if (revealEls.size === 0) window.removeEventListener('scroll', onRevealScroll);
}
function onRevealScroll() {
    if (revealRaf == null) revealRaf = requestAnimationFrame(sweepReveal);
}

function scrollTo(id) {
    const el = document.getElementById(id);
    if (!el) return;
    history.replaceState(null, '', `#${id}`);
    el.scrollIntoView({ behavior: 'smooth' });
}

onMounted(() => {
    window.addEventListener('scroll', onRevealScroll, { passive: true });
    setTimeout(sweepReveal, 2500);

    const id = window.location.hash.slice(1);
    if (!id) return;
    const jump = () => {
        const el = document.getElementById(id);
        if (!el) return;
        const html = document.documentElement;
        const prev = html.style.scrollBehavior;
        html.style.scrollBehavior = 'auto';
        el.scrollIntoView();
        html.style.scrollBehavior = prev;
    };
    // Reintenta: las imágenes que cargan después desplazan la sección.
    [40, 200, 500, 900].forEach((t) => setTimeout(jump, t));
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onRevealScroll);
    io?.disconnect();
});

const heroStats = [
    { value: '+85%', label: 'de efectividad' },
    { value: '150+', label: 'clases en video' },
    { value: '5,000+', label: 'ejercicios' },
    { value: '8', label: 'docentes expertos' },
];

const beneficios = [
    { icon: 'fa-laptop-code', title: '100% en línea', desc: 'Estudia desde cualquier lugar, a tu ritmo y en cualquier dispositivo.' },
    { icon: 'fa-chart-line', title: '+85% de efectividad', desc: 'La mayoría de nuestros estudiantes ingresa a la carrera que eligió.' },
    { icon: 'fa-user-graduate', title: 'Docentes certificados', desc: 'Profesores especializados en pruebas de selección universitaria.' },
];

const paths = [
    { id: 1, icon: 'fa-chart-simple', label: 'Mídete', title: 'Exámenes en línea', img: '/images/midete_tips.png',
      desc: 'Practica con nuestros simuladores y descubre tus fortalezas y áreas de oportunidad.',
      items: ['Simuladores tipo examen real', 'Resultados inmediatos', 'Identifica tus áreas de mejora'] },
    { id: 2, icon: 'fa-video', label: 'Mejora', title: 'Aprende con videos y podcast', img: '/images/forta_tips.png',
      desc: 'Estudia de manera fácil desde cualquier dispositivo con nuestro contenido multimedia.',
      items: ['Clases en video bajo demanda', 'Podcast para aprender en movimiento', 'Material descargable'] },
    { id: 3, icon: 'fa-pen-ruler', label: 'Practica', title: 'Resuelve ejercicios interactivos', img: '/images/prac_tips.png',
      desc: 'Nuestro banco de preguntas está basado en el temario oficial de las principales universidades.',
      items: ['Miles de ejercicios tipo examen', 'Retroalimentación inmediata', 'Exámenes imprimibles en PDF'] },
    { id: 4, icon: 'fa-flag-checkered', label: '¡Listo!', title: 'Estás listo para el examen 2026', img: '/images/simula_tips.png',
      desc: 'Con nuestra metodología comprobada, llegarás preparado y con confianza.',
      items: ['Simulacros de tiempo real', 'Estrategias de examen', 'Acompañamiento personalizado'] },
];

const instructors = [
    { name: 'Dr. Giovanni Rios', area: 'Formato de Examen', img: '/images/dr.-giovanni-rios.jpg' },
    { name: 'Lic. Edith Saldaña', area: 'Inglés', img: '/images/lic.-edith-saldana.jpg' },
    { name: 'Dr. Bernardino Brisleño', area: 'Estrategias de Estudio', img: '/images/dr.-bernarnino-brisleno.jpg' },
    { name: 'Mtra. Maria M. Casas', area: 'Matemáticas', img: '/images/mtra.-maria-casas.jpg' },
    { name: 'Mtra. Monse Orellana', area: 'Comprensión Lectora', img: '/images/mtra.-monse-orellana.jpg' },
    { name: 'Lic. Israel Saldaña', area: 'Español', img: '/images/lic.-israel-saldana.jpg' },
    { name: 'Dr. Hector Martinez', area: 'Historia y Cs. Sociales', img: '/images/dr.-hector-matrinez.jpg' },
    { name: 'Mtra. Nilda C. Sanchez', area: 'Ciencias', img: '/images/mtra.-nilda-c.-sanchez.jpg' },
];

const premiumFeatures = [
    '150+ clases en video', '5,000+ ejercicios interactivos',
    'Guías de estudio descargables', 'Simulador ilimitado',
    'Clases en vivo semanales', 'Asesoría personalizada',
    'MasterClass exclusivas', 'Certificado de finalización',
];

const universities = ['unam', 'uam', 'uaem', 'buap', 'upemor', 'utez'];

function selectPlan(openAuth, user) {
    if (user) {
        window.location.href = route('estudiante.checkout');
        return;
    }
    confirmAction({
        title: '¡Excelente elección!',
        content: 'Inicia sesión o crea una cuenta para continuar con tu compra.',
        okText: 'Iniciar sesión',
        onOk: () => openAuth('login'),
    });
}
</script>

<template>
    <PublicLayout v-slot="{ openAuth, user }" title="Curso de ingreso a la universidad 2026">
        <!-- ================= HERO ================= -->
        <section class="hero" id="inicio">
            <div class="hero__bg" aria-hidden="true">
                <span class="hero__orb hero__orb--indigo"></span>
                <span class="hero__orb hero__orb--amber"></span>
                <span class="hero__grid"></span>
                <span class="hero__glow"></span>
            </div>

            <div class="container hero__inner">
                <span class="hero__badge">
                    <span class="hero__badge-dot"></span>
                    Convocatoria 2026 · Cupos limitados
                </span>

                <h1 class="hero__title">
                    Asegura tu lugar en la
                    <span class="hero__hl">universidad de tus sueños</span>
                </h1>

                <p class="hero__sub">
                    Clases en video, simuladores tipo examen real y acompañamiento de docentes
                    certificados. El método más efectivo para el examen de admisión 2026.
                </p>

                <div class="hero__cta">
                    <Link v-if="user" :href="route('estudiante.dashboard')" class="btn-xl btn-xl--solid">
                        <i class="fas fa-graduation-cap"></i> Ir a mi curso
                    </Link>
                    <button v-else class="btn-xl btn-xl--solid" @click="openAuth('register')">
                        <i class="fas fa-rocket"></i> Comienza gratis
                    </button>
                    <a href="#metodo" class="btn-xl btn-xl--ghost" @click.prevent="scrollTo('metodo')">
                        <i class="fas fa-circle-play"></i> Conoce el método
                    </a>
                </div>

                <ul class="hero__stats">
                    <li v-for="s in heroStats" :key="s.label">
                        <strong>{{ s.value }}</strong>
                        <span>{{ s.label }}</span>
                    </li>
                </ul>

                <div class="hero__trust">
                    <span class="hero__trust-label">Preparación para el ingreso a</span>
                    <div class="hero__trust-list">
                        <span v-for="u in universities" :key="u">{{ u.toUpperCase() }}</span>
                    </div>
                </div>
            </div>

            <a class="hero__scroll" href="#nosotros" @click.prevent="scrollTo('nosotros')" aria-label="Bajar">
                <i class="fas fa-chevron-down"></i>
            </a>
        </section>

        <!-- ================= BENEFICIOS ================= -->
        <section class="sec" id="nosotros">
            <div class="container">
                <div class="sec__head" v-reveal>
                    <span class="eyebrow">Por qué SAINS</span>
                    <h2 class="sec__title">Una preparación pensada para que ingreses</h2>
                    <p class="sec__sub">Tres razones por las que miles de estudiantes confían en nosotros cada año.</p>
                </div>

                <div class="row g-4">
                    <div v-for="(b, i) in beneficios" :key="b.title" class="col-md-4">
                        <div class="feature" v-reveal="i * 90">
                            <span class="feature__ic"><i class="fas" :class="b.icon"></i></span>
                            <h3>{{ b.title }}</h3>
                            <p>{{ b.desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= BANNER ADMISIÓN ================= -->
        <section class="sec sec--tight">
            <div class="container">
                <div class="admis" v-reveal>
                    <span class="admis__shape" aria-hidden="true"></span>
                    <div class="row align-items-center g-4">
                        <div class="col-lg-5">
                            <span class="eyebrow eyebrow--light">Fechas clave</span>
                            <h2 class="admis__title">Exámenes de admisión 2026</h2>
                            <div class="admis__date"><i class="fas fa-calendar-days"></i> Mayo – Junio 2026</div>
                            <p class="admis__text">Prepárate con anticipación y llega con ventaja el día del examen.</p>
                            <Link v-if="user" :href="route('estudiante.simulador')" class="btn-xl btn-xl--white">
                                <i class="fas fa-bolt"></i> ¡Practica ya!
                            </Link>
                            <button v-else class="btn-xl btn-xl--white" @click="openAuth('login')">
                                <i class="fas fa-bolt"></i> ¡Practica ya!
                            </button>
                        </div>
                        <div class="col-lg-7">
                            <div class="admis__unis">
                                <div v-for="u in universities" :key="u" class="admis__uni">
                                    <img :src="`/images/logo-${u}.png`" :alt="u.toUpperCase()" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= MÉTODO ================= -->
        <section class="sec sec--alt" id="metodo">
            <div class="container">
                <div class="sec__head" v-reveal>
                    <span class="eyebrow">El método</span>
                    <h2 class="sec__title">Tu ruta de aprendizaje en 4 pasos</h2>
                    <p class="sec__sub">Un camino claro, del diagnóstico al día del examen.</p>
                </div>

                <div class="steps" v-reveal>
                    <button
                        v-for="(p, i) in paths"
                        :key="p.id"
                        class="step"
                        :class="{ 'is-active': activePath === p.id }"
                        @click="activePath = p.id"
                    >
                        <span class="step__num">{{ i + 1 }}</span>
                        <span class="step__ic"><i class="fas" :class="p.icon"></i></span>
                        <span class="step__label">{{ p.label }}</span>
                    </button>
                </div>

                <div
                    v-for="p in paths"
                    v-show="activePath === p.id"
                    :key="'c' + p.id"
                    class="method-panel"
                >
                    <div class="row align-items-center g-4">
                        <div class="col-md-5">
                            <div class="method-panel__media">
                                <img :src="p.img" :alt="p.title" />
                            </div>
                        </div>
                        <div class="col-md-7">
                            <span class="eyebrow">Paso {{ p.id }}</span>
                            <h3 class="method-panel__title">{{ p.title }}</h3>
                            <p class="method-panel__desc">{{ p.desc }}</p>
                            <ul class="ticks">
                                <li v-for="(it, i) in p.items" :key="i">
                                    <i class="fas fa-check"></i> {{ it }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= DOCENTES ================= -->
        <section class="sec" id="docentes">
            <div class="container">
                <div class="sec__head" v-reveal>
                    <span class="eyebrow">Docentes</span>
                    <h2 class="sec__title">Aprende con quienes conocen el examen</h2>
                    <p class="sec__sub">Profesores certificados en pruebas de selección universitaria.</p>
                </div>

                <div class="row g-4">
                    <div v-for="(ins, i) in instructors" :key="ins.name" class="col-lg-3 col-sm-6">
                        <div class="teacher" v-reveal="(i % 4) * 80">
                            <div class="teacher__photo">
                                <img :src="ins.img" :alt="ins.name" loading="lazy" />
                            </div>
                            <div class="teacher__info">
                                <h4>{{ ins.name }}</h4>
                                <span class="teacher__area">{{ ins.area }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= PLAN PREMIUM ================= -->
        <section class="sec sec--alt" id="plan">
            <div class="container">
                <div class="sec__head" v-reveal>
                    <span class="eyebrow">Plan Premium</span>
                    <h2 class="sec__title">Todo lo que necesitas, en un solo plan</h2>
                    <p class="sec__sub">Un pago, acceso completo por un año.</p>
                </div>

                <div class="plan" v-reveal>
                    <span class="plan__shape" aria-hidden="true"></span>
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-5">
                            <span class="plan__tag"><i class="fas fa-star"></i> Más popular</span>
                            <h3 class="plan__name">Curso Premium 2026</h3>
                            <div class="plan__price">
                                <span class="plan__old">$1,200</span>
                                <span class="plan__now">$800<small> MXN</small></span>
                            </div>
                            <p class="plan__note">Pago único · acceso por 1 año</p>
                            <button class="btn-xl btn-xl--pink" @click="selectPlan(openAuth, user)">
                                <i class="fas fa-rocket"></i> Comprar ahora
                            </button>
                            <p class="plan__safe"><i class="fas fa-lock"></i> Pago seguro · Garantía de reembolso</p>
                        </div>
                        <div class="col-lg-7">
                            <ul class="plan__list">
                                <li v-for="f in premiumFeatures" :key="f">
                                    <i class="fas fa-circle-check"></i> {{ f }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="guarantee" v-reveal>
                    <span class="guarantee__ic"><i class="fas fa-shield-halved"></i></span>
                    <div>
                        <h3>Garantía de reembolso</h3>
                        <p>Si no obtienes un lugar en la universidad, te regresamos tu dinero.</p>
                        <p class="guarantee__fine">*Aplica completando el 100% del curso y obteniendo 95+ puntos en el simulador oficial.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= CTA FINAL ================= -->
        <section class="closing">
            <div class="container closing__inner" v-reveal>
                <h2>¿Listo para asegurar tu lugar?</h2>
                <p>Empieza hoy con acceso gratuito a los simuladores. Sin tarjeta, sin compromiso.</p>
                <Link v-if="user" :href="route('estudiante.dashboard')" class="btn-xl btn-xl--white">
                    <i class="fas fa-graduation-cap"></i> Ir a mi curso
                </Link>
                <button v-else class="btn-xl btn-xl--white" @click="openAuth('register')">
                    <i class="fas fa-rocket"></i> Crear cuenta gratis
                </button>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
/* ===================== Tokens locales ===================== */
.hero,
.sec,
.closing {
    --brand: #4f46e5;
    --brand-2: #7c3aed;
    --brand-ink: #1e1b4b;
    --amber: #f59e0b;
    --ink: #0f172a;
    --muted: #64748b;
}

/* ===================== Revelado ===================== */
.reveal {
    opacity: 0;
    transform: translateY(26px);
    transition: opacity 0.6s ease, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    transition-delay: var(--reveal-delay, 0ms);
}
.reveal.is-in { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) {
    .reveal { opacity: 1 !important; transform: none !important; }
}

/* ===================== Botones ===================== */
.btn-xl {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.95rem 2rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.98rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.2s ease, color 0.2s ease;
}
.btn-xl i { font-size: 0.9em; }
.btn-xl--solid {
    background: #fff;
    color: #3730a3;
    box-shadow: 0 18px 40px -14px rgba(0, 0, 0, 0.45);
}
.btn-xl--solid:hover { transform: translateY(-3px); box-shadow: 0 24px 50px -14px rgba(0, 0, 0, 0.55); color: #3730a3; }
.btn-xl--ghost {
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    box-shadow: inset 0 0 0 1.5px rgba(255, 255, 255, 0.35);
}
.btn-xl--ghost:hover { background: rgba(255, 255, 255, 0.16); box-shadow: inset 0 0 0 1.5px #fff; color: #fff; transform: translateY(-3px); }
.btn-xl--white {
    background: #fff;
    color: var(--brand);
    box-shadow: 0 16px 36px -16px rgba(15, 23, 42, 0.5);
}
.btn-xl--white:hover { transform: translateY(-3px); color: var(--brand-2); }
.btn-xl--pink {
    color: #fff;
    background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
    box-shadow: 0 16px 34px -12px rgba(236, 72, 153, 0.55);
    width: 100%;
    justify-content: center;
}
.btn-xl--pink:hover { transform: translateY(-3px); box-shadow: 0 22px 44px -12px rgba(236, 72, 153, 0.7); color: #fff; }

/* ===================== Encabezados de sección ===================== */
.eyebrow {
    display: inline-block;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin-bottom: 0.85rem;
}
.eyebrow--light {
    background: none;
    color: rgba(255, 255, 255, 0.85);
    -webkit-text-fill-color: currentColor;
}
.sec__head {
    max-width: 640px;
    margin: 0 auto 3.25rem;
    text-align: center;
}
.sec__title {
    font-size: clamp(1.7rem, 3.2vw, 2.5rem);
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.02em;
    color: var(--ink);
    margin: 0 0 0.9rem;
}
.sec__sub {
    font-size: 1.05rem;
    color: var(--muted);
    line-height: 1.6;
    margin: 0;
}

/* ===================== Layout de secciones ===================== */
.sec {
    padding: 92px 0;
    position: relative;
}
.sec--tight { padding-top: 0; }
.sec--alt { background: #f5f7ff; }

/* ===================== HERO ===================== */
.hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    color: #fff;
    background: linear-gradient(135deg, #0b1120 0%, #1e1b4b 45%, #312e81 78%, #4338ca 100%);
    padding: 120px 0 90px;
}
.hero__bg { position: absolute; inset: 0; pointer-events: none; }
.hero__orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    opacity: 0.55;
    animation: hero-float 16s ease-in-out infinite;
}
.hero__orb--indigo {
    width: 520px; height: 520px;
    top: -160px; left: -120px;
    background: radial-gradient(circle, #6366f1, transparent 68%);
}
.hero__orb--amber {
    width: 440px; height: 440px;
    bottom: -180px; right: -100px;
    background: radial-gradient(circle, #f59e0b, transparent 66%);
    animation-duration: 20s;
    animation-direction: reverse;
    opacity: 0.4;
}
.hero__grid {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255, 255, 255, 0.16) 1px, transparent 1.4px);
    background-size: 32px 32px;
    mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, #000 30%, transparent 75%);
    -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, #000 30%, transparent 75%);
    opacity: 0.6;
}
.hero__glow {
    position: absolute;
    top: 34%; left: 50%;
    width: 760px; height: 420px;
    transform: translate(-50%, -50%);
    background: radial-gradient(ellipse, rgba(124, 58, 237, 0.5), transparent 70%);
    filter: blur(40px);
}
@keyframes hero-float {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(24px, -28px); }
}

.hero__inner {
    position: relative;
    z-index: 2;
    text-align: center;
    max-width: 860px;
}
.hero__badge {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.5rem 1.2rem;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(4px);
    margin-bottom: 1.6rem;
    animation: hero-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
}
.hero__badge-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #4ade80;
    box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
    animation: hero-pulse 2s infinite;
}
@keyframes hero-pulse {
    0% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.6); }
    70% { box-shadow: 0 0 0 10px rgba(74, 222, 128, 0); }
    100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
}
.hero__title {
    font-size: clamp(2.3rem, 5.4vw, 4.2rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.025em;
    margin: 0 0 1.3rem;
    animation: hero-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.08s both;
}
.hero__hl {
    position: relative;
    background: linear-gradient(105deg, #fbbf24, #fcd34d 45%, #fff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    white-space: nowrap;
}
.hero__sub {
    font-size: clamp(1rem, 1.6vw, 1.2rem);
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.65;
    max-width: 640px;
    margin: 0 auto 2.2rem;
    animation: hero-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.16s both;
}
.hero__cta {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    animation: hero-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.24s both;
}
@keyframes hero-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: none; }
}

.hero__stats {
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem 2.75rem;
    margin: 3.5rem 0 0;
    padding: 1.5rem 1rem;
    border-radius: 22px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    animation: hero-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.34s both;
}
.hero__stats li { display: flex; flex-direction: column; align-items: center; }
.hero__stats strong {
    font-size: clamp(1.5rem, 2.6vw, 2rem);
    font-weight: 800;
    background: linear-gradient(135deg, #fff, #c7d2fe);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}
.hero__stats span { font-size: 0.82rem; color: rgba(255, 255, 255, 0.65); }

.hero__trust {
    margin-top: 2.5rem;
    animation: hero-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.42s both;
}
.hero__trust-label {
    display: block;
    font-size: 0.75rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 0.85rem;
}
.hero__trust-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem 0.6rem;
}
.hero__trust-list span {
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    color: rgba(255, 255, 255, 0.75);
    padding: 0.35rem 0.85rem;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.16);
}
.hero__scroll {
    position: absolute;
    left: 50%;
    bottom: 26px;
    transform: translateX(-50%);
    width: 40px; height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: hero-bounce 2.2s ease-in-out infinite;
}
.hero__scroll:hover { color: #fff; border-color: #fff; }
@keyframes hero-bounce {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(7px); }
}

/* ===================== Beneficios ===================== */
.feature {
    height: 100%;
    background: #fff;
    border: 1px solid #eef1f8;
    border-radius: 24px;
    padding: 2.4rem 2rem;
    text-align: left;
    box-shadow: 0 20px 45px -24px rgba(15, 23, 42, 0.18);
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
}
.feature:hover {
    transform: translateY(-8px);
    border-color: #dfe3ff;
    box-shadow: 0 34px 60px -24px rgba(79, 70, 229, 0.32);
}
.feature__ic {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 58px; height: 58px;
    border-radius: 16px;
    font-size: 1.4rem;
    color: #fff;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    box-shadow: 0 12px 24px -10px rgba(79, 70, 229, 0.6);
    margin-bottom: 1.25rem;
}
.feature h3 { font-size: 1.2rem; font-weight: 750; color: var(--ink); margin: 0 0 0.5rem; }
.feature p { color: var(--muted); font-size: 0.95rem; line-height: 1.6; margin: 0; }

/* ===================== Banner admisión ===================== */
.admis {
    position: relative;
    overflow: hidden;
    border-radius: 32px;
    padding: clamp(2rem, 4vw, 3.25rem);
    color: #fff;
    background: linear-gradient(130deg, #4338ca 0%, #6d28d9 55%, #7c3aed 100%);
    box-shadow: 0 40px 80px -30px rgba(79, 70, 229, 0.5);
}
.admis__shape {
    position: absolute;
    top: -120px; right: -80px;
    width: 340px; height: 340px;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, rgba(251, 191, 36, 0.5), transparent 65%);
}
.admis__title {
    font-size: clamp(1.5rem, 2.6vw, 2.1rem);
    font-weight: 800;
    margin: 0 0 1rem;
    letter-spacing: -0.02em;
}
.admis__date {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 1.15rem;
    font-weight: 700;
    padding: 0.5rem 1.1rem;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
}
.admis__text { color: rgba(255, 255, 255, 0.8); margin: 1rem 0 1.5rem; max-width: 380px; }
.admis__unis {
    position: relative;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.9rem;
}
.admis__uni {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    height: 92px;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 12px 26px -14px rgba(0, 0, 0, 0.3);
}
.admis__uni img { max-height: 100%; max-width: 100%; object-fit: contain; }

/* ===================== Método / pasos ===================== */
.steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2.5rem;
}
.step {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.55rem;
    padding: 1.5rem 1rem 1.25rem;
    border-radius: 20px;
    background: #fff;
    border: 1.5px solid #e9edf8;
    cursor: pointer;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease, background 0.25s ease;
}
.step:hover { transform: translateY(-4px); border-color: #cdd4f7; }
.step.is-active {
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    border-color: transparent;
    color: #fff;
    box-shadow: 0 22px 44px -18px rgba(79, 70, 229, 0.6);
}
.step__num {
    position: absolute;
    top: 12px; left: 14px;
    font-size: 0.72rem;
    font-weight: 800;
    opacity: 0.4;
}
.step.is-active .step__num { opacity: 0.7; }
.step__ic {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px; height: 52px;
    border-radius: 14px;
    font-size: 1.25rem;
    color: var(--brand);
    background: rgba(79, 70, 229, 0.1);
    transition: background 0.25s ease, color 0.25s ease;
}
.step.is-active .step__ic { background: rgba(255, 255, 255, 0.2); color: #fff; }
.step__label { font-weight: 700; font-size: 0.95rem; }

.method-panel {
    background: #fff;
    border: 1px solid #eef1f8;
    border-radius: 28px;
    padding: clamp(1.5rem, 3vw, 2.75rem);
    box-shadow: 0 30px 60px -30px rgba(15, 23, 42, 0.2);
}
.method-panel__media {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.75rem;
    border-radius: 22px;
    background: linear-gradient(160deg, #eef2ff, #f5f3ff);
    border: 1px solid #e5e9fb;
}
.method-panel__media img { max-width: 190px; width: 100%; height: auto; filter: drop-shadow(0 18px 26px rgba(49, 46, 129, 0.22)); }
.method-panel__title { font-size: clamp(1.35rem, 2.2vw, 1.7rem); font-weight: 800; color: var(--ink); margin: 0 0 0.75rem; }
.method-panel__desc { color: var(--muted); line-height: 1.65; margin: 0 0 1.25rem; }
.ticks { list-style: none; padding: 0; margin: 0; }
.ticks li {
    display: flex;
    align-items: flex-start;
    gap: 0.7rem;
    padding: 0.4rem 0;
    color: #334155;
    font-weight: 550;
}
.ticks li i {
    flex: none;
    margin-top: 2px;
    width: 20px; height: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.62rem;
    color: #fff;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
}

/* ===================== Docentes ===================== */
.teacher {
    background: #fff;
    border: 1px solid #eef1f8;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 18px 40px -24px rgba(15, 23, 42, 0.2);
    transition: transform 0.28s ease, box-shadow 0.28s ease;
}
.teacher:hover { transform: translateY(-8px); box-shadow: 0 34px 56px -24px rgba(79, 70, 229, 0.32); }
.teacher__photo {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
}
.teacher__photo img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.teacher:hover .teacher__photo img { transform: scale(1.06); }
.teacher__photo::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.28), transparent 45%);
}
.teacher__info { padding: 1.1rem 1.2rem 1.25rem; }
.teacher__info h4 { font-size: 1rem; font-weight: 750; color: var(--ink); margin: 0 0 0.2rem; }
.teacher__area {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--brand);
    background: rgba(79, 70, 229, 0.09);
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
}

/* ===================== Plan Premium ===================== */
.plan {
    position: relative;
    overflow: hidden;
    border-radius: 34px;
    padding: clamp(2rem, 4vw, 3.25rem);
    color: #fff;
    background: linear-gradient(150deg, #0f172a 0%, #1e293b 60%, #312e81 100%);
    box-shadow: 0 44px 90px -34px rgba(15, 23, 42, 0.55);
}
.plan__shape {
    position: absolute;
    bottom: -140px; left: -100px;
    width: 360px; height: 360px;
    border-radius: 50%;
    background: radial-gradient(circle at 60% 40%, rgba(236, 72, 153, 0.4), transparent 65%);
}
.plan__tag {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 0.4rem 0.9rem;
    border-radius: 999px;
    color: #fff;
    background: linear-gradient(135deg, #ec4899, #f43f5e);
    margin-bottom: 1rem;
}
.plan__name { font-size: 1.5rem; font-weight: 800; margin: 0 0 0.75rem; }
.plan__price { display: flex; align-items: baseline; gap: 0.75rem; }
.plan__old { text-decoration: line-through; color: #94a3b8; font-size: 1.2rem; }
.plan__now { font-size: clamp(2.6rem, 5vw, 3.4rem); font-weight: 800; color: #f9a8d4; line-height: 1; }
.plan__now small { font-size: 0.9rem; font-weight: 500; color: #cbd5e1; }
.plan__note { color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin: 0.5rem 0 1.5rem; }
.plan__safe { font-size: 0.78rem; color: rgba(255, 255, 255, 0.55); margin: 0.9rem 0 0; }
.plan__list {
    position: relative;
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.65rem 1.25rem;
}
.plan__list li {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.92rem;
    color: #e2e8f0;
}
.plan__list li i { color: #f472b6; }

.guarantee {
    display: flex;
    gap: 1.4rem;
    align-items: flex-start;
    max-width: 720px;
    margin: 2.5rem auto 0;
    padding: 1.75rem 2rem;
    border-radius: 24px;
    background: #fff;
    border: 1px solid #d1fae5;
    box-shadow: 0 24px 50px -30px rgba(16, 185, 129, 0.35);
}
.guarantee__ic {
    flex: none;
    width: 54px; height: 54px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    font-size: 1.4rem;
    color: #fff;
    background: linear-gradient(135deg, #10b981, #059669);
}
.guarantee h3 { font-size: 1.15rem; font-weight: 750; color: var(--ink); margin: 0 0 0.35rem; }
.guarantee p { color: var(--muted); font-size: 0.95rem; line-height: 1.55; margin: 0; }
.guarantee__fine { font-size: 0.8rem; color: var(--faint, #94a3b8); margin-top: 0.5rem !important; }

/* ===================== CTA final ===================== */
.closing {
    position: relative;
    overflow: hidden;
    padding: 84px 0;
    text-align: center;
    color: #fff;
    background: linear-gradient(135deg, #4338ca, #6d28d9 55%, #7c3aed);
}
.closing::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255, 255, 255, 0.14) 1px, transparent 1.4px);
    background-size: 30px 30px;
    mask-image: radial-gradient(ellipse 60% 70% at 50% 50%, #000, transparent 75%);
    -webkit-mask-image: radial-gradient(ellipse 60% 70% at 50% 50%, #000, transparent 75%);
}
.closing__inner { position: relative; z-index: 1; }
.closing h2 { font-size: clamp(1.8rem, 3.4vw, 2.6rem); font-weight: 800; letter-spacing: -0.02em; margin: 0 0 0.9rem; }
.closing p { color: rgba(255, 255, 255, 0.82); font-size: 1.05rem; margin: 0 0 1.9rem; }

/* ===================== Responsive ===================== */
@media (max-width: 991px) {
    .steps { grid-template-columns: repeat(2, 1fr); }
    .admis__unis { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 575px) {
    .sec { padding: 64px 0; }
    .hero { padding: 110px 0 80px; }
    .hero__cta { flex-direction: column; }
    .hero__cta .btn-xl { width: 100%; justify-content: center; }
    .hero__stats { gap: 1rem 1.75rem; }
    .steps { grid-template-columns: 1fr 1fr; }
    .plan__list { grid-template-columns: 1fr; }
    .guarantee { flex-direction: column; gap: 1rem; text-align: center; align-items: center; }
}

@media (prefers-reduced-motion: reduce) {
    .hero__orb, .hero__badge-dot, .hero__scroll,
    .hero__badge, .hero__title, .hero__sub, .hero__cta, .hero__stats, .hero__trust { animation: none !important; }
}
</style>
