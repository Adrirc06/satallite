<template>
  <div class="vh-100 d-flex flex-column overflow-hidden">
    <header class="">
        <div class="tw:bg-indigo-500 d-flex justify-content-center">
            <img
            src="/img/full_logo.png"
            alt="Logo de SATAllite"
            class="py-3 tw:h-28"
            />
        </div>
    </header>
    <div
    class="d-flex justify-content-center align-items-center flex-column flex-grow-1"
    >
      <img src="/img/error-black.png" alt="Error" class="tw:h-64" id="error_light"/>
      <img src="/img/error.png" alt="Error" class="tw:h-64" id="error_dark"/>
      <p class="quantico-bold h1 py-3">Error {{ status || 404 }}</p>
      <p class="quantico-regular h2 text-center  pb-2">
          {{ message || 'El contenido al que intentas acceder no ha sido encontrado.' }}
        </p>
        <Link
        href="/"
        class="py-1 px-5 tw:bg-indigo-500 rounded-3 text-white text-decoration-none h5 rounded-bottom-right-none"
        >Volver a Inicio</Link
        >
    </div>
    <footer
    class="pt-3 container-fluid tw:bg-indigo-500 justify-content-center d-flex flex-column"
    >
      <div class="d-flex text-center py-3">
          <p class="mx-auto text-white">
              &copy; 2026 <span class="quantico-regular">SATAllite</span>. Todos los
              derechos reservados.
            </p>
        </div>
    </footer>
  </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';

const props = defineProps({
    status: Number,
    message: String,
});

let originalPaddingTop = '';

onMounted(() => {
    originalPaddingTop = document.body.style.getPropertyValue('padding-top');
    document.body.style.setProperty('padding-top', '0px', 'important');

    const html = document.documentElement;
    let theme = localStorage.getItem("theme");

    // Si no está en localStorage, intentamos leerlo del HTML actual (en caso de navegación SPA pura)
    if (!theme) {
        theme = html.getAttribute("data-bs-theme") || document.body.getAttribute("data-bs-theme");
    }

    // Si sigue sin haber tema, usamos la preferencia del sistema operativo o claro por defecto
    if (!theme) {
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    // Forzamos el tema en el HTML para que Bootstrap active fondos y textos
    html.setAttribute("data-bs-theme", theme);

    const isDark = theme === 'dark';

    if (isDark) {
        document.getElementById('error_light')?.classList.add('hidden');
        document.getElementById('error_dark')?.classList.remove('hidden');
    } else {
        document.getElementById('error_dark')?.classList.add('hidden');
        document.getElementById('error_light')?.classList.remove('hidden');
    }
});

onUnmounted(() => {
    if (originalPaddingTop) {
        document.body.style.setProperty('padding-top', originalPaddingTop);
    } else {
        document.body.style.removeProperty('padding-top');
    }
});
</script>