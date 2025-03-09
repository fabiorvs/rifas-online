<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Visão Geral da Rifa - ') }} {{ $raffle->title }}
            </h2>

            <!-- Botão Voltar -->
            <a href="{{ route('dashboard') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <!-- Big Numbers -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-100 p-4 rounded text-center">
                        <h3 class="text-lg font-semibold text-green-600">Confirmados</h3>
                        <p class="text-3xl font-bold">{{ $confirmed }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded text-center">
                        <h3 class="text-lg font-semibold text-yellow-600">Reservados</h3>
                        <p class="text-3xl font-bold">{{ $reserved }}</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded text-center">
                        <h3 class="text-lg font-semibold text-gray-600">Disponíveis</h3>
                        <p class="text-3xl font-bold">{{ $available }}</p>
                    </div>
                </div>

                <!-- Exibir Ganhador Caso a Rifa Tenha Sido Sorteada -->
                @if ($raffle->status === 'Sorteada' && $winner)
                    <div class="bg-green-200 p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-2xl font-semibold text-green-800">🏆 Ganhador da Rifa</h3>
                        <p class="text-lg font-bold mt-2">Número Sorteado: <span class="text-green-700 text-3xl">{{ $raffle->winning_number }}</span></p>
                        <p class="text-gray-800 mt-2">🎟️ Comprador: <strong>{{ $winner->user->name ?? 'Nome não disponível' }}</strong></p>
                        <p class="text-gray-600">📧 E-mail: <strong>{{ $winner->user->email ?? 'E-mail não disponível' }}</strong></p>
                    </div>
                @endif

                <hr class="my-6">

                <!-- Tabela de Números -->
                <!-- Renderiza o DataGrid de Números -->
                @livewire('raffle-numbers-table', ['raffleId' => $raffle->id])

            </div>
        </div>
    </div>
</x-app-layout>
