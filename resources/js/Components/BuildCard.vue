<template>
    <div class="carousel-item" :class="{ 'active': isActive }" style="cursor: pointer;" @click="visitBuild">
        <div class="w-100 pb-5 pt-4 d-block d-flex justify-content-center align-items-center flex-column">
            <h3 class="h1 text-center mb-4">
                {{ build.name }}
            </h3>
            <div class="w-75 row g-3 tw:mb-6">
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="d-flex flex-column tw:border-2 tw:border-gray-500 tw:h-60 rounded-4 rounded-bottom-right-none p-3">
                        <div class="d-flex flex-row align-items-center tw:border-b-2 tw:border-gray-500 tw:h-20 tw:pb-2">
                            <i class="tw:!text-indigo-500 bi bi-motherboard h2 tw:mb-0"></i>
                            <p class="h5 mx-2 tw:mb-0 tw:font-semibold">Placa base</p>
                        </div>
                        <div class="tw:flex-1 w-100 d-flex justify-content-center align-items-center text-center">
                            <p class="m-0">{{ build.components?.motherboard?.name || 'No especificada' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="d-flex flex-column tw:border-2 tw:border-gray-500 tw:h-60 rounded-4 rounded-bottom-right-none p-3">
                        <div class="d-flex flex-row align-items-center tw:border-b-2 tw:border-gray-500 tw:h-20 tw:pb-2">
                            <i class="tw:!text-indigo-500 bi bi-cpu h2 tw:mb-0"></i>
                            <p class="h5 mx-2 tw:mb-0 tw:font-semibold">Procesador</p>
                        </div>
                        <div class="tw:flex-1 w-100 d-flex justify-content-center align-items-center text-center">
                            <p class="m-0">{{ build.components?.cpu?.name || 'Integrado en la placa base' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="d-flex flex-column tw:border-2 tw:border-gray-500 tw:h-60 rounded-4 rounded-bottom-right-none p-3">
                        <div class="d-flex flex-row align-items-center tw:border-b-2 tw:border-gray-500 tw:h-20 tw:pb-2">
                            <i class="tw:!text-indigo-500 bi bi-memory h2 tw:mb-0"></i>
                            <p class="h5 mx-2 tw:mb-0 tw:font-semibold">Memoria RAM</p>
                        </div>
                        <div class="tw:flex-1 w-100 d-flex justify-content-center align-items-center text-center">
                            <p class="m-0">{{ build.components?.ram?.name || 'No especificada' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="d-flex flex-column tw:border-2 tw:border-gray-500 tw:h-60 rounded-4 rounded-bottom-right-none p-3">
                        <div class="d-flex flex-row align-items-center tw:border-b-2 tw:border-gray-500 tw:h-20 tw:pb-2">
                            <i class="tw:!text-indigo-500 bi bi-gpu-card h2 tw:mb-0"></i>
                            <p class="h5 mx-2 tw:mb-0 tw:font-semibold">Tarjeta gráfica</p>
                        </div>
                        <div class="tw:flex-1 w-100 d-flex justify-content-center align-items-center text-center">
                            <p class="m-0">{{ build.components?.gpu?.name || build.components?.cpu?.igpu?.name + ' (Integrados)' || 'No especificada' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="d-flex flex-column tw:border-2 tw:border-gray-500 tw:h-60 rounded-4 rounded-bottom-right-none p-3">
                        <div class="d-flex flex-row align-items-center tw:border-b-2 tw:border-gray-500 tw:h-20 tw:pb-2">
                            <i class="tw:!text-indigo-500 bi bi-device-ssd h2 tw:mb-0"></i>
                            <p class="h5 mx-2 tw:mb-0 tw:font-semibold">Almacenamiento</p>
                        </div>
                        <div class="tw:flex-1 w-100 d-flex justify-content-center align-items-center text-center">
                            <p class="m-0">{{ build.components?.drive?.name || 'No especificado' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="d-flex flex-column tw:border-2 tw:border-gray-500 tw:h-60 rounded-4 rounded-bottom-right-none p-3">
                        <div class="d-flex flex-row align-items-center tw:border-b-2 tw:border-gray-500 tw:h-20 tw:pb-2">
                            <i class="tw:!text-indigo-500 bi bi-plug-fill h2 tw:mb-0"></i>
                            <p class="h5 mx-2 tw:mb-0 tw:font-semibold">Fuente de alimentación</p>
                        </div>
                        <div class="tw:flex-1 w-100 d-flex justify-content-center align-items-center text-center">
                            <p class="m-0">{{ build.components?.psu?.name || 'No especificada' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    build: {
        type: Object,
        required: true
    },
    isActive: {
        type: Boolean,
        default: false
    }
});

const visitBuild = () => {
    router.visit('/build/' + props.build.id);
};
</script>
