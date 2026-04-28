<template>
    <Header/>
    <main>
        <InfiniteCarousel/>
        <ArticlesSection :articles="articles" />
        <BuilderSection/>
    </main>
    <Footer/>
</template>

<script setup>
import Header from '@/Layouts/Header.vue';
import InfiniteCarousel from '@/Components/InfiniteCarousel.vue';
import Footer from '@/Layouts/Footer.vue';
import ArticlesSection from '@/Sections/ArticlesSection.vue';
import BuilderSection from '@/Sections/BuilderSection.vue';

defineProps({
    articles: {
        type: Array,
        default: () => []
    }
});
</script>