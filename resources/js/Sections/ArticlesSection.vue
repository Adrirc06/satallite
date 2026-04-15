<template>
    <section class="container-fluid my-3" id="news">
        <p class="tw:text-6xl quantico-bold text-body text-center py-2 border-bottom border-2 border-top-0 border-end-0 border-start-0 container">
            Últimas noticias
        </p>
        <div class="container d-flex flex-wrap justify-content-center gap-3 row mx-auto">
            <ArticleCard/>
            <ArticleCard/>
            <ArticleCard/>
            <ArticleCard/>
            <ArticleCard/>
            <ArticleCard/>
        </div>
    </section>
</template>
<script setup>
import ArticleCard from '@/Components/ArticleCard.vue';
</script>