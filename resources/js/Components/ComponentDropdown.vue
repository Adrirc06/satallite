<template>
    <div class="tw:bg-transparent tw:rounded-2xl tw:rounded-br-none rounded-bottom-right-none tw:shadow-sm tw:mb-3 p-3 tw:overflow-hidden tw:border tw:border-gray-300 tw:dark:border-gray-700 tw:relative">
        <div
            ref="headerEl"
            @click="$emit('toggle')"
            class="tw:p-4 tw:cursor-pointer tw:transition"
            :class="{'tw:opacity-50 tw:cursor-not-allowed': disabled}"
        >
            <!-- Sin componente seleccionado -->
            <template v-if="!selected">
                <div class="tw:flex tw:items-center">
                    <div class="tw:text-indigo-500 tw:flex tw:items-center tw:justify-center tw:text-3xl tw:mr-4">
                        <i :class="`bi bi-${type.icon}`"></i>
                    </div>
                    <p class="h3 mb-0">{{ type.name }}</p>
                    <div class="tw:flex tw:items-center tw:ml-auto">
                        <i class="bi bi-caret-down-fill tw:text-gray-500 tw:transition-transform tw:duration-300 tw:text-2xl" :class="{ 'tw:rotate-180': isOpen }"></i>
                    </div>
                </div>
            </template>

            <!-- Componente seleccionado para móvil -->
            <template v-else>
                <div class="tw:flex tw:flex-col tw:gap-1 tw:sm:hidden">
                    <p class="tw:text-sm tw:font-medium tw:text-gray-500 tw:dark:text-gray-400 tw:uppercase tw:tracking-wide">{{ type.name }}</p>
                    <div class="tw:flex tw:items-center tw:justify-between tw:gap-2">
                        <p class="tw:text-lg tw:font-bold tw:mb-0">{{ selected.name }}</p>
                        <div class="tw:flex tw:items-center tw:gap-3 tw:shrink-0">
                            <span class="tw:font-bold">{{ selected.price ? selected.price + '€' : 'Sin stock' }}</span>
                            <button @click.stop="$emit('remove')" title="Eliminar componente">
                                <i class="bi bi-x tw:text-xl"></i>
                            </button>
                            <i class="bi bi-caret-down-fill tw:text-gray-500 tw:transition-transform tw:duration-300 tw:text-2xl" :class="{ 'tw:rotate-180': isOpen }"></i>
                        </div>
                    </div>
                    <div class="tw:flex tw:flex-wrap tw:gap-x-4 tw:gap-y-1 tw:mt-1 tw:text-sm">
                        <span v-for="spec in getSpecs(type.key, selected)" :key="spec.label">
                            <span class="tw:text-gray-500 tw:dark:text-gray-400">{{ spec.label }}:</span>
                            <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                        </span>
                    </div>
                </div>

                <!-- Componente seleccionado normal -->
                <div class="tw:hidden tw:sm:flex tw:items-center">
                    <div class="tw:text-indigo-500 tw:flex tw:items-center tw:justify-center tw:text-3xl tw:mr-4">
                        <i :class="`bi bi-${type.icon}`"></i>
                    </div>
                    <div class="tw:flex-1 tw:flex tw:flex-col tw:justify-center">
                        <p class="tw:text-sm tw:font-medium tw:text-gray-500 tw:dark:text-gray-400 tw:uppercase tw:tracking-wide">{{ type.name }}</p>
                        <p class="tw:text-lg tw:mt-1">{{ selected.name }}</p>
                        <div class="tw:flex tw:flex-wrap tw:gap-x-4 tw:gap-y-1 tw:mt-2 tw:text-sm">
                            <span v-for="spec in getSpecs(type.key, selected)" :key="spec.label">
                                <span class="tw:text-gray-500 tw:dark:text-gray-400">{{ spec.label }}:</span>
                                <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="tw:flex tw:items-center tw:gap-6 tw:ml-4">
                        <span class="tw:font-bold tw:text-lg">
                            {{ selected.price ? selected.price + '€' : 'Sin stock' }}
                        </span>
                        <button @click.stop="$emit('remove')" title="Eliminar componente">
                            <i class="bi bi-x tw:text-xl"></i>
                        </button>
                        <i class="bi bi-caret-down-fill tw:text-gray-500 tw:transition-transform tw:duration-300 tw:text-2xl" :class="{ 'tw:rotate-180': isOpen }"></i>
                    </div>
                </div>
            </template>
        </div>

        <Transition name="dropdown">
        <div v-show="isOpen" class="tw:p-4 tw:border-t tw:dark:border-gray-700 tw:bg-transparent tw:dark:bg-transparent">
            <div v-if="disabled" class="tw:text-amber-600 tw:dark:text-amber-400 tw:text-sm tw:font-medium tw:p-4 tw:bg-amber-50/50 tw:dark:bg-amber-900/20 tw:rounded">
                 {{ warningMsg || 'Opción no disponible' }}
            </div>
            <div v-else>
                <!-- Search bar -->
                <div class="tw:mb-4">
                     <input v-model="searchQuery" @input="onSearchInput" type="text" :id="`search-${type.key}`" :name="`search_${type.key}`" placeholder="Buscar por nombre" class="tw:w-full border-bottom tw:border-gray-500 tw:border-t-0 tw:border-l-0 tw:border-r-0 tw:focus:ring-0 tw:focus:outline-none tw:focus:border-gray-500 tw:bg-transparent">
                </div>
                
                <div v-if="components.length === 0" class="tw:text-center tw:py-4 tw:text-gray-500">
                     No se ha encontrado ningún componente.
                </div>
                
                <div v-else class="tw:space-y-2">
                     <div
                         v-for="item in components"
                         :key="item.id"
                         @click="selectItem(item)"
                         class="tw:p-3 mb-3 tw:bg-transparent rounded-4 rounded-bottom-right-none tw:border tw:hover:border-indigo-500 tw:cursor-pointer tw:flex tw:justify-between tw:items-center tw:transition"
                     >
                          <div>
                              <p class="tw:font-medium">{{ item.name }}</p>
                              <div class="tw:flex tw:flex-wrap tw:gap-x-3 tw:gap-y-1 tw:mt-1 tw:text-xs">
                                  <span v-for="spec in getSpecs(type.key, item)" :key="spec.label">
                                      <span class="tw:text-gray-500">{{ spec.label }}:</span>
                                      <span class="tw:text-indigo-500 tw:font-semibold tw:ml-1">{{ spec.value }}</span>
                                  </span>
                              </div>
                          </div>
                          <div class="tw:font-bold tw:text-indigo-600 tw:dark:text-indigo-400">
                              {{ item.price ? item.price + '€' : 'Sin stock' }}
                          </div>
                     </div>
                </div>
                <div v-if="hasMore" class="w-100 d-flex justify-content-center mt-2">
                    <button @click="loadMore" class="py-2 px-5 tw:bg-indigo-500 tw:hover:bg-indigo-400 rounded-3 rounded-bottom-right-none text-white">
                        Ver más
                    </button>
                </div>
            </div>
        </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue';
