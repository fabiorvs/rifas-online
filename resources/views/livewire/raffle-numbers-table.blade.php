<div> <!-- Elemento raiz único obrigatório para Livewire -->

    <!-- Campo de Busca e Botão -->
    <div class="mb-4 flex">
        <input type="text" wire:model="query" class="border border-gray-300 p-2 rounded w-full"
            placeholder="Buscar por número, comprador ou e-mail...">

        <button wire:click="searchNumbers"
            class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Buscar
        </button>
    </div>

    <!-- Tabela de Números -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 p-2">Número</th>
                    <th class="border border-gray-300 p-2">Status</th>
                    <th class="border border-gray-300 p-2">Comprador</th>
                    <th class="border border-gray-300 p-2">E-mail</th>
                    <th class="border border-gray-300 p-2">Última Atualização</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($numbers as $number)
                    <tr class="border border-gray-300">
                        <td class="border border-gray-300 p-2 text-center font-bold">{{ $number->number }}</td>
                        <td class="border border-gray-300 p-2 text-center">
                            <span
                                class="px-2 py-1 rounded text-white
                                {{ $number->status === 'Disponível' ? 'bg-gray-500' : '' }}
                                {{ $number->status === 'Reservado' ? 'bg-yellow-500' : '' }}
                                {{ $number->status === 'Confirmado' ? 'bg-green-500' : '' }}">
                                {{ ucfirst($number->status) }}
                            </span>
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ $number->user->name ?? '-' }}
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ $number->user->email ?? '-' }}
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            {{ $number->updated_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $numbers->links() }}
        </div>
    </div>

</div>
