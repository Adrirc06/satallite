<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="SATAllite - Plataforma de creación y compartición de configuraciones de PC y hardware.">
        
        <link rel="preload" as="image" href="/img/carousel/carousel1.webp">
        <link rel="preload" as="image" href="/img/carousel/carousel2.webp">
        <link rel="preload" as="image" href="/img/carousel/carousel3.webp">
        <link rel="preload" as="image" href="{{ Vite::asset('resources/img/logo.png') }}">
        <link rel="preload" as="image" href="{{ Vite::asset('resources/img/full_logo.png') }}">
        <link rel="preload" as="font" type="font/woff2" href="{{ Vite::asset('public/fonts/chiron-goround-tc-v4-latin-regular.woff2') }}" crossorigin>
        <link rel="preload" as="font" type="font/woff2" href="{{ Vite::asset('public/fonts/quantico-v19-latin-700.woff2') }}" crossorigin>

        @vite('resources/js/app.js')
        @inertiaHead
        <link rel="icon" type="image/x-icon" href="/img/favicon.ico"/>
        <title>{{ config('app.name', 'Laravel') }}</title>
    </head>
    <body>
        @inertia
    </body>
</html>
