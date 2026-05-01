<template>
    <Header />
    <main class="container tw:mx-auto tw:px-4 tw:py-8 tw:relative">
        <p class="tw:text-3xl tw:md:text-6xl tw:wrap-break-word quantico-bold tw:border-2 border-start-0 border-end-0 border-top-0 tw:border-gray-500 pb-3 text-center">
            {{ build.name }}
        </p>

        <div v-if="build.user" @click="router.visit('/user/' + build.user.id)" class="tw:flex tw:items-center tw:justify-center tw:gap-2 tw:mt-2 tw:mb-4 tw:cursor-pointer tw:w-fit tw:mx-auto tw:group">
            <span class="tw:text-sm tw:text-gray-500 tw:group-hover:text-indigo-500 tw:transition-colors">Creada por {{ build.user.name }}</span>
            <img :src="build.user.profile_url || '/img/default-avatar.png'" alt="Foto de perfil" class="tw:w-7 tw:h-7 tw:rounded-full tw:object-cover tw:bg-gray-200">
        </div>

        <div class="tw:my-6 tw:flex tw:flex-col tw:gap-4">            
            <div class="tw:grid tw:grid-cols-1 md:tw:grid-cols-2 lg:tw:grid-cols-3 tw:gap-4">
                
                <!-- Motherboard -->
                <div v-if="build.motherboard" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-motherboard tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Placa base</p>
                    </div>
                    <p class="tw:font-medium">{{ build.motherboard.name }}</p>
                    <div class="tw:flex tw:flex-wrap tw:gap-x-4 tw:gap-y-1 tw:mt-1 tw:text-sm tw:pb-7">
                        <span v-for="spec in getSpecs('motherboard', build.motherboard)" :key="spec.label">
                            <span class="tw:text-gray-500">{{ spec.label }}:</span>
                            <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                        </span>
                    </div>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.motherboard?.price ? build.motherboard.price + '€' : 'Sin Stock' }}</p>
                </div>

                <!-- CPU -->
                <div v-if="build.cpu" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-cpu tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Procesador</p>
                    </div>
                    <p class="tw:font-medium">{{ build.cpu.name }}</p>
                    <div class="tw:flex tw:flex-wrap tw:gap-x-4 tw:gap-y-1 tw:mt-1 tw:text-sm tw:pb-7">
                        <span v-for="spec in getSpecs('cpu', build.cpu)" :key="spec.label">
                            <span class="tw:text-gray-500">{{ spec.label }}:</span>
                            <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                        </span>
                    </div>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.cpu?.price ? build.cpu.price + '€' : 'Sin Stock' }}</p>
                </div>

                <!-- RAM -->
                <div v-if="build.ram" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-memory tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">RAM</p>
                    </div>
                    <p class="tw:font-medium">{{ build.ram.name }}</p>
                    <div class="tw:flex tw:flex-wrap tw:gap-x-4 tw:gap-y-1 tw:mt-1 tw:text-sm tw:pb-7">
                        <span v-for="spec in getSpecs('ram', build.ram)" :key="spec.label">
                            <span class="tw:text-gray-500">{{ spec.label }}:</span>
                            <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                        </span>
                    </div>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.ram?.price ? build.ram.price + '€' : 'Sin Stock' }}</p>
                </div>

                <!-- GPU -->
                <div v-if="build.gpu" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-gpu-card tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Tarjeta gráfica</p>
                    </div>
                    <p class="tw:font-medium">{{ build.gpu.name }}</p>
                    <div class="tw:flex tw:flex-wrap tw:gap-x-4 tw:gap-y-1 tw:mt-1 tw:text-sm tw:pb-7">
                        <span v-for="spec in getSpecs('gpu', build.gpu)" :key="spec.label">
                            <span class="tw:text-gray-500">{{ spec.label }}:</span>
                            <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                        </span>
                    </div>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.gpu?.price ? build.gpu.price + '€' : 'Sin Stock' }}</p>
                </div>

                <!-- Drive -->
                <div v-if="build.drive" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-device-ssd tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Almacenamiento</p>
                    </div>
                    <p class="tw:font-medium">{{ build.drive.name }}</p>
                    <div class="tw:flex tw:flex-wrap tw:gap-x-4 tw:gap-y-1 tw:mt-1 tw:text-sm tw:pb-7">
                        <span v-for="spec in getSpecs('drive', build.drive)" :key="spec.label">
                            <span class="tw:text-gray-500">{{ spec.label }}:</span>
                            <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                        </span>
                    </div>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.drive?.price ? build.drive.price + '€' : 'Sin Stock' }}</p>
                </div>

                <!-- PSU -->
                <div v-if="build.psu" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-plug-fill tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Fuente de alimentación</p>
                    </div>
                    <p class="tw:font-medium">{{ build.psu.name }}</p>
                    <div class="tw:flex tw:flex-wrap tw:gap-x-4 tw:gap-y-1 tw:mt-1 tw:text-sm tw:pb-7">
                        <span v-for="spec in getSpecs('psu', build.psu)" :key="spec.label">
                            <span class="tw:text-gray-500">{{ spec.label }}:</span>
                            <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                        </span>
                    </div>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.psu?.price ? build.psu.price + '€' : 'Sin Stock' }}</p>
                </div>

                <!-- Chassis -->
                <div v-if="build.chassis" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-pc-display tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Caja</p>
                    </div>
                    <p class="tw:font-medium">{{ build.chassis.name }}</p>
                    <div class="tw:flex tw:flex-wrap tw:gap-x-4 tw:gap-y-1 tw:mt-1 tw:text-sm tw:pb-7">
                        <span v-for="spec in getSpecs('chassis', build.chassis)" :key="spec.label">
                            <span class="tw:text-gray-500">{{ spec.label }}:</span>
                            <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                        </span>
                    </div>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.chassis?.price ? build.chassis.price + '€' : 'Sin Stock' }}</p>
                </div>

            </div>
        </div>

        <div class="tw:mt-8 tw:p-4 rounded-4 rounded-bottom-right-none tw:shadow-sm tw:border tw:border-gray-200 tw:dark:border-gray-700">
            <div class="tw:flex tw:flex-col tw:sm:flex-row tw:gap-4">
                <Link v-if="isOwner" :href="`/builder?edit=${build.id}`" class="tw:flex-1 tw:py-3 tw:px-4 tw:bg-indigo-500 tw:hover:bg-indigo-400 tw:!text-white text-decoration-none tw:font-bold rounded-3 rounded-bottom-right-none tw:transition tw:text-center tw:no-underline">Editar</Link>
                <Link v-else :href="`/builder?base=${build.id}`" class="tw:flex-1 tw:py-3 tw:px-4 tw:bg-indigo-500 tw:hover:bg-indigo-400 tw:!text-white text-decoration-none tw:font-bold rounded-3 rounded-bottom-right-none tw:transition tw:text-center tw:no-underline">Usar como base</Link>
                <button @click="exportPDF" :disabled="isExportingPDF" class="tw:flex-1 tw:bg-red-600 tw:hover:bg-red-500 tw:text-white tw:font-bold tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition tw:disabled:opacity-60">
                    <span v-if="isExportingPDF" class="spinner-border spinner-border-sm me-2"></span>
                    Exportar PDF
                </button>
                <button @click="openAiDialog" class="tw:flex-1 tw:bg-linear-to-r tw:from-purple-500 tw:to-pink-500 tw:hover:from-purple-600 tw:hover:to-pink-600 text-white tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition">Resumen IA</button>
                <button v-if="isOwner" data-bs-toggle="modal" data-bs-target="#modalDeleteBuild" class="tw:flex-1 tw:bg-red-600 tw:hover:bg-red-500 tw:text-white tw:font-bold tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition">Eliminar</button>
            </div>
        </div>

                <div v-if="build.is_public" class="tw:mt-8 tw:p-6 rounded-4 rounded-bottom-right-none tw:shadow-sm tw:border tw:border-gray-300 tw:dark:border-gray-700 tw:text-center tw:transition-all">
            <h2 class="tw:text-2xl tw:font-bold tw:mb-4 text-body">Índice de compatibilidad</h2>
            <div class="tw:flex tw:items-center tw:justify-center tw:gap-2 tw:text-yellow-400 tw:text-4xl tw:mb-2">
                <i v-for="star in 5" :key="'avg-'+star" class="bi" :class="starClass(build.ratings_avg_rating || 0, star)"></i>
                <span class="tw:text-gray-600 tw:text-2xl tw:ml-2 tw:align-middle">({{ build.ratings_count || 0 }})</span>
            </div>
            <div class="tw:mt-4 tw:flex tw:flex-col tw:items-center">
                <template v-if="localMyRating !== null">
                    <p class="tw:text-gray-500 tw:font-bold tw:mb-1">Mi valoración</p>
                    <div class="tw:flex tw:items-center tw:justify-center tw:gap-1 tw:text-yellow-400 tw:text-2xl">
                        <i v-for="star in 5" :key="'my-'+star" class="bi" :class="starClass(localMyRating, star)"></i>
                    </div>
                </template>
                <template v-else-if="page.props.auth.user && !isOwner">
                    <button @click="showRatingDialog = true" class="tw:bg-indigo-500 tw:hover:bg-indigo-400 tw:text-white tw:font-bold tw:py-2 tw:px-6 rounded-3 rounded-bottom-right-none tw:transition">
                        Añade una valoración
                    </button>
                </template>
                <template v-else-if="page.props.auth.user && isOwner"/>
                <template v-else>
                    <Link href="/login" class="tw:!text-indigo-500 tw:hover:!text-indigo-400 tw:font-bold tw:hover:underline text-decoration-none">Inicia sesión para valorar</Link>
                </template>
            </div>
        </div>

        <Teleport to="body">
        <div v-if="showRatingDialog" class="tw:fixed tw:inset-0 tw:z-1060 tw:flex tw:items-center tw:justify-center tw:bg-black/40">
            <div class="bg-body tw:p-6 rounded-5 rounded-bottom-right-none tw:shadow-xl tw:max-w-sm tw:w-full tw:text-center text-body tw:border-2 tw:border-gray-400">
                <h3 class="tw:text-2xl tw:font-bold tw:mb-4">Valora la compatibilidad</h3>
                
                <div class="tw:mb-4 tw:inline-flex tw:flex-col tw:items-center tw:gap-3">
                    <div class="tw:flex tw:items-center tw:justify-center tw:gap-2 tw:text-yellow-400 tw:text-4xl">
                        <i v-for="star in 5" :key="'slider-'+star" class="bi" :class="starClass(ratingSliderValue, star)"></i>
                    </div>

                    <input
                        type="range"
                        class="form-range rating-range tw:w-full"
                        min="0"
                        max="100"
                        step="1"
                        v-model="ratingSliderValue"
                    >
                </div>
                
                <div class="tw:flex tw:gap-3">
                    <button @click="showRatingDialog = false" class="tw:flex-1 tw:bg-red-600 tw:hover:bg-red-500 tw:text-white rounded-3 rounded-bottom-right-none tw:font-bold tw:py-2 tw:px-4 tw:rounded tw:transition">Cancelar</button>
                    <button @click="submitRating" class="tw:flex-1 tw:bg-indigo-500 tw:hover:bg-indigo-400 rounded-3 rounded-bottom-right-none tw:text-white tw:font-bold tw:py-2 tw:px-4 tw:rounded tw:transition text-nowrap" :disabled="isSubmittingRating">
                        <span v-if="isSubmittingRating" class="spinner-border spinner-border-sm me-2"></span>
                        Publicar
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showAiDialog" class="tw:fixed tw:inset-0 tw:z-1060 tw:flex tw:items-center tw:justify-center tw:bg-black/50 tw:p-3 tw:sm:p-6">
            <div class="bg-body tw:rounded-2xl rounded-bottom-right-none tw:shadow-2xl tw:w-full tw:max-w-3xl tw:flex tw:flex-col tw:border tw:border-gray-200 tw:dark:border-gray-700" :style="isLoadingAi || aiError ? 'height: 85dvh; max-height: 85dvh;' : 'max-height: 85dvh;'">
                <!-- Cabecera -->
                <div class="tw:flex tw:items-center tw:justify-between tw:px-5 tw:py-4 tw:border-b tw:border-gray-200 tw:dark:border-gray-700 tw:flex-shrink-0">
                    <div class="tw:flex tw:items-center tw:gap-2">
                        <i class="bi bi-stars tw:text-indigo-500 tw:text-xl"></i>
                        <h3 class="tw:text-lg tw:font-bold text-body tw:m-0">Resumen IA</h3>
                    </div>
                    <button @click="showAiDialog = false" class="tw:text-gray-400 tw:hover:text-gray-500 tw:transition tw:text-xl tw:leading-none">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Contenido -->
                <div class="tw:overflow-y-auto tw:p-5" :class="isLoadingAi || aiError ? 'tw:flex-1' : ''">
                    <div v-if="isLoadingAi" class="tw:flex tw:flex-col tw:items-center tw:justify-center tw:h-full tw:gap-3 tw:text-gray-500">
                        <div class="spinner-border tw:text-indigo-500" role="status"></div>
                        <span class="tw:text-sm">Analizando la build...</span>
                    </div>
                    <div v-else-if="aiSummaryText" class="tw:leading-relaxed tw:whitespace-pre-wrap">{{ aiSummaryText }}</div>
                    <div v-else-if="aiError" class="tw:flex tw:flex-col tw:items-center tw:justify-center tw:h-full tw:gap-3 tw:text-red-500">
                        <i class="bi bi-exclamation-circle tw:text-3xl"></i>
                        <p class="tw:text-sm tw:m-0">{{ aiError }}</p>
                        <button @click="fetchAiSummary" class="tw:text-sm tw:bg-indigo-500 tw:hover:bg-indigo-400 tw:text-white tw:px-4 tw:py-2 rounded-3 rounded-bottom-right-none tw:transition">
                            Reintentar
                        </button>
                    </div>
                </div>

                <!-- Aviso IA -->
                <div class="tw:px-5 tw:py-3 tw:border-t tw:border-gray-200 tw:dark:border-gray-700 tw:bg-amber-50 tw:dark:bg-amber-900/10 tw:rounded-b-2xl tw:flex-shrink-0">
                    <p class="tw:text-xs tw:text-gray-500 tw:dark:text-gray-400 tw:text-center tw:m-0">
                        <i class="bi bi-exclamation-triangle-fill tw:text-amber-500 tw:mr-1"></i>
                        La información generada por IA puede contener errores. Verifica siempre la compatibilidad antes de realizar una compra.
                    </p>
                </div>
            </div>
        </div>
        </Teleport>

        <div class="modal fade" id="modalDeleteBuild" tabindex="-1" aria-labelledby="modalDeleteBuildLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-5 border rounded-bottom-right-none text-start">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 font-bold" id="modalDeleteBuildLabel">
                            Eliminar build
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-gray-600">
                        ¿Estás seguro de que quieres eliminar esta configuración? Esta acción no se puede deshacer.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="custom-btn tw:border tw:border-gray-500 tw:text-gray-500 tw:bg-transparent tw:hover:border-indigo-500 tw:hover:bg-indigo-500 tw:hover:text-white" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="button" @click="deleteBuild" data-bs-dismiss="modal" class="custom-btn tw:border tw:border-gray-500 tw:text-gray-500 tw:bg-transparent tw:hover:border-red-600 tw:hover:bg-red-600 tw:hover:!text-white">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <Footer />
