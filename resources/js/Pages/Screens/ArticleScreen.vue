<template>
    <div class="d-flex flex-column" style="min-height: calc(100vh - var(--navbar-height, 0px));">
        <Header/>
        <main class="flex-grow-1 container py-5 d-flex flex-column">
            <div class="p-0 border border-2 rounded-5 rounded-bottom-right-none flex-grow-1 d-flex flex-column mb-4">
                <img
                    :src="article.banner_url"
                    :alt="article.title"
                    class="top-article rounded-top-5 w-100 object-fit-cover"
                />
                <div class="m-5 text-center flex-grow-1 d-flex flex-column">
                    <div class="flex-grow-1">
                        <p class="quantico-bold py-2 article-title">
                            {{ article.title }}
                        </p>
                        <p class="quantico-regular py-2 article-subtitle">
                            {{ article.subtitle }}
                        </p>
                        <div class="justify-content-lg-evenly">
                            <p v-for="(paragraph, index) in paragraphs" :key="index" class="py-2 article-content">
                                {{ paragraph }}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-3 mt-auto tw:text-gray-500 border-top">
                        <span class="text-start">
                            Por {{ article.user.name }} a {{ article.date }}
                        </span>
                        <button v-if="$page.props.auth.user?.is_admin" data-bs-toggle="modal" data-bs-target="#modalDeleteArticle" class="btn btn-outline-danger d-flex align-items-center gap-2 rounded-3 rounded-bottom-right-none">
                            Eliminar artículo
                        </button>
                    </div>
                </div>
            </div>
        </main>
        <Footer/>
        <div class="modal fade" id="modalDeleteArticle" tabindex="-1" aria-labelledby="modalDeleteArticleLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-5 border rounded-bottom-right-none text-start">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDeleteArticleLabel">
                            Eliminar artículo
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que quieres eliminar este artículo? Esta acción no se puede deshacer.
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="custom-btn tw:border tw:border-gray-500 tw:text-gray-500 tw:bg-transparent tw:hover:border-indigo-500 tw:hover:bg-indigo-500 tw:hover:text-white"
                            data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="button" @click="deleteArticle" data-bs-dismiss="modal"
                            class="custom-btn tw:border tw:border-gray-500 tw:text-gray-500 tw:bg-transparent tw:hover:border-red-600 tw:hover:bg-red-600 tw:hover:!text-white">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';

const props = defineProps({
    article: {
        type: Object,
        required: true,
    },
});

const paragraphs = computed(() => {
    if (!props.article?.content) return [];
    return props.article.content.split('^').filter(p => p.trim() !== '');
});

const getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
    return '';
}

const deleteArticle = async () => {
    try {
        await axios.delete(`/api/v1/articles/${props.article.id}`, {
            withCredentials: true,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': getCookie('XSRF-TOKEN')
            }
        });
        
        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

        router.visit('/articles');
    } catch (error) {
        console.error('Error al eliminar el artículo:', error);
        alert('Ha ocurrido un error al eliminar el artículo. ' + (error.response?.data?.message || ''));
    }
};
</script>
