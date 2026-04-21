<template>
    <div class="d-flex flex-column" style="min-height: calc(100vh - var(--navbar-height, 0px));">
        <Header/>
        <main>
            <LoginForm/>
        </main>
        <Footer/>
    </div>
</template>

<script setup>
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
import LoginForm from '@/Components/LoginForm.vue';
</script>
