<template>
    <main class="flex-grow-1 container-fluid justify-content-center d-flex align-items-center py-5">
        <div class="rounded-5 rounded-bottom-right-none border-2 tw:border-gray-500 d-flex flex-column justify-content-center align-items-center p-5 text-center">
            <p class="tw:text-5xl m-3 quantico-bold text-center">Inicia Sesión</p>
            <form @submit.prevent="submit" class="w-100">
                <div class="mb-3 w-100 text-start">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input 
                        type="text" 
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
                            autocomplete="current-password"
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
                <div class="mb-3 w-100 text-start d-flex align-items-center">
                    <input class="form-check-input me-2 tw:!border-gray-500" type="checkbox" id="remember" v-model="form.remember">
                    <label class="form-check-label" for="remember">
                        Recordarme
                    </label>
                </div>
                <p>¿Aún no tienes cuenta? ¡<Link href="/signup" class="text-decoration-none tw:!text-indigo-500">Regístrate</Link>!</p>
                <div class="w-100 d-flex justify-content-center">
                    <button type="submit" class="custom-btn w-75 tw:bg-indigo-500 tw:border-indigo-500 rounded-3 rounded-bottom-right-none hover:tw:bg-indigo-400 hover:tw:border-indigo-400 tw:text-white" :disabled="form.processing">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </main>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const showPassword = ref(false);
const passwordFocused = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const formErrors = ref({
    email: '',
    password: '',
});

const isValidEmail = (email: string) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
};

const clearError = (field: 'email' | 'password') => {
    formErrors.value[field] = '';
};

const submit = () => {
    let isValid = true;

    if (!isValidEmail(form.email)) {
        formErrors.value.email = 'Formato incorrecto';
        isValid = false;
    }

    if (form.password.length < 8) {
        formErrors.value.password = 'La contraseña debe ser mayor a 8 caractéres';
        isValid = false;
    }

    if (isValid) {
        form.post('/login', {
            onError: (errors) => {
                if (errors.email) formErrors.value.email = errors.email;
                if (errors.password) formErrors.value.password = errors.password;
            }
        });
    }
};
</script>