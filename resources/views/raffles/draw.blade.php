<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sorteio da Rifa - ') }} {{ $raffle->title }}
            </h2>

            <a href="{{ route('raffles.my') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">

                <h3 class="text-2xl font-semibold">🎲 Clique no botão para iniciar o sorteio!</h3>

                <div class="mt-6">
                    <button id="start-draw"
                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded text-lg">
                        Iniciar Sorteio
                    </button>
                </div>

                <div id="draw-container" class="hidden mt-6">
                    <p class="text-3xl font-bold">⏳ Sorteando...</p>
                    <p id="countdown" class="text-4xl font-bold text-red-500 mt-2"></p>
                </div>

                <div id="winner-container" class="hidden mt-6">
                    <h3 class="text-3xl font-semibold text-green-500">🏆 Número Sorteado:</h3>
                    <p id="winning-number" class="text-5xl font-bold mt-2"></p>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('start-draw').addEventListener('click', function() {
            document.getElementById('start-draw').classList.add('hidden');
            document.getElementById('draw-container').classList.remove('hidden');

            let count = 5;
            let countdownElement = document.getElementById('countdown');

            let countdown = setInterval(function() {
                countdownElement.textContent = count;
                count--;

                if (count < 0) {
                    clearInterval(countdown);

                    // Fazer a requisição para sortear o número
                    fetch("{{ route('raffles.performDraw', $raffle->id) }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('draw-container').classList.add('hidden');
                            document.getElementById('winner-container').classList.remove('hidden');
                            document.getElementById('winning-number').textContent = data.winningNumber;
                        })
                        .catch(error => {
                            console.error('Erro ao sortear número:', error);
                            alert('Erro ao sortear número. Tente novamente.');
                        });
                }
            }, 1000);
        });
    </script>
</x-app-layout>
