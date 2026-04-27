<template>
    <Header />
    <main class="container tw:mx-auto tw:px-4 tw:py-8 tw:relative">
        <p class="tw:text-6xl quantico-bold tw:border-2 border-start-0 border-end-0 border-top-0 tw:border-gray-500 pb-3 text-center">
            Configurador de PCs
        </p>
        
        <div class="my-2">
            <!-- Component Dropdowns -->
            <ComponentDropdown 
                v-for="type in componentTypes" 
                :key="type.key"
                :type="type"
                :selected="selections[type.key]"
                :isOpen="openDropdown === type.key"
                :filters="getFiltersFor(type.key)"
                @toggle="toggleDropdown(type.key)"
                @select="selectComponent(type.key, $event)"
                @remove="selectComponent(type.key, null)"
                :disabled="!isSelectable(type.key)"
                :warningMsg="getDropdownWarning(type.key)"
            />
        </div>

        <!-- Errores de validación al guardar -->
        <div v-if="errors.length > 0" class="mt-4 p-4 tw:bg-red-100 tw:border-3 tw:border-red-500 tw:text-red-700 rounded-5 rounded-bottom-right-none">
            <p class="h3 mb-3 mx-3">Errores de validación</p>
            <ul class="tw:list-disc tw:list-inside tw:space-y-1 tw:ml-1">
                <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
        </div>

        <!-- Preferencias y Advertencias -->
        <div v-if="warnings.length > 0" class="mt-4 p-4 tw:bg-amber-100 tw:border-3 tw:border-amber-500 tw:text-amber-700 rounded-5 rounded-bottom-right-none">
            <p class="h3 mb-3 mx-3">Advertencias de compatibilidad</p>
            <ul class="tw:list-disc tw:list-inside tw:space-y-1 tw:ml-1">
                <li v-for="(warning, index) in warnings" :key="index">{{ warning }}</li>
            </ul>
        </div>

        <div class="mt-4 p-4 rounded-4 rounded-bottom-right-none tw:shadow-sm tw:border tw:dark:border-gray-700">
            <div class="tw:flex tw:flex-row tw:items-end tw:gap-6 tw:mb-4 tw:flex-nowrap">
                <div class="tw:flex-1 tw:min-w-0">
                    <label class="tw:block tw:text-lg tw:font-bold tw:text-gray-700 tw:dark:text-gray-300 tw:mb-2">Nombre de la Build</label>
                    <input v-model="buildName" type="text" class="tw:w-full tw:text-lg tw:py-2 border-bottom tw:border-gray-500 tw:bg-transparent tw:border-t-0 tw:border-l-0 tw:border-r-0 tw:focus:ring-0 tw:focus:outline-none tw:focus:border-gray-500 tw:dark:text-white" placeholder="Ej. Mi PC de ensueño">
                </div>
                <div class="tw:shrink-0 tw:pb-0.5">
                    <label class="tw:cursor-pointer tw:select-none d-flex align-items-center">
                        <p class="tw:text-lg tw:font-medium tw:me-5 mb-0">Guardar como pública</p>
                        <input type="checkbox" v-model="isPublic" class="tw:sr-only">
                        <i class="bi tw:text-3xl tw:leading-none tw:transition-colors" :class="isPublic ? 'bi-toggle-on tw:text-indigo-600' : 'bi-toggle-off tw:text-gray-400'"></i>
                    </label>
                </div>
            </div>

            <div class="tw:flex tw:gap-4 tw:mt-4">
                <button @click="saveBuild" class="tw:flex-1 tw:bg-indigo-500 tw:hover:bg-indigo-400 tw:text-white tw:font-bold tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition tw:duration-150">Guardar</button>
                <button @click="exportPdf" class="tw:flex-1 tw:bg-red-600 tw:hover:bg-red-500 tw:text-white tw:font-bold tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition tw:duration-150">Exportar PDF</button>
                <button @click="showAIPrompt = true" class="tw:flex-1 tw:bg-linear-to-r tw:from-purple-500 tw:to-pink-500 tw:hover:from-purple-600 tw:hover:to-pink-600 text-white tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition tw:duration-150">Resumen IA</button>
            </div>
        </div>

        <!-- AI Dialog -->
        <div v-if="showAIPrompt" class="tw:fixed tw:inset-0 tw:z-50 tw:flex tw:items-center tw:justify-center tw:bg-black tw:bg-opacity-50">
            <div class="tw:bg-white tw:dark:bg-gray-800 tw:p-6 tw:rounded-lg tw:shadow-xl tw:max-w-sm tw:w-full">
                <h3 class="tw:text-lg tw:font-bold tw:mb-2">Resumen IA</h3>
                <p class="tw:text-gray-600 tw:dark:text-gray-300 tw:mb-4">Esta es una funcionalidad en desarrollo.</p>
                <button @click="showAIPrompt = false" class="tw:w-full tw:bg-gray-200 tw:hover:bg-gray-300 tw:text-gray-800 tw:font-bold tw:py-2 tw:px-4 tw:rounded">Cerrar</button>
            </div>
        </div>
    </main>
    <Footer />
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
import ComponentDropdown from '@/Components/Builder/ComponentDropdown.vue';

