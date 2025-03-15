<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $raffle->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .raffle-description h1 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #facc15;
            /* Amarelo */
            margin-bottom: 10px;
        }

        .raffle-description h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #f97316;
            /* Laranja */
            margin-bottom: 8px;
        }

        .raffle-description h3 {
            font-size: 1.3rem;
            font-weight: bold;
            color: #38bdf8;
            /* Azul */
            margin-bottom: 6px;
        }

        .raffle-description p {
            font-size: 1rem;
            line-height: 1.6;
            color: #e5e7eb;
            /* Cinza claro */
            margin-bottom: 10px;
        }

        .raffle-description strong {
            font-weight: bold;
            color: #f43f5e;
            /* Vermelho */
        }

        .raffle-description em {
            font-style: italic;
            color: #a3e635;
            /* Verde limão */
        }

        .raffle-description blockquote {
            border-left: 4px solid #6366f1;
            /* Roxo */
            padding-left: 12px;
            font-style: italic;
            color: #cbd5e1;
            margin: 12px 0;
        }

        .raffle-description ul {
            list-style-type: disc;
            margin-left: 20px;
        }

        .raffle-description ol {
            list-style-type: decimal;
            margin-left: 20px;
        }

        .raffle-description a {
            color: #38bdf8;
            /* Azul claro */
            text-decoration: underline;
            transition: color 0.3s;
        }

        .raffle-description a:hover {
            color: #facc15;
            /* Amarelo ao passar o mouse */
        }
    </style>

</head>

