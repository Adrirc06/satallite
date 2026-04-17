<template>
    <div class="modal fade" id="modalGithub" tabindex="-1" aria-labelledby="modalGithubLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-5 border rounded-bottom-right-none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalGithubLabel">
                        Vas a abandonar nuestra página
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Se te va a redirigir a <strong>https://github.com/</strong>. ¿Estás
                    seguro de que quieres continuar?
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="custom-btn tw:border tw:border-gray-500 tw:text-gray-500 tw:bg-transparent tw:hover:border-red-600 tw:hover:bg-red-600 tw:hover:text-white"
                        data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <a type="button"
                        class="custom-btn tw:border tw:border-gray-500 tw:!text-gray-500 tw:bg-transparent tw:hover:border-indigo-500 tw:hover:bg-indigo-500 tw:hover:!text-white"
                        href="https://github.com/" target="_blank">
                        Aceptar
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalInstagram" tabindex="-1" aria-labelledby="modalInstagramLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-5 border rounded-bottom-right-none">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalInstagramLabel">
                        Vas a abandonar nuestra página
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Se te va a redirigir a <strong>https://instagram.com/</strong>. ¿Estás
                    seguro de que quieres continuar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="custom-btn tw:border tw:border-gray-500 tw:text-gray-500 tw:bg-transparent tw:hover:border-red-600 tw:hover:bg-red-600 tw:hover:text-white" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <a type="button" 
                        class="custom-btn tw:border tw:border-gray-500 tw:!text-gray-500 tw:bg-transparent tw:hover:border-indigo-500 tw:hover:bg-indigo-500 tw:hover:!text-white" href="https://instagram.com/" target="_blank">
                        Aceptar
                    </a>
                </div>
            </div>
        </div>
    </div>
    <footer class="pt-3 container-fluid tw:bg-indigo-500 justify-content-center d-flex flex-column">
        <div class="d-flex text-center border-bottom border-white mx-5">
            <p class="mx-auto tw:text-white">
                &copy; 2026 <span class="quantico-regular">SATAllite</span>. Todos los derechos reservados.
            </p>
        </div>
        <div class="d-flex justify-content-around w-50 mx-auto pt-2">
            <i class="white bi bi-github h5 tw:cursor-pointer" data-bs-toggle="modal" data-bs-title="Nuestro GitHub"
                data-bs-target="#modalGithub"></i>
            <i class="white bi bi-instagram h5 tw:cursor-pointer" data-bs-toggle="modal" data-bs-title="Nuestro Instagram"
                data-bs-target="#modalInstagram"></i>
        </div>
    </footer>
</template>
<script setup>
import { onMounted } from 'vue';
import * as bootstrap from 'bootstrap';

onMounted(() => {
    // Activación de tooltips
    const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]',
    );
    const tooltipList = [...tooltipTriggerList].map(
        (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl),
    );
});
</script>
