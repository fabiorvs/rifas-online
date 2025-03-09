<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Nova Rifa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('raffles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label class="block text-gray-700">Título da Rifa</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full border p-2 rounded mb-4">
                    @error('title')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Editor SimpleMDE -->
                    <label class="block text-gray-700">Descrição</label>
                    <textarea id="description" name="description" class="w-full border p-2 rounded mb-4">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Upload de Imagem -->
                    <label class="block text-gray-700">Imagem da Rifa</label>
                    <input type="file" name="image" class="w-full border p-2 rounded mb-4" accept="image/*">
                    @error('image')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <label class="block text-gray-700">Quantidade de Números</label>
                    <input type="number" name="total_numbers" value="{{ old('total_numbers') }}" required
                        class="w-full border p-2 rounded mb-4">
                    @error('total_numbers')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <label class="block text-gray-700">Valor por Número (R$)</label>
                    <div x-data="{ price: '{{ old('price_per_number') }}' }">
                        <input type="hidden" name="price_per_number" x-model="price">
                        <input type="text" x-model="price" x-on:input="price = formatCurrency($event.target.value)"
                            placeholder="R$ 0,00" class="w-full border p-2 rounded mb-4">
                    </div>
                    @error('price_per_number')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Criar Rifa
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                tinymce.init({
                    selector: '#description',
                    height: 300,
                    menubar: false,
                    plugins: 'advlist autolink lists link charmap print preview anchor',
                    toolbar: 'undo redo | bold italic underline | bullist numlist outdent indent | removeformat'
                });
            });

            function formatCurrency(value) {
                value = value.replace(/\D/g, ""); // Remove tudo que não for número
                value = (value / 100).toFixed(2) + ""; // Converte para decimal
                value = value.replace(".", ","); // Troca ponto por vírgula
                return "R$ " + value; // Adiciona símbolo de real
            }
        </script>
    @endpush
</x-app-layout>
