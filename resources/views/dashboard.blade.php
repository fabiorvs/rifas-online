<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            <!-- Botão Criar Rifa -->
            <a href="{{ route('raffles.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Criar Nova Rifa
            </a>
        </div>
    </x-slot>

    <!-- Exibir créditos -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold">Seus Créditos: {{ $creditBalance }}</h3>
            </div>
        </div>
    </div>

    <!-- Listagem das Rifas -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Minhas Rifas</h3>

                @if ($raffles->isEmpty())
                    <p class="text-gray-600">Você ainda não criou nenhuma rifa.</p>
                @else
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-300 p-2 text-left">Título</th>
                                <th class="border border-gray-300 p-2 text-left">Total de Números</th>
                                <th class="border border-gray-300 p-2 text-left">Status</th>
                                <th class="border border-gray-300 p-2 text-left">Valor por Número</th>
                                <th class="border border-gray-300 p-2 text-left">Criado em</th>
                                <th class="border border-gray-300 p-2 text-left">Números</th>
                                <th class="border border-gray-300 p-2 text-left">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($raffles as $raffle)
                                <tr class="border border-gray-300">
                                    <td class="border border-gray-300 p-2">{{ $raffle->title }}</td>
                                    <td class="border border-gray-300 p-2">{{ $raffle->total_numbers }}</td>
                                    <td class="border border-gray-300 p-2">
                                        <span
                                            class="px-2 py-1 rounded text-white
                                            {{ $raffle->status === 'Aberta' ? 'bg-green-500' : '' }}
                                            {{ $raffle->status === 'Aguardando Sorteio' ? 'bg-orange-500' : '' }}
                                            {{ $raffle->status === 'Sorteada' ? 'bg-blue-500' : '' }}
                                            {{ $raffle->status === 'Finalizada' ? 'bg-gray-500' : '' }}
                                            {{ $raffle->status === 'Cancelada' ? 'bg-red-500' : '' }}">
                                            {{ ucfirst($raffle->status) }}
                                        </span>
                                    </td>

                                    </td>
                                    <td class="border border-gray-300 p-2">
                                        R$ {{ number_format($raffle->price_per_number, 2, ',', '.') }}
                                    </td>
                                    <td class="border border-gray-300 p-2">
                                        {{ $raffle->created_at->format('d/m/Y H:i') }}</td>

                                    <!-- Nova Coluna: Total de Números (Disponíveis, Reservados, Confirmados) -->
                                    <td class="border border-gray-300 p-2">
                                        <div class="flex space-x-2">
                                            <!-- Disponíveis -->
                                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-700"
                                                title="Números Disponíveis">
                                                {{ $raffle->total_disponiveis }}
                                            </span>

                                            <!-- Reservados -->
                                            <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700"
                                                title="Números Reservados">
                                                {{ $raffle->total_reservados }}
                                            </span>

                                            <!-- Confirmados -->
                                            <span class="px-2 py-1 rounded bg-green-100 text-green-700"
                                                title="Números Confirmados">
                                                {{ $raffle->total_confirmados }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="border border-gray-300 p-2">
                                        <a href="#" class="text-blue-500 hover:underline">Ver</a> |
                                        <a href="#" class="text-yellow-500 hover:underline">Editar</a> |
                                        <a href="#" class="text-red-500 hover:underline">Excluir</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
