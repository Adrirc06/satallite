<template>
    <div class="d-flex flex-column" style="min-height: calc(100vh - var(--navbar-height, 0px));">
        <Header/>
        <main class="flex-grow-1 container py-5 d-flex flex-column tw:gap-6">
            <p class="tw:text-center tw:text-6xl tw:border-b-2 tw:border-gray-500 tw:pb-2 quantico-bold">Editar mi perfil</p>
            
            <div class="flex-grow-1 d-flex flex-column justify-content-center">
                <div class="row align-items-center mt-5 mb-3">
                    <div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center gap-4 mb-4 mb-md-0">
                    <div 
                        class="tw:relative tw:cursor-pointer tw:rounded-full tw:overflow-hidden tw:w-48 tw:h-48 tw:border-4 tw:border-gray-300 profile-hover-container"
                        @click="showPhotoDialog = true"
                    >
                        <img 
                            :src="user.profile_url" 
                            alt="Foto de perfil" 
                            class="tw:w-full tw:h-full tw:object-cover profile-img"
                        >
                        <div class="tw:absolute tw:inset-0 tw:flex tw:items-center tw:justify-center tw:bg-black/50 tw:backdrop-blur-sm profile-hover-target">
                            <i class="bi bi-pencil profile-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 d-flex flex-column justify-content-center gap-4">
                    <form @submit.prevent="submitProfile" class="d-flex flex-column gap-3 w-100">
                        <div class="d-flex flex-column gap-2">
                            <label for="username" class="tw:text-lg tw:font-medium">Nombre de usuario</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="bg-body text-body tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500 focus:tw:border-transparent" 
                                :class="{'tw:border-red-500': profileErrors.name}"
                            >
                            <span v-if="profileErrors.name" class="tw:text-red-500 tw:text-sm">{{ profileErrors.name }}</span>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            <label for="email" class="tw:text-lg tw:font-medium">Correo electrónico</label>
                            <input 
                                v-model="form.email" 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="bg-body-secondary text-body-secondary tw:border tw:border-gray-300 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 tw:cursor-not-allowed focus:tw:outline-none" 
                                disabled
                            >
                        </div>
                    </form>

                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex flex-column flex-sm-row gap-3 w-100 mt-5">
                <button 
                    type="button" 
                    @click="showPasswordDialog = true"
                    class="flex-grow-1 custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:hover:bg-indigo-400"
                >
                    Modificar contraseña
                </button>
                
                <button 
                    type="button" 
                    @click="submitProfile"
                    class="flex-grow-1 custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:hover:bg-indigo-400 disabled:tw:opacity-50 disabled:tw:cursor-not-allowed"
                    :disabled="!form.isDirty || form.processing"
                >
                    {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
                </button>
                
                <button 
                    type="button" 
                    @click="showDeleteDialog = true"
                    class="flex-grow-1 custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-red-600 tw:hover:bg-red-500"
                >
                    Eliminar cuenta
                </button>
            </div>
            </div>
        </main>
        <Footer/>

        <div v-if="showPhotoDialog" class="tw:fixed tw:inset-0 tw:bg-black/50 tw:backdrop-blur-sm tw:flex tw:items-center tw:justify-center tw:z-50 tw:transition-all tw:p-4" @click.self="closePhotoDialog">
            <div class="bg-body text-body tw:border tw:border-gray-500 tw:p-6 rounded-5 rounded-bottom-right-none tw:shadow-xl tw:max-w-md tw:w-full">
                <div class="tw:flex tw:justify-between tw:items-center tw:mb-4">
                    <h3 class="tw:text-xl tw:font-bold">Modificar foto</h3>
                    <button @click="closePhotoDialog" class="tw:text-gray-500 hover:tw:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw:h-6 tw:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div v-if="!imageSrc" class="tw:flex tw:flex-col tw:gap-4">
                    <div class="tw:border-2 tw:border-dashed tw:border-gray-500 tw:rounded-lg tw:p-8 tw:text-center tw:cursor-pointer hover:tw:bg-gray-800/10 tw:transition-colors" @click="triggerFileInput">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw:h-12 tw:w-12 tw:mx-auto tw:text-gray-400 tw:mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <p class="tw:text-sm tw:font-medium">Haz clic para seleccionar una imagen</p>
                        <p class="tw:text-xs text-body-secondary tw:mt-1">Máx. 2MB (JPG, PNG, WEBP)</p>

                        <p v-if="photoError" class="tw:text-red-500 tw:text-sm tw:text-center tw:mt-4">{{ photoError }}</p>

                        <input ref="fileInputRef" type="file" class="tw:hidden" accept="image/png, image/jpeg, image/webp" @change="onImageSelected" />
                    </div>
                    
                    <button 
                        v-if="user.public_profile_url"
                        @click="deleteImageProfile"
                        type="button"
                        class="custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-red-500 tw:hover:bg-red-400 tw:w-full tw:flex tw:justify-center tw:items-center tw:gap-2 tw:py-3"
                        :disabled="isDeletingPhoto"
                    >
                        <span class="tw:font-medium">{{ isDeletingPhoto ? 'Eliminando...' : 'Eliminar foto' }}</span>
                    </button>
                </div>
                
                <div v-else class="tw:flex tw:flex-col tw:gap-4">
                    <div class="tw:h-64 sm:tw:h-80 tw:w-full tw:bg-black tw:rounded-lg tw:overflow-hidden tw:relative">
                        <Cropper
                            ref="cropperRef"
                            :src="imageSrc"
                            :stencil-component="CircleStencil"
                            :stencil-props="{ aspectRatio: 1, movable: false, resizable: false }"
                            :resize-image="{ adjustStencil: false }"
                            :wheel-resize="false"
                            :default-size="getStencilSize"
                            :canvas="{ width: 400, height: 400 }"
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
                            {{ isUploadingPhoto ? 'Recortando...' : 'Recortar y Subir' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showDeleteDialog" class="tw:fixed tw:inset-0 tw:bg-black/50 tw:backdrop-blur-sm tw:flex tw:items-center tw:justify-center tw:z-50 tw:transition-all" @click.self="closeDeleteDialog">
            <div class="bg-body text-body tw:border tw:border-gray-500 tw:p-6 rounded-5 rounded-bottom-right-none tw:shadow-xl tw:max-w-md tw:w-full">
                <h3 class="tw:text-2xl tw:font-bold tw:mb-4 quantico-bold tw:text-red-500">Eliminar cuenta</h3>
                <p class="tw:mb-6">¿Estás seguro de que deseas eliminar tu cuenta permanentemente? Esta acción no se puede deshacer y perderás todos tus datos.</p>
                <div class="tw:flex tw:justify-end tw:gap-3">
                    <button type="button" @click="closeDeleteDialog" class="tw:px-4 tw:py-2 tw:bg-gray-300 tw:hover:bg-gray-400 tw:text-black rounded-3 rounded-bottom-right-none">Cancelar</button>
                    <button type="button" @click="deleteAccount" :disabled="deleteForm.processing" class="tw:px-4 tw:py-2 tw:bg-red-600 tw:hover:bg-red-500 tw:text-white rounded-3 rounded-bottom-right-none disabled:tw:opacity-50">
                        {{ deleteForm.processing ? 'Eliminando...' : 'Eliminar' }}
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showPasswordDialog" class="tw:fixed tw:inset-0 tw:bg-black/50 tw:backdrop-blur-sm tw:flex tw:items-center tw:justify-center tw:z-50 tw:transition-all" @click.self="closePasswordDialog">
            <div class="bg-body text-body tw:border tw:border-gray-500 tw:p-6 rounded-5 rounded-bottom-right-none tw:shadow-xl tw:max-w-md tw:w-full">
                <h3 class="tw:text-2xl tw:font-bold tw:mb-4 quantico-bold">Cambiar contraseña</h3>
                
                <form @submit.prevent="submitPassword" class="tw:flex tw:flex-col tw:gap-4">
                    <div class="tw:flex tw:flex-col tw:gap-2">
                        <label for="current_password" class="tw:font-medium">Contraseña actual</label>
                        <input 
                            v-model="passwordForm.current_password" 
                            type="password" 
                            id="current_password" 
                            class="bg-body text-body tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500"
                            :class="{'tw:border-red-500': passwordErrors.current_password}"
                            required
                        >
                        <span v-if="passwordErrors.current_password" class="tw:text-red-500 tw:text-sm">{{ passwordErrors.current_password }}</span>
                    </div>

                    <div class="tw:flex tw:flex-col tw:gap-2">
                        <label for="password" class="tw:font-medium">Nueva contraseña</label>
                        <input 
                            v-model="passwordForm.password" 
                            type="password" 
                            id="password" 
                            class="bg-body text-body tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500"
                            :class="{'tw:border-red-500': passwordErrors.password}"
                            required
                        >
                        <span v-if="passwordErrors.password" class="tw:text-red-500 tw:text-sm">{{ passwordErrors.password }}</span>
                    </div>

                    <div class="tw:flex tw:flex-col tw:gap-2">
                        <label for="password_confirmation" class="tw:font-medium">Confirmar contraseña</label>
                        <input 
                            v-model="passwordForm.password_confirmation" 
                            type="password" 
                            id="password_confirmation" 
                            class="bg-body text-body tw:border tw:border-gray-500 tw:rounded-md rounded-bottom-right-none tw:px-3 tw:py-2 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-indigo-500"
                            :class="{'tw:border-red-500': passwordErrors.password_confirmation}"
                            required
                        >
                        <span v-if="passwordErrors.password_confirmation" class="tw:text-red-500 tw:text-sm">{{ passwordErrors.password_confirmation }}</span>
                    </div>

                    <div class="tw:flex tw:justify-end tw:gap-3 tw:mt-4">
                        <button type="button" @click="closePasswordDialog" class="tw:px-4 tw:py-2 custom-btn rounded-2 tw:border tw:border-gray-500 hover:tw:bg-gray-500 hover:tw:text-white">Cancelar</button>
                        <button 
                            type="submit" 
                            class="custom-btn rounded-2 tw:!text-white rounded-bottom-right-none tw:bg-indigo-500 tw:hover:bg-indigo-400"
                            :disabled="passwordForm.processing"
                        >
                            {{ passwordForm.processing ? 'Guardando...' : 'Cambiar contraseña' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import Footer from '@/Layouts/Footer.vue';
import Header from '@/Layouts/Header.vue';
import { Cropper, CircleStencil } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'Usuario', email: 'correo@ejemplo.com', profile_photo_url: null });

// Formulario de perfil
const form = useForm({
    name: user.value.name,
    email: user.value.email,
});

const profileErrors = ref({});

const validateProfile = () => {
    profileErrors.value = {};
    let isValid = true;

    if (!form.name.trim()) {
        profileErrors.value.name = 'El nombre de usuario es obligatorio.';
        isValid = false;
    } else if (form.name.length > 20) {
        profileErrors.value.name = 'El nombre de usuario no puede exceder los 20 caracteres.';
        isValid = false;
    } else if (!/^[a-zA-Z0-9_-]+$/.test(form.name)) {
        profileErrors.value.name = 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.';
        isValid = false;
    } else if (form.name === user.value.name) {
        profileErrors.value.name = 'El nombre de usuario no puede ser el mismo que tu nombre actual.';
        isValid = false;
    }

    return isValid;
};

const submitProfile = () => {
    if (!validateProfile()) return;
    
    form.patch('/profile', {
        preserveScroll: true,
        onSuccess: () => {
            form.defaults({ name: form.name });
        },
        onError: (errors) => {
            profileErrors.value = errors;
        }
    });
};

// Diálogos y estado
const showPhotoDialog = ref(false);

const fileInputRef = ref(null);
const cropperRef = ref(null);
const imageSrc = ref(null);
const isUploadingPhoto = ref(false);
const isDeletingPhoto = ref(false);
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
    
    if (file.size > 2 * 1024 * 1024) { // 2MB
        photoError.value = 'La imagen pesa más de 2MB.';
        return;
    }

    photoError.value = '';
    imageSrc.value = URL.createObjectURL(file);
};

const onCropperChange = ({ coordinates, image }) => {
    // Evento de cropper si es necesario
};

const getStencilSize = ({ imageSize }) => {
    
    // Tamaños por defecto por si acaso
    if (!imageSize || !imageSize.width || !imageSize.height) {
        return { width: 300, height: 300 };
    }

    const size = Math.min(imageSize.width, imageSize.height);
    
    return { 
        width: size,
        height: size
    };
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

        const file = new File([blob], 'profile.webp', { type: 'image/webp' });
        const formData = new FormData();
        formData.append('image', file);

        router.post('/profile/image', formData, {
            preserveState: true,
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                closePhotoDialog();
            },
            onError: (errors) => {
                photoError.value = errors.image || 'Error al subir la imagen';
            },
            onFinish: () => {
                isUploadingPhoto.value = false;
            }
        });
    }, 'image/webp', 0.75);
};

