<template>
    <div class="d-flex flex-column" style="min-height: calc(100vh - var(--navbar-height, 0px));">
        <Header/>
        <main class="flex-grow-1 container py-5 d-flex flex-column tw:gap-6">
            <p class="tw:text-center tw:text-6xl tw:border-b-2 tw:border-gray-500 tw:pb-2 quantico-bold">Mi perfil</p>
            
            <div class="tw:flex tw:flex-col tw:gap-8 tw:pb-8 tw:mt-4">
                <div class="tw:w-full tw:flex tw:items-center tw:justify-center tw:gap-6">
                    <img :src="user.profile_url || '/img/default-avatar.png'" alt="Foto de perfil" class="tw:w-20 tw:h-20 tw:md:w-32 tw:md:h-32 tw:rounded-full tw:object-cover tw:bg-gray-200">
                    <span class="tw:text-xl tw:md:text-3xl tw:font-medium">{{ user.name || 'Usuario' }}</span>
                </div>

                <div class="tw:w-full tw:flex tw:flex-col tw:md:flex-row tw:gap-4">
                    <Link href="/profile/edit" class="tw:flex-1 custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:text-center tw:hover:bg-indigo-400">Editar perfil</Link>
                    <Link v-if="user.is_admin" href="/articles/create" class="tw:flex-1 custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:text-center tw:hover:bg-indigo-400">Crear artículo nuevo</Link>
                    <Link 
                        href="/logout" 
                        method="post" 
                        as="button" 
                        type="button"
                        class="tw:flex-1 custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:text-center tw:hover:bg-indigo-400"
                    >
                        Cerrar sesión
                    </Link>
                </div>
            </div>

            <div class="mt-4 flex-grow-1">
                <p class="tw:text-6xl tw:text-center tw:mb-4 tw:border-b-2 tw:border-gray-500 quantico-bold">Mis Builds</p>
                <div class="tw:border-2 tw:border-dashed tw:border-gray-300 tw:rounded-lg tw:h-48 tw:flex tw:justify-center tw:items-center tw:text-gray-500">
                    Todavía no has creado ninguna build.
                </div>
            </div>
        </main>
        <Footer/>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user || {});
</script>
