<template>
    <section class="container-fluid py-3" id="builder">
        <p
          class="tw:text-6xl quantico-bold text-body text-center py-2 border-bottom container"
        >
          Builds de la comunidad
        </p>
        <div
          id="buildCarousel"
          class="carousel slide container"
          data-bs-ride="carousel"
        >
          <div class="carousel-inner">
            <template v-if="builds && builds.length > 0">
                <BuildSlide 
                    v-for="(build, index) in builds" 
                    :key="build.id" 
                    :build="build" 
                    :is-active="index === 0"
                />
            </template>
            <div v-else class="carousel-item active">
                <div class="w-100 pb-1 d-block d-flex justify-content-center align-items-center flex-column" style="min-height: 300px;">
                    <div class="spinner-border text-indigo-500" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
          <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#buildCarousel"
            data-bs-slide="prev"
          >
            <span
              class="tw:invert carousel-control-prev-icon"
              aria-hidden="true"
            ></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#buildCarousel"
            data-bs-slide="next"
          >
            <span
              class="tw:invert carousel-control-next-icon"
              aria-hidden="true"
            ></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
        <div class="d-flex justify-content-center my-3">
            <Link href="/builds" class="btn tw:!bg-indigo-500 tw:hover:!bg-indigo-400 tw:!text-white rounded-3 rounded-bottom-right-none px-4">
                Ver todas las builds
            </Link>
        </div>
      </section>
</template>
<script setup>
import BuildSlide from '@/Components/BuildSlide.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const builds = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/api/v1/builds/random');
        builds.value = response.data.data;
    } catch (error) {
        console.error("Error loading random builds:", error);
    }
});
</script>