// Mock component types. Icons etc would be defined here.
const componentTypes = [
    { key: 'motherboard', name: 'Placa base', icon: 'motherboard' },
    { key: 'cpu', name: 'Procesador', icon: 'cpu' },
    { key: 'ram', name: 'RAM', icon: 'memory' },
    { key: 'drive', name: 'Disco duro', icon: 'device-ssd' },
    { key: 'gpu', name: 'Tarjeta gráfica', icon: 'gpu-card' },
    { key: 'psu', name: 'Fuente de alimentación', icon: 'plug-fill' },
    { key: 'chassis', name: 'Caja', icon: 'pc-display' }
];

const caseCompatibility = {
  "ATX Full Tower": [
    "HPTX", "XL ATX", "SSI EEB", "SSI CEB", "ATX", 
    "Micro ATX", "Flex", "Mini DTX", "Mini ITX", "Thin"
  ],
  "ATX Mid Tower": [
    "SSI CEB", "ATX", "Micro ATX", "Flex", 
    "Mini DTX", "Mini ITX", "Thin"
  ],
  "ATX Desktop": [
    "ATX", "Micro ATX", "Flex", "Mini DTX", "Mini ITX", "Thin"
  ],
  "ATX Mini Tower": [
    "ATX", "Micro ATX", "Flex", "Mini DTX", "Mini ITX", "Thin"
  ],
  "ATX Test Bench": [
    "HPTX", "XL ATX", "SSI EEB", "SSI CEB", "ATX", 
    "Micro ATX", "Flex", "Mini DTX", "Mini ITX", "Thin"
  ],
  "HTPC": [
    "Micro ATX", "Flex", "Mini DTX", "Mini ITX", "Thin"
  ],
  "MicroATX Mid Tower": [
    "Micro ATX", "Flex", "Mini DTX", "Mini ITX", "Thin"
  ],
  "MicroATX Mini Tower": [
    "Micro ATX", "Flex", "Mini DTX", "Mini ITX", "Thin"
  ],
  "MicroATX Desktop": [
    "Micro ATX", "Flex", "Mini DTX", "Mini ITX", "Thin"
  ],
  "MicroATX Slim": [
    "Micro ATX", "Flex", "Mini ITX", "Thin"
  ],
  "Mini ITX Tower": [
    "Mini ITX", "Mini DTX", "Thin"
  ],
  "Mini ITX Desktop": [
    "Mini ITX", "Thin"
  ],
  "Mini ITX Test Bench": [
    "Mini ITX", "Mini DTX", "Thin"
  ],
  "Rackmount 2U": [
    "Micro ATX", "Flex", "Mini ITX", "Thin"
  ],
  "Rackmount 3U": [
    "ATX", "Micro ATX", "Flex", "Mini ITX", "Thin"
  ],
  "Rackmount 4U": [
    "SSI EEB", "SSI CEB", "ATX", "Micro ATX", "Flex", "Mini ITX", "Thin"
  ],
  "Rackmount 5U": [
    "SSI EEB", "SSI CEB", "ATX", "Micro ATX", "Flex", "Mini ITX", "Thin"
  ]
};

const openDropdown = ref(null);
const buildName = ref('');
const isPublic = ref(false);
const showAIPrompt = ref(false);
const errors = ref([]);

