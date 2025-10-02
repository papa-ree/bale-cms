<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ 'Rakaca' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,500;1,500&family=Noto+Color+Emoji&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Open+Sans:ital,wght@0,500;1,500&family=Quicksand&display=swap"
        rel="stylesheet">

    <x-bale-cms::script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

{{-- Layout For Livewire Admin Panel --}}

<body
    class="h-full min-h-screen transition-colors duration-300 scrollbar-thin scrollbar-thumb-gray-900 scrollbar-track-gray-100/50 overscroll-none bg-gray-50 dark:bg-gray-900">

    <div class="w-full px-4 pt-5 pb-10 sm:px-6 md:px-8">
        <main>
            {{ $slot }}
        </main>
    </div>

</body>

</html>