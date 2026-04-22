<template>
    <Header/>
    <SignupForm/>
    <Footer/>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import SignupForm from '@/Components/SignupForm.vue';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';

onMounted(() => {
    if (window.innerHeight > 850) {
        document.body.style.overflowY = 'hidden';
    }
});

onUnmounted(() => {
    document.body.style.overflowY = '';
});
</script>
