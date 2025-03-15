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
                    <form id="raffle-form" action="{{ route('raffle_numbers.confirm_payment') }}" method="POST">
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
                                    <th class="border border-gray-300 p-2 text-center">Selecionar</th>
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
                                            <input type="checkbox" class="number-checkbox" name="numbers[]"
                                                value="{{ $number->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="flex space-x-4 mt-4">
                            <!-- Botão para Selecionar Todos -->
                            <button type="button" id="select-all-btn"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Selecionar Todos
                            </button>

                            <!-- Botão para Confirmar Selecionados -->
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Confirmar Selecionados
                            </button>

                            <!-- Botão para Cancelar Selecionados -->
                            <button type="button" id="cancel-selected"
                                class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                                Cancelar Selecionados
                            </button>
                        </div>
                    </form>

                    <!-- Formulário de cancelamento -->
                    <form id="cancel-form" action="{{ route('raffle_numbers.cancel') }}" method="POST">
                        @csrf
                        <div id="cancel-numbers-container"></div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectAllBtn = document.getElementById("select-all-btn");
            const checkboxes = document.querySelectorAll(".number-checkbox");
            const cancelButton = document.getElementById("cancel-selected");
            const cancelForm = document.getElementById("cancel-form");
            const cancelNumbersContainer = document.getElementById("cancel-numbers-container");

            let allSelected = false; // Variável para rastrear se todos estão selecionados

            // Selecionar/deselecionar todos os checkboxes
            selectAllBtn.addEventListener("click", function() {
                allSelected = !allSelected; // Alternar estado

                checkboxes.forEach(checkbox => {
                    checkbox.checked = allSelected;
                });

                // Atualiza o texto do botão
                selectAllBtn.innerText = allSelected ? "Desselecionar Todos" : "Selecionar Todos";
            });

            // Cancelar números selecionados
            cancelButton.addEventListener("click", function() {
                const selectedNumbers = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedNumbers.length === 0) {
                    alert("Selecione pelo menos um número para cancelar.");
                    return;
                }

                if (!confirm("Tem certeza que deseja cancelar os números selecionados?")) {
                    return;
                }

                // Adiciona inputs hidden com os números selecionados ao formulário
                cancelNumbersContainer.innerHTML = "";
                selectedNumbers.forEach(number => {
                    let input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "numbers[]";
                    input.value = number;
                    cancelNumbersContainer.appendChild(input);
                });

                // Submete o formulário
                cancelForm.submit();
            });
        });
    </script>
</x-app-layout>
