<template>
    <Header/>
    <InfiniteCarousel/>
        <main class="container-fluid">
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
        </main>
    <div class="tw:h-400">Prueba Scroll en Home</div>
    <Footer/>
</template>

<script setup>
import Header from '@/Layouts/Header.vue';
import InfiniteCarousel from '@/Components/InfiniteCarousel.vue';
import Footer from '@/Layouts/Footer.vue';
import ArticleCard from '@/Components/ArticleCard.vue';
</script>