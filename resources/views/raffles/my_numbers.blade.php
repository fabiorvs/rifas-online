<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Meus Números - ') }} {{ $raffle->title }}
            </h2>

            <!-- Botão Voltar -->
            <a href="{{ route('raffles.my') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <!-- Informações da Rifa -->
                <div class="flex flex-col md:flex-row items-center md:items-start">
                    <img src="{{ asset('storage/' . $raffle->image) }}"
                        alt="{{ $raffle->title }}"
                        class="w-48 h-48 object-cover rounded-md md:mr-6 mb-4 md:mb-0">

                    <div>
                        <h3 class="text-2xl font-semibold">{{ $raffle->title }}</h3>
                        <p class="text-gray-700 mt-2">{!! $raffle->description !!}</p>

                        <span class="px-4 py-1 rounded text-white mt-2 inline-block
                            {{ $raffle->status === 'Aberta' ? 'bg-blue-500' : '' }}
                            {{ $raffle->status === 'Finalizada' ? 'bg-yellow-500' : '' }}
                            {{ $raffle->status === 'Sorteada' ? 'bg-green-500' : '' }}
                            {{ $raffle->status === 'Aguardando Sorteio' ? 'bg-purple-500' : '' }}">
                            {{ ucfirst($raffle->status) }}
                        </span>

                        <!-- Exibir Número Sorteado Caso a Rifa Tenha Sido Sorteada -->
                        @if ($raffle->status === 'Sorteada')
                            <p class="text-lg font-bold mt-3">
                                🏆 Número Sorteado:
                                <span class="text-green-600 text-2xl">{{ $raffle->winning_number ?? 'Não informado' }}</span>
                            </p>
                        @endif
                    </div>
                </div>

                <hr class="my-6">

                <!-- Exibir Números Comprados -->
                @if ($numbers->isEmpty())
                    <p class="text-gray-600">Você ainda não comprou números nesta rifa.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border border-gray-300 p-2">Número</th>
                                    <th class="border border-gray-300 p-2">Status</th>
                                    <th class="border border-gray-300 p-2">Data da Compra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($numbers as $number)
                                    <tr class="border border-gray-300
                                        {{ $raffle->status === 'Sorteada' && $number->number == $raffle->winning_number ? 'bg-green-100' : '' }}">
                                        <td class="border border-gray-300 p-2 text-center font-bold">{{ $number->number }}</td>
                                        <td class="border border-gray-300 p-2 text-center">
                                            <span class="px-2 py-1 rounded text-white
                                                {{ $number->status === 'Reservado' ? 'bg-yellow-500' : '' }}
                                                {{ $number->status === 'Confirmado' ? 'bg-green-500' : '' }}">
                                                {{ ucfirst($number->status) }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-300 p-2 text-center">
                                            {{ $number->updated_at->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
