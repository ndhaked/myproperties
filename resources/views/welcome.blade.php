<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col items-center justify-center bg-white text-gray-900">
        <main class="text-center">
            <h1 class="text-3xl font-semibold">{{ config('app.name') }}</h1>
            <p class="mt-2 text-gray-500">Your health, our priority.</p>
        </main>
    </body>
</html>
