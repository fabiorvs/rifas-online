<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Números Vendidos - Confirmação de Pagamento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($raffleNumbers->isEmpty())
                    <p class="text-gray-600">Nenhum número aguardando confirmação de pagamento.</p>
                @else
                    <form action="{{ route('raffle_numbers.confirm_payment') }}" method="POST">
                        @csrf
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border border-gray-300 p-2">Rifa</th>
                                    <th class="border border-gray-300 p-2">Número</th>
                                    <th class="border border-gray-300 p-2">Preço</th>
                                    <th class="border border-gray-300 p-2">Comprador</th>
                                    <th class="border border-gray-300 p-2">E-mail</th>
                                    <th class="border border-gray-300 p-2">Data Reserva</th>
                                    <th class="border border-gray-300 p-2 text-center">Confirmar Pagamento</th>
                                    <th class="border border-gray-300 p-2 text-center">Cancelar Reserva</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($raffleNumbers as $number)
                                    <tr class="border border-gray-300">
                                        <td class="border border-gray-300 p-2">{{ $number->raffle->title }}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">
                                            {{ $number->number }}</td>
                                        <td class="border border-gray-300 p-2">
                                            R$ {{ number_format($number->raffle->price_per_number, 2, ',', '.') }}
                                        </td>
                                        <td class="border border-gray-300 p-2">
                                            {{ $number->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="border border-gray-300 p-2">
                                            {{ $number->user->email ?? 'N/A' }}
                                        </td>
                                        <td class="border border-gray-300 p-2">
                                            {{ $number->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="border border-gray-300 p-2 text-center">
                                            <input type="checkbox" name="numbers[]" value="{{ $number->id }}">
                                        </td>
                                        <td class="border border-gray-300 p-2 text-center">
                                            <form action="{{ route('raffle_numbers.cancel', $number->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja cancelar esta reserva?');">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                                    Cancelar Reserva
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit"
                            class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Confirmar Pagamento
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
