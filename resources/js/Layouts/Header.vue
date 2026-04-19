<template>
    <nav class="navbar navbar-expand-lg container-fluid tw:bg-indigo-500 white" data-bs-theme="dark">
        <div class="container justify-content-between d-flex align-items-center w-100">
            <Link href="/" class="navbar-brand">
                <img src="../../img/full_logo.png" alt="Logo de SATAllite" class="navbar-logo tw:h-25"/>
            </Link>
            <button class="navbar-toggler white" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navbar">
                <span class="navbar-toggler-icon tw:text-white"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end h5" id="navbar">
                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item">
                        <Link class="nav-link white navbar-hover tw:bg-indigo-500 tw:hover:bg-indigo-500" aria-current="page" href="/">Inicio</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link white navbar-hover tw:bg-indigo-500 tw:hover:bg-indigo-500" href="/articles">Noticias</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link white navbar-hover tw:bg-indigo-500 tw:hover:bg-indigo-500" href="/builder">Configurador de PCs</Link>
                    </li>
                    <li v-if="$page.props.auth.user" class="nav-item d-flex align-items-center">
                        <Link class="nav-link white navbar-hover tw:bg-indigo-500 tw:hover:bg-indigo-500 d-flex align-items-center gap-2 py-lg-1 w-100" href="/profile">
                            <span class="m-0">{{ $page.props.auth.user.name.length > 15 ? $page.props.auth.user.name.substring(0, 12) + '...' : $page.props.auth.user.name }}</span>
                            <img :src="$page.props.auth.user.profile_url" alt="Foto de perfil" class="rounded-circle" style="width: 31px; height: 31px; object-fit: cover;">
                        </Link>
                    </li>
                    <li v-else class="nav-item dropdown tw:bg-indigo-500">
                        <a class="nav-link dropdown-toggle white navbar-hover tw:bg-indigo-500 tw:hover:bg-indigo-500" href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">Usuario</a>
                        <ul class="dropdown-menu tw:bg-indigo-500 p-0">
                            <li class="tw:bg-indigo-500 dropdown-option-top">
                                <Link class="dropdown-item white dropdown-option tw:bg-indigo-500 py-2 tw:border-0 tw:rounded-t-2xl" href="/login">Iniciar Sesión</Link>
                            </li>
                            <li class="tw:bg-indigo-500 dropdown-option-bottom">
                                <Link class="dropdown-item white dropdown-option tw:bg-indigo-500 py-2 tw:border-0 tw:rounded-b-2xl" href="/signup">Registrarse</Link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item ms-xl-2 d-flex align-items-center px-xl-3 py-xl-0 py-2">
                        <button id="theme-toggle" class="btn tw:text-white border-0 p-0 fs-5 lh-1"
                            aria-label="Cambiar tema">
                            <i id="theme-icon" class="bi bi-moon-fill"></i>
                            <i id="theme-switch" class="bi bi-toggle-off"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';

onMounted(() => {
    // Cambiar entre temas claro y oscuro
    const html = document.documentElement;
    const themeSwitch = document.getElementById("theme-switch");
    const themeToggle = document.getElementById("theme-toggle");

    function applyTheme(theme) {
        html.setAttribute("data-bs-theme", theme);
        if (themeSwitch) {
            themeSwitch.className = theme === "dark" ? "bi bi-toggle-on" : "bi bi-toggle-off";
        }
        localStorage.setItem("theme", theme);
    }

    // Inicializar tema
    applyTheme(localStorage.getItem("theme") || "light");

    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            applyTheme(html.getAttribute("data-bs-theme") === "dark" ? "light" : "dark");
        });
    }

    // Esconder o mostrar el navbar al hacer scroll
    const navbar = document.querySelector("nav.navbar");
    if (navbar) {
        document.body.style.paddingTop = navbar.offsetHeight + "px";
        document.documentElement.style.setProperty('--navbar-height', navbar.offsetHeight + 'px');
    }

    let lastScroll = window.scrollY;
    const handleScroll = () => {
        const currentScroll = window.scrollY;
        if (navbar) {
            if (currentScroll > lastScroll && currentScroll > navbar.offsetHeight) {
                navbar.classList.add("navbar-hidden");
            } else {
                navbar.classList.remove("navbar-hidden");
            }
        }
        lastScroll = currentScroll;
    };

    window.addEventListener("scroll", handleScroll);

    onUnmounted(() => {
        window.removeEventListener("scroll", handleScroll);
    });
});
</script>
