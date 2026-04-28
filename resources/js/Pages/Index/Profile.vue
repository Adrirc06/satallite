<template>
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

        <div class="mt-4 tw:flex tw:flex-col tw:flex-1">
            <p class="tw:text-6xl tw:text-center tw:mb-6 tw:border-b-2 tw:border-gray-500 quantico-bold">Mis Builds</p>

            <div v-if="builds.length > 0" class="row g-4 justify-content-center">
                <div v-for="build in builds" :key="build.id" class="col-12 col-md-6 col-xl-4">
                    <BuildCard :build="build" />
                </div>
            </div>

            <div v-else-if="!isLoadingBuilds" class="tw:flex tw:flex-col tw:items-center tw:gap-4 tw:py-8">
                <p class="tw:text-gray-500">Todavía no tienes ninguna build guardada.</p>
                <Link href="/builder" class="custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:text-center tw:hover:bg-indigo-400">
                    Ir al configurador
                </Link>
            </div>

            <div v-if="isLoadingBuilds" class="tw:flex tw:justify-center tw:py-6">
                <div class="spinner-border tw:!text-indigo-500" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>

            <div v-if="hasMoreBuilds && !isLoadingBuilds" class="tw:flex tw:justify-center tw:mt-6">
                <button
                    @click="loadMoreBuilds"
                    class="custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:hover:bg-indigo-400"
                >
                    Ver más
                </button>
            </div>
        </div>
    </main>
    <Footer/>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import axios from 'axios';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
import BuildCard from '@/Components/BuildCard.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user || {});

const builds = ref([]);
const currentPage = ref(1);
const hasMoreBuilds = ref(false);
const isLoadingBuilds = ref(false);
const perPage = 6;

const loadBuilds = async (pageNumber = 1) => {
    isLoadingBuilds.value = true;

    try {
        const response = await axios.get(`/api/v1/users/${user.value.id}/builds`, {
            params: { per_page: perPage, page: pageNumber },
        });

        const newBuilds = response.data.data;
        const meta = response.data.meta;

        if (pageNumber === 1) {
            builds.value = newBuilds;
        } else {
            builds.value = [...builds.value, ...newBuilds];
        }

        hasMoreBuilds.value = meta.current_page < meta.last_page;
        currentPage.value = meta.current_page;
    } catch (error) {
        console.error('Error al cargar las builds:', error);
    } finally {
        isLoadingBuilds.value = false;
    }
};

const loadMoreBuilds = () => {
    loadBuilds(currentPage.value + 1);
};

onMounted(() => {
    if (user.value.id) {
        loadBuilds(1);
    }
});
</script>