const selections = reactive({
    motherboard: null,
    cpu: null,
    ram: null,
    drive: null,
    gpu: null,
    psu: null,
    chassis: null
});

const toggleDropdown = (key) => {
    if (openDropdown.value === key) {
        openDropdown.value = null;
    } else {
        openDropdown.value = key;
    }
};

const selectComponent = (key, component) => {
    // Si cambiamos la placa base, debemos invalidar CPU y RAM si sus sockets o tipos de RAM no coinciden, 
    // o simplemente limpiarlos para evitar incompatibilidades
    if (key === 'motherboard' && selections.motherboard?.id !== component?.id) {
        selections.cpu = null;
        selections.ram = null;
    }
    
    selections[key] = component;
    
    openDropdown.value = null;
};

// Validations and restrictions logic mapped as required
const isSelectable = (key) => {
    if ((key === 'cpu' || key === 'ram') && !selections.motherboard) {
        return false;
    }
    
    if (key === 'cpu') {
        const socketName = selections.motherboard?.socket?.name || '';
        if (socketName.toLowerCase().includes('integrated')) return false;
    }
    return true;
};

const getFiltersFor = (key) => {
    const filters = {};
    if (selections.motherboard) {
        if (key === 'cpu' && selections.motherboard.socket?.id) {
            filters.socket_id = selections.motherboard.socket.id;
        }
        if (key === 'ram' && selections.motherboard.ram_type?.id) {
            filters.ram_type_id = selections.motherboard.ram_type.id;
        }
    }
    return filters;
};

const getDropdownWarning = (key) => {
    if ((key === 'cpu' || key === 'ram') && !selections.motherboard) {
        return 'Debes elegir primero una placa base para poder elegir este componente';
    }
    
    if (key === 'cpu') {
       const socketName = selections.motherboard?.socket?.name || '';
       if (socketName.toLowerCase().includes('integrated')) {
           return 'La placa base tiene procesador integrado.';
       }
    }
    return null;
};

const warnings = computed(() => {
    const msgs = [];
    
    // Check combined TDP against PSU
    if (selections.cpu && selections.gpu && selections.psu) {
        const combinedTDP = (selections.cpu.tdp || 0) + (selections.gpu.tdp || 0);
        if ((selections.psu.power || 0) >= combinedTDP && (selections.psu.power || 0) < (combinedTDP * 1.3)) {
             msgs.push('La fuente de alimentación puede no ser lo suficientemente potente. Se recomienda dejar un margen razonable.');
        }
    }
    
    // Check form factor
    if (selections.motherboard && selections.chassis) {
        const isCompatible = caseCompatibility[selections.chassis.chassis_type.name]?.includes(selections.motherboard.form_factor.name);
        if (!isCompatible) {
            msgs.push('La placa base elegida puede no ser compatible con el factor de forma de la caja.');
        }
    }

    return msgs;
});

const saveBuild = () => {
    errors.value = [];
    
    if (!buildName.value || buildName.value.trim() === '') {
        errors.value.push('El nombre de la configuración no puede estar vacío.');
    }
    
    const isCpuIntegrated = selections.motherboard?.socket?.name?.toLowerCase().includes('integrated');
    const hasIgpu = selections.cpu?.igpu != null;
    
    componentTypes.forEach(type => {
        if (!selections[type.key]) {
            if (type.key === 'cpu' && isCpuIntegrated) return;
            if (type.key === 'gpu' && hasIgpu) return;
            errors.value.push(`Aún no has seleccionado ningún componente para: ${type.name}`);
        }
    });

    if (selections.cpu && selections.gpu && selections.psu) {
        const combinedTDP = (selections.cpu.tdp || 0) + (selections.gpu.tdp || 0);
        if (combinedTDP > (selections.psu.power || 0)) {
            errors.value.push(`La suma del TDP del procesador y la gráfica (${combinedTDP}W) supera la capacidad de la fuente de alimentación (${selections.psu.power}W).`);
        }
    }

    if (errors.value.length > 0) {
        // En un comportamiento real habría un scroll hacia arriba o foco visual
        return;
    }

    // In complete implementation, post via Inertia useForm to backend
    console.log('Saved', { name: buildName.value, public: isPublic.value, build: selections });
};

const exportPdf = () => {
    console.log('Exporting PDF');
};
</script>
