<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Rifas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
            /* Faz com que essa área ocupe o máximo possível do espaço disponível */
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Sistema de Rifas</h1>
            <nav>
                @if (Route::has('login'))
                    <div class="flex space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-blue-500 hover:underline">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Entrar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Registrar</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </div>
    </header>
    <main class="container mx-auto py-16 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Participe das Melhores Rifas Online!</h2>
        <p class="text-lg text-gray-600 mt-4">Aposte na sorte e concorra a prêmios incríveis.</p>
        <a href="{{ route('register') }}"
            class="mt-6 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg text-lg hover:bg-blue-600">Cadastre-se
            Agora</a>
    </main>
    <section class="container mx-auto py-12 text-center">
        <h3 class="text-2xl font-bold text-gray-800">Como Funciona?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-6">
            <div class="p-6 bg-white shadow rounded-lg">
                <h4 class="text-lg font-semibold">1. Escolha sua Rifa</h4>
                <p class="text-gray-600">Navegue pelas rifas disponíveis e selecione a sua favorita.</p>
            </div>
            <div class="p-6 bg-white shadow rounded-lg">
                <h4 class="text-lg font-semibold">2. Compre seu Número</h4>
                <p class="text-gray-600">Garanta seu número da sorte de forma rápida e segura.</p>
            </div>
            <div class="p-6 bg-white shadow rounded-lg">
                <h4 class="text-lg font-semibold">3. Aguarde o Sorteio</h4>
                <p class="text-gray-600">Fique atento ao sorteio e torça para ganhar!</p>
            </div>
        </div>
    </section>
    <footer class="bg-gray-800 text-white text-center p-4">
        <p>&copy; 2025 Sistema de Rifas. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
