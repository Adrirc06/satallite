<template>
    <Link :href="`/article/${article.id}`" class="text-decoration-none text-reset">
        <article class="rounded-4 rounded-bottom-right-none border border-2 overflow-hidden tw:grid tw:grid-rows-3 tw:sm:grid-rows-1 tw:sm:grid-cols-6 tw:h-56 tw:sm:h-48">
            <div class="tw:row-span-1 tw:sm:col-span-1 overflow-hidden">
                <img
                    :src="article.banner_url"
                    :alt="article.title"
                    class="w-100 h-100 object-fit-cover"
                />
            </div>
            <div class="tw:row-span-2 tw:sm:col-span-5 position-relative d-flex align-items-center justify-content-center justify-content-sm-start p-2 p-sm-3 pe-2 pe-sm-5 pb-4 pb-sm-3 overflow-hidden">
                <div class="w-100 d-flex justify-content-center justify-content-sm-start">
                    <div class="d-inline-block">
                        <h2 class="h4 mb-0 text-center text-sm-start quantico-bold">
                            {{ article.title }}
                        </h2>
                    </div>
                </div>
                <p class="mb-0 small text-body-secondary position-absolute end-0 bottom-0 p-2 p-sm-3">
                    {{ article.date }}
                </p>
            </div>
        </article>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    article: {
        type: Object,
        required: true,
    },
});
</script>