import axios from 'axios';

const props = defineProps({
    type: Object,
    selected: Object,
    isOpen: Boolean,
    disabled: Boolean,
    warningMsg: String,
    filters: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['toggle', 'select', 'remove']);

const headerEl = ref(null);

const selectItem = (item) => {
    emit('select', item);
    nextTick(() => {
        headerEl.value?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
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
        if (item.frequency) specs.push({ label: 'Frecuencia', value: `${item.frequency}${' - ' + item.max_frequency || ' '}GHz` });
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

const searchQuery = ref('');
const components = ref([]);
const hasMore = ref(true);
const page = ref(1);

let searchTimeout = null;

const onSearchInput = () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    searchTimeout = setTimeout(() => {
        fetchComponents(false);
    }, 500);
};

const fetchComponents = async (isLoadMore = false) => {
    if (!isLoadMore) {
        components.value = [];
        page.value = 1;
    }

    try {
        const response = await axios.get(`/api/v1/components/${props.type.key}`, {
            params: {
                search: searchQuery.value,
                page: page.value,
                ...props.filters
            }
        });

        const data = response.data.data;
        const meta = response.data.meta;

        if (isLoadMore) {
            components.value = [...components.value, ...data];
        } else {
            components.value = data;
        }

        hasMore.value = meta.current_page < meta.last_page;
    } catch (error) {
        console.error('Error cargando componentes:', error);
    }
};

const loadMore = () => {
    if (hasMore.value) {
        page.value++;
        fetchComponents(true);
    }
};

watch(() => props.isOpen, (newVal) => {
    if (newVal && components.value.length === 0) {
        fetchComponents();
    }
});

watch(() => JSON.stringify(props.filters), (newVal, oldVal) => {
    if (newVal !== oldVal) {
        components.value = [];
        searchQuery.value = '';
        if (props.isOpen) {
            fetchComponents();
        }
    }
});
</script>