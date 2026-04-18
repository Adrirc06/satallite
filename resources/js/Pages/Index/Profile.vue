<template>
    <div class="d-flex flex-column" style="min-height: calc(100vh - var(--navbar-height, 0px));">
        <Header/>
        <main class="flex-grow-1 container py-5 d-flex flex-column">
            <h2>Mi Perfil</h2>
            
            <div class="mt-auto d-flex justify-content-end">
                <Link 
                    href="/logout" 
                    method="post" 
                    as="button" 
                    class="btn btn-outline-danger custom-btn rounded-3 rounded-bottom-right-none"
                >
                    Cerrar Sesión
                </Link>
            </div>
        </main>
        <Footer/>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
</script>
