<template>
    <div class="d-flex flex-column" style="min-height: calc(100vh - var(--navbar-height, 0px));">
        <Header/>
        <main class="flex-grow-1 container py-5 d-flex flex-column tw:gap-6">
            <p class="tw:text-center tw:text-6xl tw:border-b-2 tw:border-gray-500 tw:pb-2 quantico-bold">Crear nuevo artículo</p>
            <form @submit.prevent="submitArticle" class="tw:flex tw:flex-col tw:gap-4 tw:grow">
                <div class="tw:flex tw:flex-col tw:gap-2">
                    <label for="title" class="tw:text-lg tw:font-medium">Título del artículo</label>
                    <input v-model="form.title" type="text" id="title" name="title" class="tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500 focus:tw:border-transparent" required>
                </div>
                <div class="tw:flex tw:flex-col tw:gap-2">
                    <label for="subtitle" class="tw:text-lg tw:font-medium">Subtítulo del artículo</label>
                    <input v-model="form.subtitle" type="text" id="subtitle" name="subtitle" class="tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500 focus:tw:border-transparent" required>
                </div>
                <div class="tw:flex tw:flex-col tw:gap-2">
                    <label class="tw:text-lg tw:font-medium">Imagen del banner (Opcional)</label>
                    <div 
                        class="tw:relative tw:cursor-pointer tw:rounded-lg tw:overflow-hidden tw:w-full tw:h-64 tw:border-4 tw:border-dashed tw:border-gray-500 hover:tw:border-indigo-500 tw:transition-colors tw:group tw:flex tw:items-center tw:justify-center tw:bg-gray-800/10 banner-hover-container"
                        @click="showPhotoDialog = true"
                    >
                        <template v-if="form.banner_url && form.banner_url !== '/img/banners/banner1.jpg'">
                            <img 
                                :src="form.banner_url" 
                                alt="Banner del artículo" 
                                class="tw:w-full tw:h-full tw:object-cover banner-img"
                            >
                            <div class="tw:absolute tw:inset-0 tw:flex tw:items-center tw:justify-center tw:opacity-0 group-hover:tw:opacity-100 tw:transition-opacity tw:bg-black/50 tw:backdrop-blur-sm banner-hover-target">
                                <i class="bi bi-pencil profile-icon tw:text-white" style="font-size: 2.5rem;"></i>
                            </div>
                        </template>
                        <template v-else>
                           <div class="tw:flex tw:flex-col tw:items-center tw:text-gray-500">
                               <i class="bi bi-image" style="font-size: 3rem;"></i>
                               <span class="tw:mt-2 tw:font-medium">Seleccionar banner</span>
                           </div>
                        </template>
                    </div>
                </div>
                <div class="tw:flex tw:flex-col tw:gap-2 tw:grow">
                    <label for="content" class="tw:text-lg tw:font-medium">Contenido del artículo</label>
                    <textarea v-model="form.content" id="content" name="content" class="tw:min-h-50 tw:grow tw:resize-none tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500 focus:tw:border-transparent" required></textarea>
                </div>
                <div class="tw:flex tw:items-center tw:gap-4 tw:self-start">
                    <button type="submit" class="custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:hover:bg-indigo-400" :disabled="loading">
                        {{ loading ? 'Publicando...' : 'Publicar artículo' }}
                    </button>
                    <span v-if="errorMsg" class="tw:text-red-500 tw:text-sm">{{ errorMsg }}</span>
                </div>
            </form>
        </main>
        <Footer/>

        <div v-if="showPhotoDialog" class="tw:fixed tw:inset-0 tw:bg-black/50 tw:backdrop-blur-sm tw:flex tw:items-center tw:justify-center tw:z-50 tw:transition-all tw:p-4" @click.self="closePhotoDialog">
            <div class="bg-body text-body tw:border tw:border-gray-500 tw:p-6 rounded-5 rounded-bottom-right-none tw:shadow-xl tw:max-w-md tw:w-full">
                <div class="tw:flex tw:justify-between tw:items-center tw:mb-4">
                    <h3 class="tw:text-xl tw:font-bold">Subir Banner</h3>
                    <button @click="closePhotoDialog" class="tw:text-gray-500 hover:tw:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw:h-6 tw:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div v-if="!imageSrc" class="tw:border-2 tw:border-dashed tw:border-gray-500 tw:rounded-lg tw:p-8 tw:text-center tw:cursor-pointer hover:tw:bg-gray-800/10 tw:transition-colors" @click="triggerFileInput">
                    <svg xmlns="http://www.w3.org/2000/svg" class="tw:h-12 tw:w-12 tw:mx-auto tw:text-gray-400 tw:mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <p class="tw:text-sm tw:font-medium">Haz clic para seleccionar una imagen</p>
                    <p class="tw:text-xs text-body-secondary tw:mt-1">Máx. 5MB (JPG, PNG, WEBP)</p>

                    <p v-if="photoError" class="tw:text-red-500 tw:text-sm tw:text-center tw:mt-4">{{ photoError }}</p>

                    <input ref="fileInputRef" type="file" class="tw:hidden" accept="image/png, image/jpeg, image/webp" @change="onImageSelected" />
                </div>
                
                <div v-else class="tw:flex tw:flex-col tw:gap-4">
                    <div class="tw:h-64 sm:tw:h-80 tw:w-full tw:bg-black tw:rounded-lg tw:overflow-hidden tw:relative">
                        <Cropper
                            ref="cropperRef"
                            :src="imageSrc"
                            :stencil-props="{ aspectRatio: 1, movable: false, resizable: false }"
                            :resize-image="{ adjustStencil: false }"
                            :wheel-resize="false"
                            :default-size="getStencilSize"
                            :canvas="{ width: 1080, height: 1080 }"
                            class="tw:h-full tw:w-full"
                            image-restriction="stencil"
                            @change="onCropperChange"
                        />
                    </div>
                    
                    <div class="tw:flex tw:items-center tw:justify-center">
                        <div class="tw:flex tw:items-center tw:border tw:border-gray-500 rounded-3 rounded-bottom-right-none tw:overflow-hidden">
                            <button type="button" @click.prevent="zoomOut" class="tw:w-10 tw:h-10 tw:flex tw:items-center tw:justify-center hover:tw:bg-gray-500 hover:tw:text-white tw:transition-colors tw:text-lg tw:font-bold">
                                -
                            </button>
                            <span class="tw:flex-1 tw:text-sm tw:font-medium tw:px-4 tw:text-center">Zoom</span>
                            <button type="button" @click.prevent="zoomIn" class="tw:w-10 tw:h-10 tw:flex tw:items-center tw:justify-center hover:tw:bg-gray-500 hover:tw:text-white tw:transition-colors tw:text-lg tw:font-bold">
                                +
                            </button>
                        </div>
                    </div>

                    <p v-if="photoError" class="tw:text-red-500 tw:text-sm tw:text-center">{{ photoError }}</p>

                    <div class="tw:flex tw:justify-end tw:gap-3 tw:mt-2">
                        <button @click="resetImage" class="tw:px-4 tw:py-2 custom-btn rounded-2 tw:border tw:border-gray-500 hover:tw:bg-gray-500 hover:tw:text-white">Cancelar</button>
                        <button @click="uploadCroppedImage" :disabled="isUploadingPhoto" class="tw:px-4 tw:py-2 custom-btn rounded-2 tw:bg-indigo-500 tw:text-white hover:tw:bg-indigo-400 disabled:tw:opacity-50">
                            {{ isUploadingPhoto ? 'Aplicando...' : 'Recortar y Aplicar' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.banner-hover-container .banner-img {
    transition: opacity 0.3s ease;
}
.banner-hover-container .banner-hover-target {
    transition: opacity 0.3s ease;
    pointer-events: none;
}
</style>
<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

const form = ref({
    title: '',
    subtitle: '',
    banner_url: '/img/banners/banner1.jpg',
    content: '',
});

const bannerBlob = ref(null);

const loading = ref(false);
const errorMsg = ref('');

const showPhotoDialog = ref(false);
const fileInputRef = ref(null);
const cropperRef = ref(null);
const imageSrc = ref(null);
const isUploadingPhoto = ref(false);
const photoError = ref('');

const triggerFileInput = () => {
    fileInputRef.value?.click();
};

const onImageSelected = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        photoError.value = 'El archivo seleccionado no es una imagen válida.';
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) { // 5MB
        photoError.value = 'La imagen pesa más de 5MB.';
        return;
    }

    photoError.value = '';
    imageSrc.value = URL.createObjectURL(file);
};

