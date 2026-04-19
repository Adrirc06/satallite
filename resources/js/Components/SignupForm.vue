<template>
    <main class="flex-grow-1 container-fluid justify-content-center d-flex align-items-center py-5">
        <div class="rounded-5 rounded-bottom-right-none  border-2 tw:!border-gray-500 d-flex flex-column justify-content-center align-items-center p-5 text-center">
            <p class="tw:text-5xl m-3 quantico-bold">Regístrate</p>
            <form @submit.prevent="submit" class="w-100">
                <div class="mb-3 w-100 text-start">
                    <label for="username" class="form-label">Nombre de usuario</label>
                    <input 
                        type="text" 
                        class="form-control pb-2 w-100 keyboard-safe-area border-2" 
                        :class="[formErrors.username ? 'tw:!border-red-500' : 'tw:!border-gray-500']"
                        id="username" 
                        autocomplete="username"
                        v-model="form.username"
                        @focus="clearError('username')"
                        @click="clearError('username')"
                    >
                    <div v-if="formErrors.username" class="tw:text-red-500 small mt-1">{{ formErrors.username }}</div>
                </div>
                <div class="mb-3 w-100 text-start">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input 
                        type="email" 
                        class="form-control pb-2 w-100 keyboard-safe-area border-2" 
                        :class="[formErrors.email ? 'tw:!border-red-500' : 'tw:!border-gray-500']"
                        id="email" 
                        autocomplete="email"
                        v-model="form.email"
                        @focus="clearError('email')"
                        @click="clearError('email')"
                    >
                    <div v-if="formErrors.email" class="tw:text-red-500 small mt-1">{{ formErrors.email }}</div>
                </div>
                <div class="mb-3 w-100 text-start">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="position-relative">
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            class="form-control pb-2 w-100 keyboard-safe-area border-2" 
                            :class="[formErrors.password ? 'tw:!border-red-500' : 'tw:!border-gray-500']"
                            id="password" 
                            autocomplete="new-password"
                            v-model="form.password"
                            @focus="clearError('password'); passwordFocused = true"
                            @blur="passwordFocused = false"
                            @click="clearError('password')"
                        >
                        <i 
                            v-show="passwordFocused || form.password.length > 0"
                            :class="['bi', showPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill', 'position-absolute tw:cursor-pointer tw:text-gray-500 tw:hover:text-gray-700']" 
                            style="right: 15px; top: 50%; transform: translateY(-50%); font-size: 1.2rem;"
                            @mousedown.prevent
                            @click="showPassword = !showPassword"
                        ></i>
                    </div>
                    <div v-if="formErrors.password" class="tw:text-red-500 small mt-1">{{ formErrors.password }}</div>
                </div>
                <div class="mb-3 w-100 text-start">
                    <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
                    <div class="position-relative">
                        <input 
                            :type="showConfirmPassword ? 'text' : 'password'" 
                            class="form-control pb-2 w-100 keyboard-safe-area border-2" 
                            :class="[formErrors.confirmPassword ? 'tw:!border-red-500' : 'tw:!border-gray-500']"
                            id="confirmPassword" 
                            autocomplete="new-password"
                            v-model="form.confirmPassword"
                            @focus="clearError('confirmPassword'); confirmPasswordFocused = true"
                            @blur="confirmPasswordFocused = false"
                            @click="clearError('confirmPassword')"
                        >
                        <i 
                            v-show="confirmPasswordFocused || form.confirmPassword.length > 0"
                            :class="['bi', showConfirmPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill', 'position-absolute tw:cursor-pointer tw:text-gray-500 tw:hover:text-gray-700']" 
                            style="right: 15px; top: 50%; transform: translateY(-50%); font-size: 1.2rem;"
                            @mousedown.prevent
                            @click="showConfirmPassword = !showConfirmPassword"
                        ></i>
                    </div>
                    <div v-if="formErrors.confirmPassword" class="tw:text-red-500 small mt-1">{{ formErrors.confirmPassword }}</div>
                </div>
                <p>¿Ya tienes una cuenta? ¡<Link href="/login" class="text-decoration-none tw:!text-indigo-500">Inicia sesión</Link>!</p>
                <div class="w-100 d-flex justify-content-center">
                    <button type="submit" class="custom-btn w-75 tw:bg-indigo-500 tw:border-indigo-500 rounded-3 rounded-bottom-right-none hover:tw:bg-indigo-400 hover:tw:border-indigo-400 tw:text-white" :disabled="form.processing">Registrarse</button>
                </div>
            </form>
        </div>
    </main>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';

const showPassword = ref(false);
const passwordFocused = ref(false);
const showConfirmPassword = ref(false);
const confirmPasswordFocused = ref(false);

const form = useForm({
    username: '',
    email: '',
    password: '',
    confirmPassword: '',
});

const formErrors = ref({
    username: '',
    email: '',
    password: '',
    confirmPassword: '',
});

const clearError = (field: 'username' | 'email' | 'password' | 'confirmPassword') => {
    formErrors.value[field] = '';
};

const isValidUsername = (username: string) => {
    const re = /^[a-zA-Z0-9_\-]+$/;
    return re.test(username) && username.length > 0;
};

const isValidEmail = (email: string) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
};

const checkUserExists = async (username: string, email: string) => {
    try {
    
        const response = await axios.post('/api/v1/check-user', { username, email });
    
        return response.data;
    } catch (error) {
        console.error("Error al comprobar disponibilidad del usuario:", error);
    
        return { usernameExists: false, emailExists: false };
    }
};

const submit = async () => {
    let isValid = true;

    if (!isValidUsername(form.username)) {
        formErrors.value.username = 'El nombre de usuario solo puede contener letras, nÃºmeros, guiones y guiones bajos.';
        isValid = false;
    }

    if (!isValidEmail(form.email)) {
        formErrors.value.email = 'Formato de correo incorrecto.';
        isValid = false;
    }

    if (form.password.length < 8) {
        formErrors.value.password = 'La contrasena debe ser mayor a 8 caracteres.';
        isValid = false;
    }

    if (form.password !== form.confirmPassword) {
        formErrors.value.confirmPassword = 'Las contraseñas no coinciden.';
        isValid = false;
    }

    if (isValid) {
        const { usernameExists, emailExists } = await checkUserExists(form.username, form.email);
        
        if (usernameExists) {
            formErrors.value.username = 'Este nombre de usuario ya estÃ¡ en uso.';
            isValid = false;
        }

        if (emailExists) {
            formErrors.value.email = 'Este correo electrÃ³nico ya estÃ¡ registrado.';
            isValid = false;
        }
    }

    if (isValid) {
        form.post('/signup', {
            // Si falla se vuelve a rellenar el formulario con los datos introducidos
            onError: (errors) => {
                if (errors.username) formErrors.value.username = errors.username;
                if (errors.email) formErrors.value.email = errors.email;
                if (errors.password) formErrors.value.password = errors.password;
            }
        });
    }
};
</script>
