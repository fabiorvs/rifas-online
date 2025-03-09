<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seus Números Reservados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="bg-green-500 text-white p-3 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($reservedNumbers->isEmpty())
                    <p class="text-gray-600">Você não tem números reservados.</p>
                @else
                    <form action="{{ route('raffle_numbers.confirm') }}" method="POST">
                        @csrf
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border border-gray-300 p-2">Rifa</th>
                                    <th class="border border-gray-300 p-2">Número</th>
                                    <th class="border border-gray-300 p-2">Preço</th>
                                    <th class="border border-gray-300 p-2">Reservado Por</th>
                                    <th class="border border-gray-300 p-2">E-mail</th>
                                    <th class="border border-gray-300 p-2 text-center">Confirmar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservedNumbers as $numero)
                                    <tr class="border border-gray-300">
                                        <td class="border border-gray-300 p-2">{{ $numero->raffle->title }}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">
                                            {{ $numero->number }}</td>
                                        <td class="border border-gray-300 p-2">
                                            R$ {{ number_format($numero->raffle->price_per_number, 2, ',', '.') }}
                                        </td>
                                        <td class="border border-gray-300 p-2">
                                            {{ $numero->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="border border-gray-300 p-2">
                                            {{ $numero->user->email ?? 'N/A' }}
                                        </td>
                                        <td class="border border-gray-300 p-2 text-center">
                                            <input type="checkbox" name="numbers[]" value="{{ $numero->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit"
                            class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Confirmar Selecionados
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
