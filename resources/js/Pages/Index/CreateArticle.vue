<template>
    <div class="d-flex flex-column" style="min-height: calc(100vh - var(--navbar-height, 0px));">
        <Header/>
        <main class="flex-grow-1 container py-5 d-flex flex-column tw:gap-6">
            <p class="tw:text-center tw:text-6xl tw:border-b-2 tw:border-gray-500 tw:pb-2 quantico-bold">Crear nuevo artículo</p>
            <form @submit.prevent="submitArticle" class="tw:flex tw:flex-col tw:gap-4 tw:grow">
                <div class="tw:flex tw:flex-col tw:gap-2">
                    <label for="title" class="tw:text-lg tw:font-medium">Título del artículo</label>
                    <input v-model="form.title" type="text" id="title" name="title" class="tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500 focus:tw:border-transparent" required>
                </div>
                <div class="tw:flex tw:flex-col tw:gap-2">
                    <label for="subtitle" class="tw:text-lg tw:font-medium">Subtítulo del artículo</label>
                    <input v-model="form.subtitle" type="text" id="subtitle" name="subtitle" class="tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500 focus:tw:border-transparent" required>
                </div>
                <div class="tw:flex tw:flex-col tw:gap-2">
                    <label for="banner_url" class="tw:text-lg tw:font-medium">URL de la imagen del banner (Opcional)</label>
                    <input v-model="form.banner_url" type="text" id="banner_url" name="banner_url" class="tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500 focus:tw:border-transparent">
                </div>
                <div class="tw:flex tw:flex-col tw:gap-2 tw:grow">
                    <label for="content" class="tw:text-lg tw:font-medium">Contenido del artículo</label>
                    <textarea v-model="form.content" id="content" name="content" class="tw:grow tw:resize-none tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500 focus:tw:border-transparent" required></textarea>
                </div>
                <div class="tw:flex tw:items-center tw:gap-4 tw:self-start">
                    <button type="submit" class="custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:hover:bg-indigo-400" :disabled="loading">
                        {{ loading ? 'Publicando...' : 'Publicar artículo' }}
                    </button>
                    <span v-if="errorMsg" class="tw:text-red-500 tw:text-sm">{{ errorMsg }}</span>
                </div>
            </form>
        </main>
        <Footer/>
    </div>
</template>
<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';

const form = ref({
    title: '',
    subtitle: '',
    banner_url: '/img/banners/banner1.jpg',
    content: '',
});

const loading = ref(false);
const errorMsg = ref('');

const submitArticle = async () => {
    loading.value = true;
    errorMsg.value = '';
    
    const getCookie = (name) => {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
        return '';
    }
    
    try {
        const response = await axios.post('/api/v1/articles', form.value, {
            withCredentials: true,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': getCookie('XSRF-TOKEN')
            }
        });
        
        if (response.data && response.data.data) {
            router.visit(`/article/${response.data.data.id}`);
        } else {
            router.visit('/articles');
        }
    } catch (error) {
        if (error.response?.data?.message) {
            errorMsg.value = error.response.data.message;
        } else {
            errorMsg.value = 'Ha ocurrido un error al publicar el artículo.';
        }
    } finally {
        loading.value = false;
    }
};
</script>