const getStencilSize = ({ imageSize }) => {
    if (!imageSize || !imageSize.width || !imageSize.height) {
        return { width: 300, height: 300 };
    }
    const size = Math.min(imageSize.width, imageSize.height);
    return { width: size, height: size };
};

const zoomIn = () => {
    if (cropperRef.value) {
        cropperRef.value.zoom(1.1);
    }
};

const zoomOut = () => {
    if (cropperRef.value) {
        cropperRef.value.zoom(0.9);
    }
};

const onCropperChange = () => {
    // Empty for now, handles event if needed
};

const resetImage = () => {
    imageSrc.value = null;
    if (fileInputRef.value) fileInputRef.value.value = '';
    photoError.value = '';
    isUploadingPhoto.value = false;
};

const closePhotoDialog = () => {
    showPhotoDialog.value = false;
    resetImage();
};

const uploadCroppedImage = () => {
    if (!cropperRef.value) return;

    const { canvas } = cropperRef.value.getResult();
    if (!canvas) return;

    isUploadingPhoto.value = true;
    photoError.value = '';

    canvas.toBlob((blob) => {
        if (!blob) {
            photoError.value = 'No se pudo procesar la imagen.';
            isUploadingPhoto.value = false;
            return;
        }
        
        bannerBlob.value = blob;
        form.value.banner_url = URL.createObjectURL(blob);
        
        closePhotoDialog();
        isUploadingPhoto.value = false;
    }, 'image/jpeg', 0.95);
};

const submitArticle = async () => {
    loading.value = true;
    errorMsg.value = '';
    
    const getCookie = (name) => {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
        return '';
    }
    
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('subtitle', form.value.subtitle);
    formData.append('content', form.value.content);
    
    if (bannerBlob.value) {
        formData.append('banner', bannerBlob.value, 'banner.jpg');
    } else if (form.value.banner_url && !form.value.banner_url.startsWith('blob:')) {
        formData.append('banner_url', form.value.banner_url);
    }
    
    try {
        const response = await axios.post('/api/v1/articles', formData, {
            withCredentials: true,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'multipart/form-data',
                'X-XSRF-TOKEN': getCookie('XSRF-TOKEN')
            }
        });
        
        if (response.data && response.data.data) {
            router.visit(`/article/${response.data.data.id}`);
        } else {
            router.visit('/articles');
        }
    } catch (error) {
        if (error.response?.data?.message) {
            errorMsg.value = error.response.data.message;
        } else {
            errorMsg.value = 'Ha ocurrido un error al publicar el artículo.';
        }
    } finally {
        loading.value = false;
    }
};
</script>