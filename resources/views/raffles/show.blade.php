<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $raffle->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-gray-800 rounded-xl shadow-lg">
        <!-- Título -->
        <h2 class="text-center text-2xl font-bold mb-4">{{ $raffle->title }}</h2>

        <!-- Imagem da Rifa -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('storage/' . $raffle->image) }}" alt="Imagem da Rifa"
                class="w-full max-w-lg h-auto rounded-lg shadow-md">
        </div>

        <p class="text-center text-gray-400 mb-5">{!! $raffle->description !!}</p>

        <!-- Status da Rifa -->
        <p class="text-center text-lg font-semibold mb-2">
            Status: <span class="text-blue-600">{{ ucfirst($raffle->status) }}</span>
        </p>


        @auth
            <form action="{{ route('raffle.buy') }}" method="POST">
                @csrf
                <input type="hidden" name="selected_numbers" id="selected-numbers">

                <div class="max-w-4xl mx-auto p-4">
                    <h2 class="text-center text-2xl font-bold mb-4">Escolha seus números</h2>

                    <div class="grid grid-cols-10 gap-2">
                        @foreach ($numbers as $num)
                            @php
                                $isUserNumber = in_array($num->id, $userNumbers); // Lista de números comprados pelo usuário
                                $status = strtolower($num->status);
                            @endphp

                            <button type="button" data-number="{{ $num->id }}"
                                class="number-button p-2 text-sm font-semibold rounded text-white shadow-md transition-all
                                @if ($isUserNumber) bg-blue-500 cursor-not-allowed opacity-90 @endif
                                @if (!$isUserNumber && $status == 'confirmado') bg-red-600 cursor-not-allowed opacity-75 @endif
                                @if (!$isUserNumber && $status == 'reservado') bg-orange-500 cursor-not-allowed opacity-75 @endif
                                @if (!$isUserNumber && $status == 'disponível') bg-green-500 hover:bg-green-600 cursor-pointer @endif"
                                @if (!$isUserNumber && $status !== 'disponível') disabled @endif
                                title="{{ $isUserNumber ? 'Seu Número' : $num->status }}">
                                {{ $num->number }}
                            </button>
                        @endforeach
                    </div>
                </div>


                <div class="mt-6 text-center">
                    <button id="buy-button" type="submit"
                        class="px-6 py-3 bg-gray-600 cursor-not-allowed rounded-lg text-white font-bold" disabled>
                        Comprar Selecionados
                    </button>
                </div>
            </form>
        @else
            <p class="text-center mt-5 text-red-400 font-semibold">
                Faça login para selecionar números.
            </p>
        @endauth
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectedNumbers = new Set();
            const buyButton = document.getElementById("buy-button");
            const inputSelectedNumbers = document.getElementById("selected-numbers");

            document.querySelectorAll(".number-button").forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault();
                    const number = this.dataset.number;

                    if (selectedNumbers.has(number)) {
                        selectedNumbers.delete(number);
                        this.classList.remove("border-4", "border-blue-400", "scale-105");
                    } else {
                        selectedNumbers.add(number);
                        this.classList.add("border-4", "border-blue-400", "scale-105");
                    }

                    inputSelectedNumbers.value = Array.from(selectedNumbers).join(",");
                    buyButton.disabled = selectedNumbers.size === 0;
                    buyButton.classList.toggle("bg-blue-600", selectedNumbers.size > 0);
                    buyButton.classList.toggle("bg-gray-600", selectedNumbers.size === 0);
                    buyButton.classList.toggle("cursor-not-allowed", selectedNumbers.size === 0);
                    buyButton.classList.toggle("cursor-pointer", selectedNumbers.size > 0);
                });
            });
        });
    </script>
</body>

</html>
