<template>
    <Header/>
    <main class="container-fluid">
      <div
        class="container my-5 p-0 border border-2 rounded-5 rounded-bottom-right-none h-100"
      >
        <img
          :src="article.banner_url"
          :alt="article.title"
          class="top-article rounded-top-5"
        />
        <div class="m-5 text-center">
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
            <p class="py-2 px-3 tw:text-gray-400 tw:text-start">
                Por {{ article.user.name }} a {{ article.date }}
            </p>
          </div>
        </div>
      </div>
    </main>
    <Footer/>
</template>
<script setup>
import { computed } from 'vue';
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
</script>