<template>
    <p class="tw:text-red-500 tw:bg-indigo-500 tw:hover:text-black">Index</p>
    <Link href="/">Index</Link>
    <Link href="/articles">Articles</Link>
    <Link href="/builder">Builder</Link>
    <Link href="/login">Login</Link>
    <Link href="/signup">Signup</Link>
    <Link href="/profile">Profile</Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>