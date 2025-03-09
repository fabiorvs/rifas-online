<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minhas Rifas Compradas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($raffles as $raffle)
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <img src="{{ asset('storage/' . $raffle->image) }}" alt="{{ $raffle->title }}"
                            class="w-full h-48 object-cover rounded-md">

                        <h3 class="text-lg font-semibold mt-2">{{ $raffle->title }}</h3>

                        <!-- Exibir Status da Rifa -->
                        <span
                            class="px-4 py-1 rounded text-white mt-2 inline-block
                                {{ $raffle->status === 'Aberta' ? 'bg-blue-500' : '' }}
                                {{ $raffle->status === 'Finalizada' ? 'bg-yellow-500' : '' }}
                                {{ $raffle->status === 'Aguardando Sorteio' ? 'bg-purple-500' : '' }}
                                {{ $raffle->status === 'Cancelada' ? 'bg-red-500' : '' }}
                                @if ($raffle->status === 'Sorteada') @php
                                        $winningNumber = $raffle->winning_number ?? null;
                                        $userWon = $raffle->numbers->contains('number', $winningNumber);
                                    @endphp
                                    {{ $userWon ? 'bg-green-500' : 'bg-orange-500' }} @endif
                            ">
                            @if ($raffle->status === 'Aberta')
                                ⏳ Aguardando vender todos os números.
                            @elseif ($raffle->status === 'Aguardando Sorteio')
                                ⏳ Estamos aguardando o sorteio!
                            @elseif ($raffle->status === 'Finalizada')
                                ✅ Rifa encerrada. Aguardando resultado.
                            @elseif ($raffle->status === 'Sorteada')
                                @if ($userWon)
                                    🎉 Parabéns! Você ganhou com o número {{ $winningNumber }}!
                                @else
                                    😢 Infelizmente você não foi o ganhador.
                                @endif
                            @elseif ($raffle->status === 'Cancelada')
                                ⚠️ Rifa cancelada.
                            @endif
                        </span>

                        <!-- Botão Meus Números -->
                        <a href="{{ route('raffles.my_numbers', $raffle->id) }}"
                            class="mt-4 block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                            Meus Números
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