</template>

<script setup>
import { ref, computed, watch, onUnmounted } from 'vue';
import jsPDF from 'jspdf';
import { usePage, router, Link } from '@inertiajs/vue3';
import axios from 'axios';
import Header from '@/Layouts/Header.vue';
import Footer from '@/Layouts/Footer.vue';

const props = defineProps({
    build: {
        type: Object,
        required: true
    },
    myRating: {
        type: Number,
        default: null
    }
});

const page = usePage();
const showAiDialog = ref(false);
const aiSummaryText = ref('');
const aiError = ref(false);
const isLoadingAi = ref(false);

const openAiDialog = () => {
    showAiDialog.value = true;
    if (!aiSummaryText.value) {
        fetchAiSummary();
    }
};

const fetchAiSummary = async () => {
    isLoadingAi.value = true;
    aiSummaryText.value = '';
    aiError.value = false;
    try {
        const response = await axios.get(`/api/v1/builds/${props.build.id}/ai-summary`);
        aiSummaryText.value = response.data.summary
            .replace(/\*\*(.*?)\*\*/g, '$1')
            .replace(/\*(.*?)\*/g, '$1')
            .replace(/^#+\s+/gm, '');
    } catch (e) {
        aiError.value = e.response?.data?.error || 'No se pudo generar el resumen.';
    } finally {
        isLoadingAi.value = false;
    }
};

const getSpecs = (typeKey, item) => {
    if (!item) return [];
    const specs = [];
    if (typeKey === 'motherboard') {
        if (item.socket?.name) specs.push({ label: 'Socket', value: item.socket.name });
        if (item.max_memory) specs.push({ label: 'Memoria máxima', value: `${item.max_memory}GB` });
        if (item.form_factor) specs.push({ label: 'Factor de forma', value: item.form_factor.name });
        if (item.ram_type) specs.push({ label: 'Tipo de RAM', value: item.ram_type.name });
    } else if (typeKey === 'cpu') {
        if (item.socket?.name) specs.push({ label: 'Socket', value: item.socket.name });
        if (item.cores) specs.push({ label: 'Núcleos/Hilos', value: `${item.cores}/${item.cores * 2}` });
        if (item.frequency) specs.push({ label: 'Frecuencia', value: `${item.frequency}–${item.max_frequency || item.frequency}GHz` });
        if (item.tdp) specs.push({ label: 'TDP', value: `${item.tdp}W` });
        specs.push({ label: 'iGPU', value: item.igpu?.name || 'No' });
    } else if (typeKey === 'ram') {
        specs.push({ label: 'Capacidad', value: `${item.size * item.modules}GB (${item.size}GB x ${item.modules})` });
        if (item.ram_type) specs.push({ label: 'Tipo', value: item.ram_type.name });
        if (item.cas_latency) specs.push({ label: 'Latencia', value: `CL${item.cas_latency}` });
        if (item.speed) specs.push({ label: 'Velocidad', value: `${item.speed}MHz` });
    } else if (typeKey === 'drive') {
        const size = item.size >= 1000 ? `${item.size / 1000}TB` : `${item.size}GB`;
        specs.push({ label: 'Capacidad', value: size });
        if (item.drive_type) {
            specs.push({ label: 'Tipo', value: item.drive_type.id != 1 ? `HDD, ${item.drive_type.name} RPM` : item.drive_type.name });
        }
    } else if (typeKey === 'gpu') {
        specs.push({ label: 'VRAM', value: `${item.vram}GB` });
        if (item.tdp) specs.push({ label: 'TDP', value: `${item.tdp}W` });
    } else if (typeKey === 'psu') {
        if (item.psu_type?.name) specs.push({ label: 'Tipo', value: item.psu_type.name });
        if (item.power) specs.push({ label: 'Potencia', value: `${item.power}W` });
        if (item.modularity) specs.push({ label: 'Modularidad', value: item.modularity.name });
        if (item.efficiency) specs.push({ label: 'Eficiencia', value: item.efficiency.name });
    } else if (typeKey === 'chassis') {
        if (item.chassis_type?.name) specs.push({ label: 'Tipo', value: item.chassis_type.name });
    }
    return specs;
};

const localMyRating = ref(props.myRating);
const showRatingDialog = ref(false);
const ratingSliderValue = ref(50);
const isSubmittingRating = ref(false);

watch(showAiDialog, (isOpen) => {
    if (isOpen) {
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.paddingRight = scrollbarWidth + 'px';
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
});

watch(showRatingDialog, (isOpen) => {
    if (isOpen) {
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.paddingRight = scrollbarWidth + 'px';
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
});

const starClass = (score, position) => {
    const starValue = position * 20;
    const previousStarValue = (position - 1) * 20;
    if (score >= starValue) {
        return 'bi-star-fill';
    } else if (score > previousStarValue + 5) {
        return 'bi-star-half';
    } else {
        return 'bi-star';
    }
};

const submitRating = async () => {
    if (isSubmittingRating.value) return;
    isSubmittingRating.value = true;

    try {
        await axios.post(`/api/v1/builds/${props.build.id}/ratings`, {
            rating: ratingSliderValue.value
        });

        localMyRating.value = ratingSliderValue.value;
        showRatingDialog.value = false;

        // Recargar la build mediante Inertia para que los campos de average y count se actualicen en pantalla
        router.reload({ only: ['build'] });
    } catch (error) {
        console.error('Error al guardar la valoración', error);
        alert(error.response?.data?.message || 'Error al guardar la valoración');
    } finally {
        isSubmittingRating.value = false;
    }
};

onUnmounted(() => {
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
});

const isOwner = computed(() => {
    return page.props.auth.user?.id === props.build.user_id;
});

const isExportingPDF = ref(false);

const exportPDF = async () => {
    if (isExportingPDF.value) return;
    isExportingPDF.value = true;

    try {
        const doc = new jsPDF({ unit: 'mm', format: 'a4' });
        const W = 210;
        const H = 297;
        const margin = 15;
        const indigo = [99, 102, 241];
        const darkText = [30, 30, 30];
        const grayText = [100, 100, 100];
        const borderColor = [200, 200, 210];
        const headerH = 28;
        const footerH = 14;

        const loadAsDataUrl = (path) =>
            fetch(path).then(r => r.blob()).then(blob => new Promise(resolve => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.readAsDataURL(blob);
            }));

        const profileUrl = props.build.user?.profile_url || '/img/default.jpg';

        const circularCrop = (dataUrl) => new Promise(resolve => {
            const img = new Image();
            img.onload = () => {
                const size = Math.min(img.width, img.height);
                const canvas = document.createElement('canvas');
                canvas.width = size;
                canvas.height = size;
                const ctx = canvas.getContext('2d');
                ctx.beginPath();
                ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
                ctx.clip();
                ctx.drawImage(img, (img.width - size) / 2, (img.height - size) / 2, size, size, 0, 0, size, size);
                resolve(canvas.toDataURL('image/png'));
            };
            img.src = dataUrl;
        });

        const [logoDataUrl, fontRegularDataUrl, fontBoldDataUrl, rawProfileDataUrl] = await Promise.all([
            loadAsDataUrl('/img/full_logo.png'),
            loadAsDataUrl('/fonts/quantico-regular.ttf'),
            loadAsDataUrl('/fonts/quantico-bold.ttf'),
            loadAsDataUrl(profileUrl),
        ]);

        const profileDataUrl = await circularCrop(rawProfileDataUrl);

        doc.addFileToVFS('Quantico-Regular.ttf', fontRegularDataUrl.split(',')[1]);
        doc.addFont('Quantico-Regular.ttf', 'Quantico', 'normal');
        doc.addFileToVFS('Quantico-Bold.ttf', fontBoldDataUrl.split(',')[1]);
        doc.addFont('Quantico-Bold.ttf', 'Quantico', 'bold');

        const logoImg = await new Promise(resolve => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.src = logoDataUrl;
        });
        const logoH = 20;
        const logoW = logoH * (logoImg.naturalWidth / logoImg.naturalHeight);

        const drawHeader = () => {
            doc.setFillColor(...indigo);
            doc.rect(0, 0, W, headerH, 'F');
            doc.addImage(logoDataUrl, 'PNG', (W - logoW) / 2, 4, logoW, logoH);
        };

        const drawFooter = () => {
            doc.setFillColor(...indigo);
            doc.rect(0, H - footerH, W, footerH, 'F');
            doc.setFont('Quantico', 'normal');
            doc.setFontSize(9);
            doc.setTextColor(255, 255, 255);
            doc.text('© 2026 SATAllite v1.2', W / 2, H - footerH + 9, { align: 'center' });
        };

        const types = [
            { key: 'motherboard', label: 'Placa base' },
            { key: 'cpu', label: 'Procesador' },
            { key: 'ram', label: 'RAM' },
            { key: 'gpu', label: 'Tarjeta gráfica' },
            { key: 'drive', label: 'Almacenamiento' },
            { key: 'psu', label: 'Fuente de alimentación' },
            { key: 'chassis', label: 'Caja' },
        ];
        const activeComponents = types.filter(t => props.build[t.key]);

        // Pre-calcular altura total del contenido para centrarlo verticalmente
        const calcSpecLines = (specs) => {
            if (!specs.length) return 0;
            doc.setFontSize(8);
            let lines = 1;
            let currentX = 0;
            for (const spec of specs) {
                const w = doc.getTextWidth(`${spec.label}: ${spec.value}   `);
                if (currentX + w > W - margin * 2 - 8 && currentX > 0) {
                    lines++;
                    currentX = 0;
                }
                currentX += w;
            }
            return lines;
        };

        const avatarSize = 8;
        const titleFontSize = 30;
        const titleLineH = 12;

        doc.setFont('Quantico', 'bold');
        doc.setFontSize(titleFontSize);
        const titleLines = doc.splitTextToSize(props.build.name, W - margin * 2);

        let totalH = titleLines.length * titleLineH + 7; // título + espacio al subrayado
        if (props.build.user) totalH += avatarSize + 4;  // avatar + gap
        totalH += 6;                                      // espacio antes del primer card

        for (const type of activeComponents) {
            const specs = getSpecs(type.key, props.build[type.key]);
            const specLines = calcSpecLines(specs);
            totalH += 4 + 7 + 2 + 7 + (specLines > 0 ? specLines * 4 + 3 : 0) + 5 + 5;
        }

        const availableH = H - headerH - footerH;
        const startY = totalH <= availableH
            ? headerH + Math.max(16, (availableH - totalH) / 2)
            : headerH + 16;

        // Renderizado
        drawHeader();
        let y = startY;

        // Título
        doc.setFont('Quantico', 'bold');
        doc.setFontSize(titleFontSize);
        doc.setTextColor(...darkText);
        doc.text(titleLines, W / 2, y, { align: 'center' });
        y += titleLines.length * titleLineH + 1;

        // Subrayado del título
        doc.setDrawColor(...borderColor);
        doc.setLineWidth(0.5);
        doc.line(margin, y, W - margin, y);
        y += 6;

        // Autor con foto de perfil
        if (props.build.user) {
            const authorLabel = `Creada por ${props.build.user.name}`;
            doc.setFont('Quantico', 'normal');
            doc.setFontSize(10);
            const textW = doc.getTextWidth(authorLabel);
            const groupW = avatarSize + 3 + textW;
            const groupX = (W - groupW) / 2;

            doc.addImage(profileDataUrl, 'PNG', groupX, y, avatarSize, avatarSize);
            doc.setTextColor(...grayText);
            doc.text(authorLabel, groupX + avatarSize + 3, y + avatarSize * 0.68);
            y += avatarSize + 4;
        }

        y += 2;

        // Cards de componentes
        for (const type of activeComponents) {
            const component = props.build[type.key];
            const specs = getSpecs(type.key, component);
            const specLines = calcSpecLines(specs);
            const cardH = 4 + 7 + 2 + 7 + (specLines > 0 ? specLines * 4 + 3 : 0) + 5;

            if (y + cardH > H - footerH - 5) {
                drawFooter();
                doc.addPage();
                drawHeader();
                y = headerH + 10;
            }

            const cardStartY = y;
            y += 4;

            // Nombre del tipo (label en indigo, bold)
            doc.setFont('Quantico', 'bold');
            doc.setFontSize(11);
            doc.setTextColor(...indigo);
            doc.text(type.label, margin + 4, y);
            y += 3;

            // Separador interno
            doc.setDrawColor(...borderColor);
            doc.setLineWidth(0.2);
            doc.line(margin + 4, y, W - margin - 4, y);
            y += 4;

            // Nombre del componente y precio
            doc.setFont('Quantico', 'normal');
            doc.setFontSize(10);
            doc.setTextColor(...darkText);
            doc.text(component.name, margin + 4, y);
            doc.setFont('Quantico', 'bold');
            doc.setTextColor(...indigo);
            doc.text(component.price ? `${component.price}€` : 'Sin stock', W - margin - 2, y, { align: 'right' });
            doc.setFont('Quantico', 'normal');
            y += 6;

            // Specs con label en indigo y valor en gris
            if (specs.length > 0) {
                y += 1;
                doc.setFontSize(8);
                let currentX = margin + 4;
                let currentY = y;

                for (const spec of specs) {
                    const labelText = `${spec.label}: `;
                    const valueText = `${spec.value}   `;
                    const labelW = doc.getTextWidth(labelText);
                    const valueW = doc.getTextWidth(valueText);

                    if (currentX + labelW + valueW > W - margin - 4 && currentX > margin + 4) {
                        currentX = margin + 4;
                        currentY += 4;
                    }

                    doc.setTextColor(...indigo);
                    doc.text(labelText, currentX, currentY);
                    doc.setTextColor(...grayText);
                    doc.text(valueText.trimEnd(), currentX + labelW, currentY);
                    currentX += labelW + valueW;
                }

                y = currentY + 5;
            }

            y += 1;

            // Borde del card
            doc.setDrawColor(...borderColor);
            doc.setLineWidth(0.3);
            doc.roundedRect(margin, cardStartY, W - margin * 2, y - cardStartY, 2, 2, 'S');

            y += 4;
        }

        drawFooter();
        doc.save(`${props.build.name}.pdf`);
    } catch (error) {
        console.error('Error al generar el PDF:', error);
        alert('Ha ocurrido un error al generar el PDF.');
    } finally {
        isExportingPDF.value = false;
    }
};

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

const deleteBuild = async () => {
    try {
        await axios.delete(`/api/v1/builds/${props.build.id}`, {
            withCredentials: true,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': getCookie('XSRF-TOKEN')
            }
        });
        router.visit('/profile');
    } catch (error) {
        console.error('Error al eliminar la build:', error);
        alert('Ha ocurrido un error al eliminar la configuración. ' + (error.response?.data?.message || ''));
    }
};
</script>