<body class="bg-gray-900 text-white">
    <!-- Header com link para Dashboard -->
    <div class="w-full bg-gray-800 p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">{{ $raffle->title }}</h1>

        @auth
            <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Dashboard
            </a>
        @endauth
    </div>

    <div class="max-w-4xl mx-auto mt-10 p-6 bg-gray-800 rounded-xl shadow-lg">
        <!-- Imagem da Rifa -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('storage/' . $raffle->image) }}" alt="Imagem da Rifa"
                class="w-full max-w-lg h-auto rounded-lg shadow-md">
        </div>

        <div class="raffle-description prose prose-invert">
            {!! $raffle->description !!}
        </div>


        <!-- Status da Rifa -->
        <div class="text-center text-lg font-semibold mb-2">
            <span
                class="inline-block px-4 py-2 rounded-full text-white shadow-md
                    @if (strtolower($raffle->status) === 'aberta') bg-green-600 @endif
                    @if (strtolower($raffle->status) === 'finalizada') bg-gray-500 @endif
                    @if (strtolower($raffle->status) === 'sorteada') bg-yellow-500 @endif
                    @if (strtolower($raffle->status) === 'aguardando sorteio') bg-purple-500 @endif
                    @if (strtolower($raffle->status) === 'cancelada') bg-red-500 @endif">
                Status: {{ ucfirst($raffle->status) }}
            </span>
        </div>

        <!-- Exibir número vencedor se a rifa foi sorteada -->
        @if ($raffle->status === 'Sorteada' && $raffle->winning_number)
            <div class="text-center mt-6 p-6 bg-green-500 text-black rounded-lg shadow-lg">
                <h3 class="text-3xl font-bold">🎉 Número Sorteado: <span
                        class="text-4xl">{{ $raffle->winning_number }}</span></h3>
                <p class="mt-2 font-semibold">Parabéns ao vencedor!</p>
            </div>
        @elseif($raffle->status === 'Sorteada' && !$raffle->winning_number)
            <div class="text-center mt-6 p-6 bg-red-500 text-white rounded-lg shadow-lg">
                <h3 class="text-2xl font-bold">⚠️ Erro!</h3>
                <p class="mt-2">Nenhum número foi sorteado para esta rifa.</p>
            </div>
        @endif

        @auth
            @php
                $isOwner = Auth::id() == $raffle->user_id;
                $canBuy = strtolower($raffle->status) === 'aberta' && !$isOwner;
            @endphp

            <div class="max-w-4xl mx-auto p-4">
                <h2 class="text-center text-2xl font-bold mb-4">
                    {{ $isOwner ? 'Números da Rifa' : 'Escolha seus números' }}
                </h2>

                <div class="grid grid-cols-10 gap-2">
                    @foreach ($numbers as $num)
                        @php
                            $isUserNumber = in_array($num->id, $userNumbers);
                            $isUserConfirmed = $isUserNumber && strtolower($num->status) === 'confirmado';
                            $isUserReserved = $isUserNumber && strtolower($num->status) === 'reservado';
                            $status = strtolower($num->status);
                            $isAvailable = $status == 'disponível' && $canBuy;
                        @endphp

                        <button type="button" data-number="{{ $num->id }}"
                            class="number-button p-2 text-sm font-semibold rounded text-white shadow-md transition-all
                            @if ($isUserConfirmed) bg-indigo-700 cursor-not-allowed opacity-90 @endif
                            @if ($isUserReserved) bg-purple-600 cursor-not-allowed opacity-90 @endif
                            @if (!$isUserNumber && $status == 'confirmado') bg-red-600 cursor-not-allowed opacity-75 @endif
                            @if (!$isUserNumber && $status == 'reservado') bg-orange-500 cursor-not-allowed opacity-75 @endif
                            @if ($isAvailable) bg-green-500 cursor-pointer hover:bg-green-600 @endif"
                            @if (!$isAvailable) disabled @endif
                            title="{{ $isUserConfirmed ? 'Seu Número Confirmado' : ($isUserReserved ? 'Seu Número Reservado' : ucfirst($num->status)) }}">
                            {{ $num->number }}
                        </button>
                    @endforeach
                </div>
            </div>

            @if ($canBuy)
                <form action="{{ route('raffle.buy') }}" method="POST">
                    @csrf
                    <input type="hidden" name="selected_numbers" id="selected-numbers">

                    <div class="mt-6 text-center">
                        <button id="buy-button" type="submit"
                            class="px-6 py-3 bg-gray-600 cursor-not-allowed rounded-lg text-white font-bold" disabled>
                            Comprar Selecionados
                        </button>
                    </div>
                </form>
            @elseif($isOwner)
                <p class="text-center mt-5 text-green-400 font-semibold">
                    Você é o dono desta rifa. Apenas pode visualizar os números reservados e confirmados.
                </p>
            @else
                <p class="text-center mt-5 text-red-400 font-semibold">
                    A rifa não está disponível para compra.
                </p>
            @endif
        @else
            <div class="text-center mt-5">
                <p class="text-red-400 font-semibold">Faça login para visualizar os números.</p>
                <a href="{{ route('login') }}?redirect={{ request()->fullUrl() }}"
                    class="mt-3 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Fazer Login
                </a>
            </div>
        @endauth
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let selectedNumbers = [];
            const buttons = document.querySelectorAll(".number-button");
            const inputHidden = document.getElementById("selected-numbers");
            const buyButton = document.getElementById("buy-button");

            buttons.forEach(button => {
                button.addEventListener("click", function() {
                    const numberId = this.getAttribute("data-number");

                    // Se for um número confirmado ou reservado do usuário, não permitir seleção
                    if (this.classList.contains("bg-indigo-700") || this.classList.contains(
                            "bg-purple-600")) {
                        return;
                    }

                    if (this.classList.contains("bg-green-500")) {
                        // Selecionar o número (Muda para roxo)
                        selectedNumbers.push(numberId);
                        this.classList.remove("bg-green-500", "hover:bg-green-600");
                        this.classList.add("bg-purple-400", "cursor-not-allowed");
                    } else if (this.classList.contains("bg-purple-400")) {
                        // Deselecionar o número (Volta para verde)
                        selectedNumbers = selectedNumbers.filter(num => num !== numberId);
                        this.classList.remove("bg-purple-400", "cursor-not-allowed");
                        this.classList.add("bg-green-500", "hover:bg-green-600", "cursor-pointer");
                    }

                    inputHidden.value = JSON.stringify(selectedNumbers);

                    buyButton.disabled = selectedNumbers.length === 0;
                    buyButton.classList.toggle("cursor-not-allowed", selectedNumbers.length === 0);
                    buyButton.classList.toggle("bg-blue-600", selectedNumbers.length > 0);
                });
            });
        });
    </script>
</body>

</html>
