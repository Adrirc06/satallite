<template>
    <div
        class="card tw:border tw:border-gray-500 rounded-4 rounded-bottom-right-none tw:h-full tw:relative tw:cursor-pointer"
        @click="router.visit('/build/' + build.id)"
    >
        <div class="card-body">
            <p class="card-title h4 text-center tw:border-b tw:border-gray-500 pb-2 tw:truncate">{{ build.name }}</p>
            <div class="d-flex align-items-center">
                <i class="bi bi-motherboard tw:!text-indigo-500 tw:shrink-0"></i>
                <span class="ms-2 tw:truncate tw:min-w-0">{{ build.components?.motherboard?.name || 'No especificada' }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-cpu tw:!text-indigo-500 tw:shrink-0"></i>
                <span class="ms-2 tw:truncate tw:min-w-0">{{ build.components?.cpu?.name || 'Integrado en la placa base' }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-memory tw:!text-indigo-500 tw:shrink-0"></i>
                <span class="ms-2 tw:truncate tw:min-w-0">{{ build.components?.ram?.name || 'No especificada' }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-gpu-card tw:!text-indigo-500 tw:shrink-0"></i>
                <span class="ms-2 tw:truncate tw:min-w-0">{{ build.components?.gpu?.name || (build.components?.cpu?.igpu?.name ? build.components.cpu.igpu.name + ' (Integrados)' : 'No especificada') }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-device-ssd tw:!text-indigo-500 tw:shrink-0"></i>
                <span class="ms-2 tw:truncate tw:min-w-0">{{ build.components?.drive?.name || 'No especificado' }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-plug-fill tw:!text-indigo-500 tw:shrink-0"></i>
                <span class="ms-2 tw:truncate tw:min-w-0">{{ build.components?.psu?.name || 'No especificada' }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-pc-display tw:!text-indigo-500 tw:shrink-0"></i>
                <span class="ms-2 tw:truncate tw:min-w-0">{{ build.components?.chassis?.name || 'No especificada' }}</span>
            </div>
        </div>
        <div v-if="showRating" class="tw:border-t tw:border-gray-500 tw:px-4 tw:py-2 tw:flex tw:items-center justify-content-center tw:gap-1">
            <i v-for="star in 5" :key="star" class="bi tw:!text-yellow-400" :class="starClass(build.ratings_avg_rating || 0, star)"></i>
            <span class="tw:ml-1">{{ ratingAvg }}</span>
            <span class="tw:text-gray-500 tw:ml-1">({{ build.ratings_count ?? 0 }})</span>
        </div>
        <i v-if="!build.is_public" class="bi bi-lock-fill tw:absolute tw:bottom-3 tw:right-3 tw:text-indigo-500 tw:text-lg"></i>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    build: {
        type: Object,
        required: true,
    },
    showRating: {
        type: Boolean,
        default: false,
    },
});

const starClass = (score, position) => {
    const starValue = position * 20;
    const previousStarValue = (position - 1) * 20;
    if (score >= starValue) return 'bi-star-fill';
    if (score > previousStarValue + 5) return 'bi-star-half';
    return 'bi-star';
};

const ratingAvg = computed(() => {
    if (!props.build.ratings_avg_rating) return '0.0';
    return (props.build.ratings_avg_rating / 20).toFixed(1);
});

</script>
