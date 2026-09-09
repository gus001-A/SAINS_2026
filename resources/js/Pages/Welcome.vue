<script setup>
import { onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { confirmAction } from '@/lib/notify';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const activePath = ref(1);

onMounted(() => {
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

const paths = [
    { id: 1, icon: 'fa-chart-simple', label: '1. Mídete', title: 'Exámenes en línea', img: '/images/midete_tips.png',
      desc: 'Practica con nuestros simuladores y descubre tus fortalezas y áreas de oportunidad.',
      items: ['Simuladores tipo examen real', 'Resultados inmediatos', 'Identifica tus áreas de mejora'] },
    { id: 2, icon: 'fa-video', label: '2. Mejora', title: 'Aprende con videos y podcast', img: '/images/forta_tips.png',
      desc: 'Estudia de manera fácil desde cualquier dispositivo con nuestro contenido multimedia.',
      items: ['Clases en video bajo demanda', 'Podcast para aprender en movimiento', 'Material descargable'] },
    { id: 3, icon: 'fa-pen-ruler', label: '3. Practica', title: 'Resuelve ejercicios interactivos', img: '/images/prac_tips.png',
      desc: 'Nuestro banco de preguntas está basado en el temario oficial de las principales universidades.',
      items: ['Miles de ejercicios tipo examen', 'Retroalimentación inmediata', 'Exámenes imprimibles en PDF'] },
    { id: 4, icon: 'fa-flag-checkered', label: '4. ¡Listo!', title: 'Estás listo para el examen 2026', img: '/images/simula_tips.png',
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
        <!-- HERO -->
        <section class="hero">
            <div class="container hero-content text-center">
                <span class="hero-badge"><i class="fas fa-calendar-alt me-2"></i> Convocatoria 2026</span>
                <h1>Asegura tu lugar en la<br />universidad de tus sueños</h1>
                <p class="subtitle">Prepárate con el método más efectivo. Clases, simuladores y guías exclusivas para el examen de admisión 2026.</p>

                <Link v-if="user" :href="route('estudiante.dashboard')" class="btn-hero">
                    <i class="fas fa-graduation-cap"></i> Ir a mi curso
                </Link>
                <button v-else class="btn-hero" @click="openAuth('register')">
                    <i class="fas fa-graduation-cap"></i> Comienza ahora
                </button>

                <div class="partner-logos">
                    <img src="/images/02.png" alt="Partner" />
                    <img src="/images/01.png" alt="Partner" />
                </div>
            </div>
        </section>

        <!-- INFO CARDS -->
        <section class="section-padding" id="nosotros">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="info-card">
                            <i class="fas fa-laptop-code"></i>
                            <h3>100% Online</h3>
                            <p class="text-muted">Estudia desde cualquier lugar, a tu ritmo y en cualquier dispositivo.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <i class="fas fa-chart-line"></i>
                            <h3>+85% de efectividad</h3>
                            <p class="text-muted">Nuestros estudiantes logran ingresar a la carrera de sus sueños.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <i class="fas fa-trophy"></i>
                            <h3>Certificados</h3>
                            <p class="text-muted">Instructores especializados en pruebas de selección universitaria.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ADMISSION BANNER -->
        <section class="section-padding pt-0">
            <div class="container">
                <div class="admission-banner">
                    <div class="row align-items-center">
                        <div class="col-lg-5 mb-4 mb-lg-0">
                            <h2 class="mb-3">Temporada de Exámenes de Admisión 2026</h2>
                            <div class="admission-date">MAYO - JUNIO 2026</div>
                            <p class="mt-3 opacity-75">Prepárate con anticipación y asegura tu ingreso a la universidad de tus sueños.</p>
                            <Link v-if="user" :href="route('estudiante.simulador')" class="btn btn-light mt-3 btn-pill">
                                <i class="fas fa-play me-2"></i> ¡Practica ya!
                            </Link>
                            <button v-else class="btn btn-light mt-3 btn-pill" @click="openAuth('login')">
                                <i class="fas fa-play me-2"></i> ¡Practica ya!
                            </button>
                        </div>
                        <div class="col-lg-7">
                            <div class="university-grid">
                                <img v-for="u in universities" :key="u" :src="`/images/logo-${u}.png`" :alt="u.toUpperCase()" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- LEARNING PATH -->
        <section class="section-padding bg-light-gray" id="metodo">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="mb-3">Ruta de Aprendizaje</h2>
                    <p class="text-muted">El método más innovador de preparación para admisión universitaria 2026</p>
                </div>

                <div class="row g-4 mb-4">
                    <div v-for="p in paths" :key="p.id" class="col-md-3">
                        <div class="path-card" :class="{ active: activePath === p.id }" @click="activePath = p.id">
                            <div class="path-icon"><i class="fas" :class="p.icon"></i></div>
                            <h5 class="mb-0">{{ p.label }}</h5>
                        </div>
                    </div>
                </div>

                <div v-for="p in paths" :key="'c' + p.id" v-show="activePath === p.id" class="path-content">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <img :src="p.img" :alt="p.title" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-md-6">
                            <h3 class="mb-3">{{ p.title }}</h3>
                            <p class="text-muted mb-3">{{ p.desc }}</p>
                            <ul class="list-unstyled">
                                <li v-for="(it, i) in p.items" :key="i" class="mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i> {{ it }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- INSTRUCTORS -->
        <section class="section-padding" id="docentes">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="mb-3">Nuestros Instructores</h2>
                    <p class="text-muted">Prepárate con los mejores profesores certificados en pruebas de selección universitaria</p>
                </div>
                <div class="row g-4">
                    <div v-for="ins in instructors" :key="ins.name" class="col-lg-3 col-md-6">
                        <div class="instructor-card">
                            <img :src="ins.img" :alt="ins.name" class="instructor-img" />
                            <div class="instructor-info">
                                <h4>{{ ins.name }}</h4>
                                <p>{{ ins.area }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PREMIUM PLAN -->
        <section class="section-padding bg-light-gray" id="plan">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="mb-3">Plan Premium 2026</h2>
                    <p class="text-muted">Todo lo que necesitas para asegurar tu ingreso a la universidad</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="premium-card">
                            <div class="premium-badge"><i class="fas fa-star me-1"></i> Más popular</div>
                            <div class="row align-items-center">
                                <div class="col-lg-5 text-center text-lg-start">
                                    <h3 class="text-white mb-2">Curso Premium</h3>
                                    <div class="price-old">$1,200 MXN</div>
                                    <div class="premium-price">$800 <small>MXN</small></div>
                                    <p class="text-white-50 mb-3 small">Pago único · acceso por 1 año</p>
                                    <button class="btn-premium" @click="selectPlan(openAuth, user)">
                                        <i class="fas fa-rocket me-2"></i> Comprar ahora
                                    </button>
                                </div>
                                <div class="col-lg-7 mt-4 mt-lg-0">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="feature-list">
                                                <li><i class="fas fa-check-circle"></i> 150+ clases en video</li>
                                                <li><i class="fas fa-check-circle"></i> 5000+ ejercicios interactivos</li>
                                                <li><i class="fas fa-check-circle"></i> Guías de estudio descargables</li>
                                                <li><i class="fas fa-check-circle"></i> Simulador ilimitado</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="feature-list">
                                                <li><i class="fas fa-check-circle"></i> Clases en vivo semanales</li>
                                                <li><i class="fas fa-check-circle"></i> Asesoría personalizada</li>
                                                <li><i class="fas fa-check-circle"></i> MasterClass exclusivas</li>
                                                <li><i class="fas fa-check-circle"></i> Certificado de finalización</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center mt-5">
                    <div class="col-md-8">
                        <div class="guarantee-card">
                            <div class="guarantee-icon"><i class="fas fa-shield-alt"></i></div>
                            <h3 class="mb-3">Garantía de Reembolso</h3>
                            <p>Si no obtienes un lugar en la universidad, te regresamos tu dinero.</p>
                            <hr />
                            <p class="small text-muted">*Aplica para plan Premium completando el 100% del curso y obteniendo 95+ puntos en el simulador oficial.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
.hero {
    background: linear-gradient(125deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #4f46e5 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    color: #fff;
}

.hero h1 {
    font-size: clamp(2.2rem, 5vw, 4.5rem);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.hero .subtitle {
    font-size: 1.15rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
}

.hero-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.15);
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    font-size: 0.85rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-hero {
    background: #fff;
    color: #4338ca;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.partner-logos {
    margin-top: 3rem;
    display: flex;
    gap: 2.5rem;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.partner-logos img {
    height: 35px;
    opacity: 0.85;
    filter: brightness(0) invert(1);
}

.section-padding {
    padding: 80px 0;
}

.bg-light-gray {
    background: #f8fafc;
}

.btn-pill {
    border-radius: 50px;
    padding: 0.75rem 1.75rem;
    font-weight: 600;
}

.info-card {
    background: #fff;
    border-radius: 24px;
    padding: 2.5rem 2rem;
    text-align: center;
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid rgba(0, 0, 0, 0.04);
}

.info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 30px 50px -15px rgba(79, 70, 229, 0.2);
}

.info-card i {
    font-size: 3rem;
    color: #4f46e5;
    margin-bottom: 1.25rem;
}

.info-card h3 {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.admission-banner {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: 32px;
    padding: 3rem;
    color: #fff;
}

.admission-date {
    font-size: 2rem;
    font-weight: 700;
    margin: 0.5rem 0;
}

.university-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 1.5rem;
    align-items: center;
}

.university-grid img {
    max-width: 100%;
    filter: brightness(0) invert(1);
    opacity: 0.85;
}

.path-card {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    border: 2px solid transparent;
}

.path-card.active,
.path-card:hover {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff;
    transform: translateY(-5px);
}

.path-icon {
    width: 70px;
    height: 70px;
    background: rgba(79, 70, 229, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.75rem;
    color: #4f46e5;
}

.path-card.active .path-icon,
.path-card:hover .path-icon {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
}

.path-content {
    margin-top: 2rem;
    padding: 2.5rem;
    background: #fff;
    border-radius: 28px;
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08);
}

.instructor-card {
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.instructor-card:hover {
    transform: translateY(-8px);
}

.instructor-img {
    width: 100%;
    height: 260px;
    object-fit: cover;
}

.instructor-info {
    padding: 1.25rem;
}

.instructor-info h4 {
    font-weight: 800;
    margin-bottom: 0.25rem;
    font-size: 1.05rem;
}

.instructor-info p {
    color: #4f46e5;
    font-weight: 600;
    font-size: 0.8rem;
    margin-bottom: 0;
}

.premium-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 36px;
    padding: 3rem;
    position: relative;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
}

.premium-badge {
    position: absolute;
    top: 30px;
    right: 30px;
    background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.8rem;
    color: #fff;
}

.premium-price {
    font-size: 3.5rem;
    font-weight: 800;
    margin: 1rem 0;
    color: #f472b6;
}

.premium-price small {
    font-size: 0.9rem;
    font-weight: 400;
    color: #94a3b8;
}

.price-old {
    text-decoration: line-through;
    color: #94a3b8;
    font-size: 1.25rem;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-list li {
    margin-bottom: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #e2e8f0;
}

.feature-list li i {
    color: #ec4899;
}

.btn-premium {
    background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
    color: #fff;
    padding: 0.9rem 2rem;
    border-radius: 50px;
    font-weight: 700;
    border: none;
    width: 100%;
    box-shadow: 0 5px 15px rgba(236, 72, 153, 0.3);
}

.guarantee-card {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 28px;
    padding: 2rem;
    text-align: center;
    border: 1px solid rgba(16, 185, 129, 0.15);
}

.guarantee-icon {
    font-size: 3.5rem;
    color: #10b981;
    margin-bottom: 1rem;
}

.rounded-4 {
    border-radius: 20px;
}
</style>
