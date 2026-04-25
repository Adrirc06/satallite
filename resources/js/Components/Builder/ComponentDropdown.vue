<template>
    <div class="tw:bg-transparent tw:rounded-2xl tw:rounded-br-none rounded-bottom-right-none tw:shadow-sm tw:mb-3 p-3 tw:overflow-hidden tw:border tw:border-gray-300 tw:dark:border-gray-700 tw:relative">
        <div 
            @click="$emit('toggle')"
            class="tw:flex tw:items-center tw:p-4 tw:cursor-pointer tw:transition"
            :class="{'tw:opacity-50 tw:cursor-not-allowed': disabled}"
        >
            <div class="tw:text-indigo-500 tw:flex tw:items-center tw:justify-center tw:text-3xl tw:mr-4">
                 <i :class="`bi bi-${type.icon}`"></i>
            </div>
            
            <div class="tw:flex-1 tw:flex tw:flex-col tw:justify-center">
                 <template v-if="!selected">
                     <p class="h3 mb-0">{{ type.name }}</p>
                 </template>
                 <template v-else>
                     <p class="tw:text-sm tw:font-medium tw:text-gray-500 tw:dark:text-gray-400 tw:uppercase tw:tracking-wide">{{ type.name }}</p>
                     <p class="tw:text-lg tw:font-bold tw:text-gray-900 tw:dark:text-white tw:mt-1">{{ selected.name }}</p>
                     
                     <div class="tw:flex tw:gap-4 tw:mt-2 tw:text-sm tw:text-gray-600 tw:dark:text-gray-400">
                         <span v-if="type.key === 'motherboard'">Socket: {{ selected.socket?.name }}<template v-if="selected.max_memory"> | Memoria máxima: {{ selected.max_memory }}GB</template><template v-if="selected.form_factor"> | Factor de forma: {{ selected.form_factor.name }}</template><template v-if="selected.ram_type"> | Tipo de RAM: {{ selected.ram_type.name }}</template></span>
                         <span v-else-if="type.key === 'cpu'">Socket: {{ selected.socket?.name }}<template v-if="selected.cores"> | {{ selected.cores }} Núcleos / {{ selected.cores * 2 }} Hilos</template><template v-if="selected.frequency"> | {{ selected.frequency }}GHz - {{ selected.max_frequency || selected.frequency }}GHz</template><template v-if="selected.tdp"> | TDP: {{ selected.tdp }}W</template><template v-if="selected.igpu"> | Gráficos Integrados: {{ selected.igpu.name || 'No' }}</template><template v-else> | Gráficos Integrados: No</template></span>
                         <span v-else-if="type.key === 'ram'">{{ selected.size * selected.modules }}GB ({{ selected.size }}GB x {{ selected.modules }})<template v-if="selected.ram_type"> | {{ selected.ram_type.name }}</template><template v-if="selected.cas_latency"> | Latencia: CL{{ selected.cas_latency }}</template><template v-if="selected.speed"> | Velocidad: {{ selected.speed }}MHz</template></span>
                         <span v-else-if="type.key === 'drive'"><template v-if="selected.size >= 1000">{{ selected.size / 1000 }}TB</template><template v-else>{{ selected.size }}GB</template><template v-if="selected.drive_type"> | <template v-if="selected.drive_type.id != 1">HDD, {{ selected.drive_type.name }} RPM</template><template v-else>{{ selected.drive_type.name }}</template></template></span>
                         <span v-else-if="type.key === 'gpu'">{{ selected.vram }}GB VRAM<template v-if="selected.tdp"> | {{ selected.tdp }}W</template></span>
                         <span v-else-if="type.key === 'psu'">Tipo: {{ selected.psu_type?.name }}<template v-if="selected.power"> | Potencia: {{ selected.power }}W</template><template v-if="selected.modularity"> | Modularidad: {{ selected.modularity.name }}</template><template v-if="selected.efficiency"> | Eficiencia: {{ selected.efficiency.name }}</template></span>
                         <span v-else-if="type.key === 'chassis'">{{ selected.chassis_type?.name }}</span>
                     </div>
                 </template>
            </div>
            
            <div class="tw:flex tw:items-center tw:gap-6 tw:ml-4">
                <span v-if="selected" class="tw:font-bold tw:text-lg">
                    {{ selected.price ? selected.price + '€' : 'Sin stock' }}
                </span>
                <button 
                    v-if="selected" 
                    @click.stop="$emit('remove')" 
                    title="Eliminar componente"
                >
                    <i class="bi bi-x tw:text-xl"></i>
                </button>
                <i class="bi bi-caret-down-fill tw:text-gray-500 tw:transition-transform tw:duration-300 tw:text-2xl" :class="{ 'tw:rotate-180': isOpen }"></i>
            </div>
        </div>

        <div v-show="isOpen" class="tw:p-4 tw:border-t tw:dark:border-gray-700 tw:bg-transparent tw:dark:bg-transparent">
            <div v-if="disabled" class="tw:text-amber-600 tw:dark:text-amber-400 tw:text-sm tw:font-medium tw:p-4 tw:bg-amber-50/50 tw:dark:bg-amber-900/20 tw:rounded">
                 {{ warningMsg || 'Opción no disponible' }}
            </div>
            <div v-else>
                <!-- Search bar -->
                <div class="tw:mb-4">
                     <input v-model="searchQuery" @input="onSearchInput" type="text" placeholder="Buscar por nombre" class="tw:w-full border-bottom tw:border-gray-500 tw:border-t-0 tw:border-l-0 tw:border-r-0 tw:focus:ring-0 tw:focus:outline-none tw:focus:border-gray-500 tw:bg-transparent tw:dark:text-white">
                </div>
                
                <div v-if="components.length === 0" class="tw:text-center tw:py-4 tw:text-gray-500">
                     No se ha encontrado ningún componente.
                </div>
                
                <div v-else class="tw:space-y-2">
                     <div 
                         v-for="item in components" 
                         :key="item.id" 
                         @click="$emit('select', item)"
                         class="tw:p-3 mb-3 tw:bg-transparent rounded-4 rounded-bottom-right-none tw:border tw:hover:border-indigo-500 tw:cursor-pointer tw:flex tw:justify-between tw:items-center tw:transition"
                     >
                          <div>
                              <p class="tw:font-medium">{{ item.name }}</p>
                              <p class="tw:text-xs tw:text-gray-500 tw:mt-1">
                                  <span v-if="type.key === 'motherboard'">Socket: {{ item.socket?.name }}<template v-if="item.max_memory"> | Memoria máxima: {{ item.max_memory }}GB</template><template v-if="item.form_factor"> | Factor de forma: {{ item.form_factor.name }}</template><template v-if="item.ram_type"> | Tipo de RAM: {{ item.ram_type.name }}</template></span>
                                  <span v-else-if="type.key === 'cpu'">Socket: {{ item.socket?.name }}<template v-if="item.cores"> | {{ item.cores }} Núcleos / {{ item.cores * 2 }} Hilos</template><template v-if="item.frequency"> | {{ item.frequency }}GHz - {{ item.max_frequency || item.frequency }}GHz</template><template v-if="item.tdp"> | TDP: {{ item.tdp }}W</template><template v-if="item.igpu"> | Gráficos Integrados: {{ item.igpu.name || 'No' }}</template><template v-else> | Gráficos Integrados: No</template></span>
                                  <span v-else-if="type.key === 'ram'">{{ item.size * item.modules }}GB ({{ item.size }}GB x {{ item.modules }})<template v-if="item.ram_type"> | {{ item.ram_type.name }}</template><template v-if="item.cas_latency"> | Latencia: CL{{ item.cas_latency }}</template><template v-if="item.speed"> | Velocidad: {{ item.speed }}MHz</template></span>
                                  <span v-else-if="type.key === 'drive'"><template v-if="item.size >= 1000">{{ item.size / 1000 }}TB</template><template v-else>{{ item.size }}GB</template><template v-if="item.drive_type"> | <template v-if="item.drive_type.id != 1">HDD, {{ item.drive_type.name }} RPM</template><template v-else>{{ item.drive_type.name }}</template></template></span>
                                  <span v-else-if="type.key === 'gpu'">{{ item.vram }}GB VRAM<template v-if="item.tdp"> | {{ item.tdp }}W</template></span>
                                  <span v-else-if="type.key === 'psu'">Tipo: {{ item.psu_type?.name }}<template v-if="item.power"> | Potencia: {{ item.power }}W</template><template v-if="item.modularity"> | Modularidad: {{ item.modularity.name }}</template><template v-if="item.efficiency"> | Eficiencia: {{ item.efficiency.name }}</template></span>
                                  <span v-else-if="type.key === 'chassis'">{{ item.chassis_type?.name }}</span>
                              </p>
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
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    type: Object,
    selected: Object,
    isOpen: Boolean,
    disabled: Boolean,
    warningMsg: String
});

const emit = defineEmits(['toggle', 'select', 'remove']);

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
                page: page.value
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
</script>