<template>
    <Header/>
    <LoginForm/>
    <Footer/>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
import LoginForm from '@/Components/LoginForm.vue';

onMounted(() => {
    if (window.innerHeight > 750) {
        document.body.style.overflowY = 'hidden';
    }
});

onUnmounted(() => {
    document.body.style.overflowY = '';
});
</script>
