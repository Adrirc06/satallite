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
                         <span v-if="type.key === 'motherboard'">{{ selected.socket?.name }} | {{ selected.ram_type?.name }} | {{ selected.form_factor?.name }}</span>
                         <span v-if="type.key === 'cpu'">{{ selected.socket?.name }} | {{ selected.tdp }}W</span>
                         <span v-if="type.key === 'ram'">{{ selected.ram_type?.name }} | {{ selected.size }}GB | {{ selected.speed }}MHz | {{ selected.modules }}</span>
                         <span v-if="type.key === 'drive'">{{ selected.drive_type?.name }} | {{ selected.size }}GB <span v-if="selected.rpm">| {{ selected.rpm }}RPM</span></span>
                         <span v-if="type.key === 'gpu'">{{ selected.tdp }}W | {{ selected.vram }}GB</span>
                         <span v-if="type.key === 'psu'">{{ selected.psu_type?.name }} | {{ selected.power }}W</span>
                         <span v-if="type.key === 'chassis'">{{ selected.chassis_type?.name }}</span>
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
                     <input v-model="searchQuery" @input="fetchComponents" type="text" placeholder="Buscar por nombre" class="tw:w-full border-bottom tw:border-gray-500 tw:border-t-0 tw:border-l-0 tw:border-r-0 tw:focus:ring-0 tw:focus:outline-none tw:focus:border-gray-500 tw:bg-transparent tw:dark:text-white">
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
                                  <span v-if="type.key === 'motherboard'">{{ item.socket?.name }} | {{ item.ram_type?.name }}</span>
                                  <span v-if="type.key === 'cpu'">Socket: {{ item.socket?.name }} | TDP: {{ item.tdp }}W</span>
                                  <span v-if="type.key === 'ram'">{{ item.ram_type?.name }} | {{ item.size }}GB | {{ item.speed }}MHz</span>
                                  <span v-if="type.key === 'drive'">{{ item.drive_type?.name }} | {{ item.size }}GB <span v-if="item.rpm">| {{ item.rpm }}RPM</span></span>
                                  <span v-if="type.key === 'gpu'">TDP: {{ item.tdp }}W | VRAM: {{ item.vram }}GB</span>
                                  <span v-if="type.key === 'psu'">{{ item.psu_type?.name }} | {{ item.power }}W</span>
                                  <span v-if="type.key === 'chassis'">{{ item.chassis_type?.name }}</span>
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

const props = defineProps({
    type: Object,
    selected: Object,
    isOpen: Boolean,
    disabled: Boolean,
    warningMsg: String
});

const emit = defineEmits(['toggle', 'select', 'remove']);

const searchQuery = ref('');
const components = ref([
    // Mock implementation for UI demonstration
    { id: 1, name: 'Ejemplo Componente 1', price: 120.50, tdp: 65, size: 16, speed: 3200 },
    { id: 2, name: 'Ejemplo Componente 2', price: null, tdp: 105, size: 32, speed: 3600 }
]);
const hasMore = ref(true);

const fetchComponents = () => {
    // API call replacement via Axios/Axios-mock
    // The actual parameters would filter by dependencies and pagination (15 items)
};

const loadMore = () => {
    // Fetch 15 more and push to components array
};

watch(() => props.isOpen, (newVal) => {
    if (newVal && components.value.length === 0) {
        fetchComponents();
    }
});
</script>