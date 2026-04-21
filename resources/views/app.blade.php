<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/js/app.js')
        @inertiaHead
        <link rel="icon" type="image/x-icon" href="/img/favicon.ico"/>
        <title>{{ config('app.name', 'Laravel') }}</title>
    </head>
    <body>
        @inertia
    </body>
</html>