const deleteImageProfile = () => {
    isDeletingPhoto.value = true;
    router.delete('/profile/image', {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closePhotoDialog();
        },
        onError: () => {
            photoError.value = 'Error al eliminar la imagen';
        },
        onFinish: () => {
            isDeletingPhoto.value = false;
        }
    });
};

const showPasswordDialog = ref(false);const showDeleteDialog = ref(false);

const deleteForm = useForm({});

const deleteAccount = () => {
    deleteForm.delete('/profile', {
        preserveScroll: true,
        onSuccess: () => closeDeleteDialog(),
    });
};

const closeDeleteDialog = () => {
    showDeleteDialog.value = false;
};
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const passwordErrors = ref({});

const validatePassword = () => {
    passwordErrors.value = {};
    let isValid = true;

    if (!passwordForm.current_password) {
        passwordErrors.value.current_password = 'Debes introducir tu contraseña actual.';
        isValid = false;
    }

    if (!passwordForm.password) {
        passwordErrors.value.password = 'Debes introducir una nueva contraseña.';
        isValid = false;
    } else if (passwordForm.password.length < 8) {
        passwordErrors.value.password = 'La nueva contraseña debe tener al menos 8 caracteres.';
        isValid = false;
    } else if (passwordForm.password === passwordForm.current_password) {
        passwordErrors.value.password = 'La nueva contraseña no puede ser igual a tu contraseña actual.';
        isValid = false;
    }

    if (passwordForm.password !== passwordForm.password_confirmation) {
        passwordErrors.value.password_confirmation = 'Las nuevas contraseñas no coinciden.';
        isValid = false;
    }

    return isValid;
};

const submitPassword = () => {
    if (!validatePassword()) return;

    passwordForm.put('/profile/password', {
        preserveScroll: true,
        onError: (errors) => {
            passwordErrors.value = errors;
        },
        onSuccess: () => {
            closePasswordDialog();
        },
    });
};

const closePasswordDialog = () => {
    showPasswordDialog.value = false;
    passwordForm.reset();
    passwordErrors.value = {};
};
</script>
