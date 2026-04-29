<template>
    <Header />
    <main class="container tw:mx-auto tw:px-4 tw:py-8 tw:relative">
        <p class="tw:text-3xl tw:md:text-6xl quantico-bold tw:border-2 border-start-0 border-end-0 border-top-0 tw:border-gray-500 pb-3 text-center">
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

        <div ref="errorsContainer" v-if="errors.length > 0" class="mt-4 p-4 tw:bg-red-100 tw:border-3 tw:border-red-500 tw:text-red-700 rounded-5 rounded-bottom-right-none">
            <p class="h3 mb-3 mx-3">Errores de validación</p>
            <ul class="tw:list-disc tw:list-inside tw:space-y-1 tw:ml-1">
                <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
        </div>

        <!-- Preferencias y Advertencias -->
        <div v-if="compatibilityWarnings.length > 0" class="mt-4 p-4 tw:bg-amber-100 tw:border-3 tw:border-amber-500 tw:text-amber-700 rounded-5 rounded-bottom-right-none">
            <p class="h3 mb-3 mx-3">Advertencias de compatibilidad</p>
            <ul class="tw:list-disc tw:list-inside tw:space-y-1 tw:ml-1">
                <li v-for="(warning, index) in compatibilityWarnings" :key="index">{{ warning }}</li>
            </ul>
        </div>

        <div class="mt-4 p-4 rounded-4 rounded-bottom-right-none tw:shadow-sm tw:border tw:dark:border-gray-700">
            <div class="tw:flex tw:flex-col tw:sm:flex-row tw:items-start tw:sm:items-end tw:gap-6 tw:mb-4 tw:flex-nowrap">
                <div class="tw:flex-1 tw:min-w-0">
                    <label for="build-name" class="tw:block tw:text-lg tw:font-bold tw:mb-2">Nombre de la Build</label>
                    <input v-model="buildName" type="text" id="build-name" name="build_name" maxlength="32" class="tw:w-full tw:text-lg tw:py-2 border-bottom tw:border-gray-500 tw:bg-transparent tw:border-t-0 tw:border-l-0 tw:border-r-0 tw:focus:ring-0 tw:focus:outline-none tw:focus:border-gray-500" placeholder="PC gaming para 2026">
                </div>
                <div class="tw:shrink-0 tw:pb-0.5">
                    <label class="tw:cursor-pointer tw:select-none d-flex align-items-center">
                        <p class="tw:text-lg tw:font-medium tw:me-5 mb-0">Guardar como pública</p>
                        <input type="checkbox" id="is-public" name="is_public" v-model="isPublic" class="tw:sr-only">
                        <i class="bi tw:text-3xl tw:leading-none tw:transition-colors" :class="isPublic ? 'bi-toggle-on tw:text-indigo-600' : 'bi-toggle-off tw:text-gray-400'"></i>
                    </label>
                </div>
            </div>

            <div class="tw:flex tw:gap-4 tw:mt-4">
                <button @click="saveBuild" class="tw:flex-1 tw:bg-indigo-500 tw:hover:bg-indigo-400 tw:text-white tw:font-bold tw:py-3 tw:px-4 rounded-3 rounded-bottom-right-none tw:transition tw:duration-150">Guardar</button>
            </div>
        </div>
    </main>
    <Footer />
</template>

<script setup>
import { ref, reactive, computed, nextTick } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
import ComponentDropdown from '@/Components/ComponentDropdown.vue';

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

const props = defineProps({
    build: {
        type: Object,
        default: null
    },
    mode: {
        type: String,
        default: 'new'
    }
});

const page = usePage();

const errorsContainer = ref(null);
const openDropdown = ref(null);
const buildName = ref(props.build && props.mode === 'edit' ? props.build.name : '');
const isPublic = ref(props.build && props.mode === 'edit' ? Boolean(props.build.is_public) : false);
const errors = ref([]);

const selections = reactive({
    motherboard: props.build?.motherboard || null,
    cpu: props.build?.cpu || null,
    ram: props.build?.ram || null,
    drive: props.build?.drive || null,
    gpu: props.build?.gpu || null,
    psu: props.build?.psu || null,
    chassis: props.build?.chassis || null
});

const toggleDropdown = (key) => {
    if (openDropdown.value === key) {
        openDropdown.value = null;
    } else {
        openDropdown.value = key;
    }
};

const selectComponent = (key, component) => {
    if (key === 'motherboard' && selections.motherboard?.id !== component?.id) {
        selections.cpu = null;
        selections.ram = null;
    }
    
    selections[key] = component;
    
    openDropdown.value = null;
};

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

const compatibilityWarnings = computed(() => {
    const warnings = [];
    
    if (selections.cpu && selections.gpu && selections.psu) {
        const combinedTDP = (selections.cpu.tdp || 0) + (selections.gpu.tdp || 0);
        if ((selections.psu.power || 0) >= combinedTDP && (selections.psu.power || 0) < (combinedTDP * 1.3)) {
             warnings.push('La fuente de alimentación puede no ser lo suficientemente potente. Se recomienda dejar un margen razonable.');
        }
    }
    
    if (selections.motherboard && selections.chassis) {
        const isCompatible = caseCompatibility[selections.chassis.chassis_type.name]?.includes(selections.motherboard.form_factor.name);
        if (!isCompatible) {
            warnings.push('La placa base elegida puede no ser compatible con el factor de forma de la caja.');
        }
    }

    return warnings;
});

const saveBuild = () => {
    if (!page.props.auth?.user) {
        router.visit('/login');
        return;
    }

    errors.value = [];
    
    if (!buildName.value || buildName.value.trim() === '') {
        errors.value.push('El nombre de la configuración no puede estar vacío.');
    } else if (buildName.value.length > 32) {
        errors.value.push('El nombre de la configuración no puede superar los 32 caracteres.');
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
        nextTick(() => {
            if (errorsContainer.value) {
                errorsContainer.value.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
        return;
    }

    const payload = {
        name: buildName.value,
        is_public: isPublic.value,
        motherboard_id: selections.motherboard?.id,
        cpu_id: selections.cpu?.id,
        ram_id: selections.ram?.id,
        drive_id: selections.drive?.id,
        gpu_id: selections.gpu?.id,
        psu_id: selections.psu?.id,
        chassis_id: selections.chassis?.id
    };

    const request = (props.mode === 'edit' && props.build)
        ? axios.put('/api/v1/builds/' + props.build.id, payload)
        : axios.post('/api/v1/builds', payload);

    request
    .then(response => {       
        router.visit('/build/' + response.data.data.id);
    })
    .catch(error => {
        console.error('Error desde el servidor:', error.response?.data);
        if (error.response?.status === 422) {
            const serverErrors = error.response.data.errors;
            Object.values(serverErrors).forEach(errArray => {
                errArray.forEach(err => errors.value.push(err));
            });
            nextTick(() => {
                if (errorsContainer.value) {
                    errorsContainer.value.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    });
};
</script>
