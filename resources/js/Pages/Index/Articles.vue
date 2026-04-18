<template>
    <div class="tw:min-h-screen tw:flex tw:flex-col">
        <Header/>
        <div class="container my-5 tw:flex-1 tw:flex tw:flex-col tw:justify-center">
            <div class="tw:w-full">
                <p class="tw:text-6xl mb-4 quantico-bold text-center tw:border-2 border-2 border-bottom border-0 pb-2 tw:border-black tw:dark:border-white">Todas las noticias</p>
                <div class="d-flex flex-column gap-4">
                    <ArticleBar
                        v-for="article in articles.data"
                        :key="article.id"
                        :article="article"
                    />
                </div>
            </div>
            
            <RenderlessPagination
            :data="articles"
            :limit="2"
            @pagination-change-page="getResults"
            v-slot="{ computed, prevButtonEvents, nextButtonEvents, pageButtonEvents }"
        >
            <nav v-if="articles.meta && articles.meta.last_page > 1" class="mt-4 d-flex justify-content-center">
                <div class="d-inline-flex tw:border-2 border-2 tw:border-gray-400 tw:dark:border-gray-500 rounded-4 rounded-bottom-right-none overflow-hidden custom-pagination">
                    <!-- Botón Primera Página -->
                    <button
                        @click="getResults(1)"
                        :disabled="articles.meta.current_page === 1"
                        class="tw:w-12 tw:h-12 border-0 border-end border-3 tw:border-gray-400 tw:dark:border-gray-500 tw:flex tw:items-center tw:justify-center tw:bg-transparent hover:tw:bg-gray-200 dark:hover:tw:bg-gray-800 tw:transition-colors disabled:tw:opacity-50"
                    >
                        <i class="bi bi-chevron-double-left page-icon"></i>
                    </button>
                    
                    <!-- Botón Anterior -->
                    <button
                        v-on="prevButtonEvents"
                        :disabled="articles.meta.current_page === 1"
                        class="tw:w-12 tw:h-12 border-0 border-end border-3 tw:border-gray-400 tw:dark:border-gray-500 tw:flex tw:items-center tw:justify-center tw:bg-transparent hover:tw:bg-gray-200 dark:hover:tw:bg-gray-800 tw:transition-colors disabled:tw:opacity-50"
                    >
                        <i class="bi bi-chevron-left page-icon"></i>
                    </button>

                    <!-- Números de Página -->
                    <button
                        v-for="page in computed.pageRange"
                        :key="page"
                        v-on="pageButtonEvents(page)"
                        class="tw:w-12 tw:h-12 border-0 border-end border-3 tw:border-gray-400 tw:dark:border-gray-500 tw:flex tw:items-center tw:justify-center tw:bg-transparent hover:tw:bg-gray-200 dark:hover:tw:bg-gray-800 tw:transition-colors"
                    >
                        <span class="fw-bold fs-5" :class="page === articles.meta.current_page ? 'page-icon-active' : 'page-icon'">{{ page }}</span>
                    </button>

                    <!-- Botón Siguiente -->
                    <button
                        v-on="nextButtonEvents"
                        :disabled="articles.meta.current_page === articles.meta.last_page"
                        class="tw:w-12 tw:h-12 border-0 border-end border-3 tw:border-gray-400 tw:dark:border-gray-500 tw:flex tw:items-center tw:justify-center tw:bg-transparent hover:tw:bg-gray-200 dark:hover:tw:bg-gray-800 tw:transition-colors disabled:tw:opacity-50"
                    >
                        <i class="bi bi-chevron-right page-icon"></i>
                    </button>

                    <!-- Botón Última Página -->
                    <button
                        @click="getResults(articles.meta.last_page)"
                        :disabled="articles.meta.current_page === articles.meta.last_page"
                        class="tw:w-12 tw:h-12 border-0 tw:flex tw:items-center tw:justify-center tw:bg-transparent hover:tw:bg-gray-200 dark:hover:tw:bg-gray-800 tw:transition-colors disabled:tw:opacity-50"
                    >
                        <i class="bi bi-chevron-double-right page-icon"></i>
                    </button>
                </div>
            </nav>
        </RenderlessPagination>
        </div>
        <Footer class="tw:mt-auto"/>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
import ArticleBar from '@/Components/ArticleBar.vue';
import { RenderlessPagination } from 'laravel-vue-pagination';

defineProps({
    articles: {
        type: Object,
        default: () => ({ data: [], meta: {} })
    }
});

const getResults = (page = 1) => {
    router.get(
        '/articles',
        { page },
        { preserveState: true }
    );
};
</script>

<style>
.page-icon {
    color: #9ca3af !important; /* tw-gray-400 */
}
.page-icon-active {
    color: #6366f1 !important; /* tw-indigo-500 */
}

/* Compatibilidad con Dark Mode, ya sea por media query nativa o por atributo de Bootstrap */
@media (prefers-color-scheme: dark) {
    .page-icon {
        color: #6b7280 !important; /* tw-gray-500 */
    }
}
[data-bs-theme="dark"] .page-icon {
    color: #6b7280 !important;
}
</style>
