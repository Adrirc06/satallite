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
                    <input 
                        type="password" 
                        class="form-control pb-2 w-100 keyboard-safe-area border-2" 
                        :class="[formErrors.password ? 'tw:!border-red-500' : 'tw:!border-gray-500']"
                        id="password" 
                        autocomplete="new-password"
                        v-model="form.password"
                        @focus="clearError('password')"
                        @click="clearError('password')"
                    >
                    <div v-if="formErrors.password" class="tw:text-red-500 small mt-1">{{ formErrors.password }}</div>
                </div>
                <div class="mb-3 w-100 text-start">
                    <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
                    <input 
                        type="password" 
                        class="form-control pb-2 w-100 keyboard-safe-area border-2" 
                        :class="[formErrors.confirmPassword ? 'tw:!border-red-500' : 'tw:!border-gray-500']"
                        id="confirmPassword" 
                        autocomplete="new-password"
                        v-model="form.confirmPassword"
                        @focus="clearError('confirmPassword')"
                        @click="clearError('confirmPassword')"
                    >
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

// Validar que sean solo alfanuméricos, guiones y guiones bajos (sin espacios)
const isValidUsername = (username: string) => {
    const re = /^[a-zA-Z0-9_\-]+$/;
    return re.test(username) && username.length > 0;
};

// Validar formato de email
const isValidEmail = (email: string) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
};

// Función para comprobar si el usuario ya existe en base de datos
const checkUserExists = async (username: string, email: string) => {
    try {
        // Hacemos una petición al backend
        const response = await axios.post('/api/v1/check-user', { username, email });
        // Suponemos que el endpoint devuelve esto: { usernameExists: boolean, emailExists: boolean }
        return response.data;
    } catch (error) {
        console.error("Error al comprobar disponibilidad del usuario:", error);
        // Si no existe el endpoint aún, vamos a devolver false temporalmente para dejar que Inertia haga su trabajo
        return { usernameExists: false, emailExists: false };
    }
};

const submit = async () => {
    let isValid = true;

    if (!isValidUsername(form.username)) {
        formErrors.value.username = 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.';
        isValid = false;
    }

    if (!isValidEmail(form.email)) {
        formErrors.value.email = 'Formato de correo incorrecto.';
        isValid = false;
    }

    if (form.password.length < 8) {
        formErrors.value.password = 'La contraseña debe ser mayor a 8 caracteres.';
        isValid = false;
    }

    if (form.password !== form.confirmPassword) {
        formErrors.value.confirmPassword = 'Las contraseñas no coinciden.';
        isValid = false;
    }

    // Si pasamos las validaciones en JS, comprobamos contra la base de datos
    if (isValid) {
        const { usernameExists, emailExists } = await checkUserExists(form.username, form.email);
        
        if (usernameExists) {
            formErrors.value.username = 'Este nombre de usuario ya está en uso.';
            isValid = false;
        }

        if (emailExists) {
            formErrors.value.email = 'Este correo electrónico ya está registrado.';
            isValid = false;
        }
    }

    if (isValid) {
        // Enviar la petición de registro con Inertia
        form.post('/signup', {
            onError: (errors) => {
                // Por si el servidor de Inertia devuelve algún error que se escapó a las validaciones de cliente
                if (errors.username) formErrors.value.username = errors.username;
                if (errors.email) formErrors.value.email = errors.email;
                if (errors.password) formErrors.value.password = errors.password;
            }
        });
    }
};
</script>