<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Estilos do Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>

    <script src="https://cdn.tiny.cloud/1/l65z99kfznruhzztxw8gb3bq92ny2qlyz08jxx4f1f6h5kz7/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

</head>

<body class="font-sans antialiased bg-gray-100">

    <div class="min-h-screen flex flex-col">
        <!-- Barra de Navegação -->
        @include('layouts.navigation')

        <!-- Header (se existir) -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Conteúdo Principal -->
        <main class="flex-grow container mx-auto p-4">
            {{ $slot }}
        </main>
    </div>
    <x-toast />
    <!-- Scripts Opcionais -->
    @stack('scripts')

</body>

</html>
