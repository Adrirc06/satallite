<template>
    <div class="d-flex flex-column" style="min-height: calc(100vh - var(--navbar-height, 0px));">
        <Header/>
        <SignupForm/>
        <Footer/>
    </div>
</template>

<script setup>
import SignupForm from '@/Components/SignupForm.vue';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
</script>
