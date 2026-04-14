<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/js/app.js')
        @inertiaHead
        <link
            href="https://fonts.googleapis.com/css2?family=Chiron+GoRound+TC:wght@200..900&family=Quantico:ital,wght@0,400;0,700;1,400;1,700&display=swap"
            rel="stylesheet" />
        <title>{{ config('app.name', 'Laravel') }}</title>
    </head>
    <body>
        @inertia
    </body>
</html>
