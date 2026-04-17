<template>
    <div class="card article-card col-12 col-md-5 col-lg-3 p-0 h4 rounded-5 border-2 rounded-bottom-right-none ">
        <img :src="article.banner_url" class="card-img-top article-thumbnail-banner w-100 rounded-top-5"
            :alt="article.title" />
        <div class="card-body p-2 justify-content-between d-flex flex-column">
            <div class="d-flex justify-content-center align-items-center h-100">
                <p class="card-title text-center h4">
                    {{ article.title }}
                </p>
            </div>
            <Link :href="`/article/${article.id}`" class="btn w-100 custom-news-button tw:hover:bg-indigo-400 tw:hover:border-indigo-400 white">Ver más</Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    article: {
        type: Object,
        required: true,
    },
});
</script>