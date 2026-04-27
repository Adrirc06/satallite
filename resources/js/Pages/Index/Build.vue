<template>
    <Header />
    <main class="container tw:mx-auto tw:px-4 tw:py-8 tw:relative">
        <p class="tw:text-6xl quantico-bold tw:border-2 border-start-0 border-end-0 border-top-0 tw:border-gray-500 pb-3 text-center">
            {{ build.name }}
        </p>

        <div class="tw:my-6 tw:flex tw:flex-col tw:gap-4">            
            <div class="tw:grid tw:grid-cols-1 md:tw:grid-cols-2 lg:tw:grid-cols-3 tw:gap-4">
                
                <!-- Motherboard -->
                <div v-if="build.motherboard" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-motherboard tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Placa base</p>
                    </div>
                    <p class="tw:font-medium">{{ build.motherboard.name }}</p>
                    <p class="tw:text-sm tw:text-gray-500 tw:mt-1">
                        Socket: {{ build.motherboard.socket?.name }}<template v-if="build.motherboard.max_memory"> | Memoria máxima: {{ build.motherboard.max_memory }}GB</template><template v-if="build.motherboard.form_factor"> | Factor de forma: {{ build.motherboard.form_factor.name }}</template><template v-if="build.motherboard.ram_type"> | Tipo de RAM: {{ build.motherboard.ram_type.name }}</template>
                    </p>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.motherboard.price }}€</p>
                </div>

                <!-- CPU -->
                <div v-if="build.cpu" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-cpu tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Procesador</p>
                    </div>
                    <p class="tw:font-medium">{{ build.cpu.name }}</p>
                    <p class="tw:text-sm tw:text-gray-500 tw:mt-1">
                        Socket: {{ build.cpu.socket?.name }}<template v-if="build.cpu.cores"> | {{ build.cpu.cores }} Núcleos / {{ build.cpu.cores * 2 }} Hilos</template><template v-if="build.cpu.frequency"> | {{ build.cpu.frequency }}GHz - {{ build.cpu.max_frequency || build.cpu.frequency }}GHz</template><template v-if="build.cpu.tdp"> | TDP: {{ build.cpu.tdp }}W</template><template v-if="build.cpu.igpu"> | Gráficos Integrados: {{ build.cpu.igpu.name || 'No' }}</template><template v-else> | Gráficos Integrados: No</template>
                    </p>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.cpu.price }}€</p>
                </div>

                <!-- RAM -->
                <div v-if="build.ram" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-memory tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">RAM</p>
                    </div>
                    <p class="tw:font-medium">{{ build.ram.name }}</p>
                    <p class="tw:text-sm tw:text-gray-500 tw:mt-1">
                        {{ build.ram.size * build.ram.modules }}GB ({{ build.ram.size }}GB x {{ build.ram.modules }})<template v-if="build.ram.ram_type"> | {{ build.ram.ram_type.name }}</template><template v-if="build.ram.cas_latency"> | Latencia: CL{{ build.ram.cas_latency }}</template><template v-if="build.ram.speed"> | Velocidad: {{ build.ram.speed }}MHz</template>
                    </p>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.ram.price }}€</p>
                </div>

                <!-- GPU -->
                <div v-if="build.gpu" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-gpu-card tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Tarjeta gráfica</p>
                    </div>
                    <p class="tw:font-medium">{{ build.gpu.name }}</p>
                    <p class="tw:text-sm tw:text-gray-500 tw:mt-1">
                        {{ build.gpu.vram }}GB VRAM<template v-if="build.gpu.tdp"> | {{ build.gpu.tdp }}W</template>
                    </p>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.gpu.price }}€</p>
                </div>

                <!-- Drive -->
                <div v-if="build.drive" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-device-ssd tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Almacenamiento</p>
                    </div>
                    <p class="tw:font-medium">{{ build.drive.name }}</p>
                    <p class="tw:text-sm tw:text-gray-500 tw:mt-1">
                        <template v-if="build.drive.size >= 1000">{{ build.drive.size / 1000 }}TB</template>
                        <template v-else>{{ build.drive.size }}GB</template>
                        <template v-if="build.drive.drive_type"> | <template v-if="build.drive.drive_type.id != 1">HDD, {{ build.drive.drive_type.name }} RPM</template><template v-else>{{ build.drive.drive_type.name }}</template></template>
                    </p>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.drive.price }}€</p>
                </div>

                <!-- PSU -->
                <div v-if="build.psu" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-plug-fill tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Fuente de alimentación</p>
                    </div>
                    <p class="tw:font-medium">{{ build.psu.name }}</p>
                    <p class="tw:text-sm tw:text-gray-500 tw:mt-1">
                        Tipo: {{ build.psu.psu_type?.name }}<template v-if="build.psu.power"> | Potencia: {{ build.psu.power }}W</template><template v-if="build.psu.modularity"> | Modularidad: {{ build.psu.modularity.name }}</template><template v-if="build.psu.efficiency"> | Eficiencia: {{ build.psu.efficiency.name }}</template>
                    </p>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.psu.price }}€</p>
                </div>

                <!-- Chassis -->
                <div v-if="build.chassis" class="tw:border rounded-4 rounded-bottom-right-none tw:p-4 tw:shadow-sm tw:relative">
                    <div class="tw:flex tw:items-center tw:mb-2">
                        <i class="bi bi-pc-display tw:text-indigo-500 tw:text-2xl tw:mr-3"></i>
                        <p class="tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-200 h4 mb-0">Caja</p>
                    </div>
                    <p class="tw:font-medium">{{ build.chassis.name }}</p>
                    <p class="tw:text-sm tw:text-gray-500 tw:mt-1">
                        {{ build.chassis.chassis_type?.name }}
                    </p>
                    <p class="tw:absolute tw:bottom-1 tw:right-4 tw:text-xl tw:font-bold tw:text-indigo-500">{{ build.chassis.price }}€</p>
                </div>

            </div>
        </div>

        <div class="tw:mt-8 tw:p-4 rounded-4 rounded-bottom-right-none tw:shadow-sm tw:border tw:border-gray-200 tw:dark:border-gray-700">
            <div class="tw:flex tw:flex-col tw:sm:flex-row tw:gap-4">
                <button v-if="isOwner" @click="showDevDialog = true" class="tw:flex-1 tw:py-3 tw:px-4 tw:bg-indigo-500 tw:hover:bg-indigo-400 tw:text-white tw:font-bold rounded-3 rounded-bottom-right-none tw:transition">Editar</button>
                <button v-else @click="showDevDialog = true" class="tw:flex-1 tw:py-3 tw:px-4 tw:bg-indigo-500 tw:hover:bg-indigo-400 tw:text-white tw:font-bold rounded-3 rounded-bottom-right-none tw:transition">Usar como base</button>
                
                <button @click="showDevDialog = true" class="tw:flex-1 tw:bg-red-600 tw:hover:bg-red-500 tw:text-white tw:font-bold tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition">Exportar PDF</button>
                <button @click="showDevDialog = true" class="tw:flex-1 tw:bg-linear-to-r tw:from-purple-500 tw:to-pink-500 tw:hover:from-purple-600 tw:hover:to-pink-600 text-white tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition">Resumen IA</button>
                <button v-if="isOwner" data-bs-toggle="modal" data-bs-target="#modalDeleteBuild" class="tw:flex-1 tw:bg-red-600 tw:hover:bg-red-500 tw:text-white tw:font-bold tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition">Eliminar</button>
            </div>
        </div>

        <!-- En Desarrollo Dialog -->
        <div v-if="showDevDialog" class="tw:fixed tw:inset-0 tw:z-50 tw:flex tw:items-center tw:justify-center tw:bg-black tw:bg-opacity-50">
            <div class="tw:bg-white tw:dark:bg-gray-800 tw:p-6 tw:rounded-lg tw:shadow-xl tw:max-w-sm tw:w-full">
                <h3 class="tw:text-lg tw:font-bold tw:mb-2">En desarrollo</h3>
                <p class="tw:text-gray-600 tw:dark:text-gray-300 tw:mb-4">Esta no es una función lista todavía. Pronto estará disponible.</p>
                <button @click="showDevDialog = false" class="tw:w-full tw:bg-gray-200 tw:hover:bg-gray-300 tw:text-gray-800 tw:font-bold tw:py-2 tw:px-4 tw:rounded">Cerrar</button>
            </div>
        </div>

        <!-- Eliminar Build Dialog -->
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
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import Header from '@/Layouts/Header.vue';
import Footer from '@/Layouts/Footer.vue';

const props = defineProps({
    build: {
        type: Object,
        required: true
    }
});

const page = usePage();
const showDevDialog = ref(false);

const isOwner = computed(() => {
    return page.props.auth.user?.id === props.build.user_id;
});

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
        
        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

        router.visit('/profile');
    } catch (error) {
        console.error('Error al eliminar la build:', error);
        alert('Ha ocurrido un error al eliminar la configuración. ' + (error.response?.data?.message || ''));
    }
};
</script>
