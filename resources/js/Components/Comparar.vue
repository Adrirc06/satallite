<template>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="d-flex flex-column tw:border-2 tw:border-gray-500 tw:h-60 rounded-4 rounded-bottom-right-none p-3 just">
            <div class="d-flex flex-row align-items-center tw:border-b-2 tw:border-gray-500 tw:h-20">
                <i class="tw:!text-indigo-500 bi bi-pc-display h2"></i>
                <p class="h4 mx-2">Caja</p>
            </div>
            <div class="tw:flex-1 w-100 d-flex justify-content-center align-items-center">
                <p class="m-0">Nombre del componente</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="d-flex flex-column tw:border-2 tw:border-gray-300 tw:h-60 rounded-4 rounded-bottom-right-none p-3 shadow-sm">
            <div class="d-flex flex-row align-items-center tw:border-b-2 tw:border-gray-200 tw:h-20 tw:pb-2">
                <i class="tw:text-indigo-500 bi bi-cpu h2 tw:mb-0"></i>
                <p class="h5 mx-2 tw:mb-0 tw:font-semibold">Procesador</p>
            </div>
            <div class="tw:flex-1 w-100 d-flex justify-content-center align-items-center text-center">
                <p class="m-0 tw:text-gray-700">{{ build.components?.cpu?.name || 'No especificado' }}</p>
            </div>
        </div>
    </div>
</template>