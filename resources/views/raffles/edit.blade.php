<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Rifa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('raffles.update', $raffle->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <label class="block text-gray-700">Título da Rifa</label>
                    <input type="text" name="title" value="{{ old('title', $raffle->title) }}" required
                        class="w-full border p-2 rounded mb-4">
                    @error('title')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <label class="block text-gray-700">Descrição</label>
                    <textarea id="description" name="description" class="w-full border p-2 rounded mb-4">{{ old('description', $raffle->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <label class="block text-gray-700">Detalhes do Pagamento</label>
                    <textarea id="payment_details" name="payment_details" class="w-full border p-2 rounded mb-4">{{ old('payment_details', $raffle->payment_details) }}</textarea>
                    @error('payment_details')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <label class="block text-gray-700">Imagem</label>
                    <input type="file" name="image" class="w-full border p-2 rounded mb-4">

                    @if ($raffle->image)
                        <img src="{{ asset('storage/' . $raffle->image) }}" alt="Imagem da Rifa"
                            class="mb-4 w-32 rounded">
                    @endif

                    @error('image')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Atualizar Rifa
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
                    menubar: true, // Ativa o menu superior
                    plugins: 'advlist autolink lists link charmap print preview anchor',
                    toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist outdent indent | removeformat',
                    skin: 'oxide-dark', // Modo escuro para o editor
                    content_css: '/css/tinymce-style.css', // Estilos externos

                    // Adiciona os estilos personalizados de cabeçalhos
                    style_formats: [{
                            title: 'Título H1',
                            format: 'h1'
                        },
                        {
                            title: 'Título H2',
                            format: 'h2'
                        },
                        {
                            title: 'Título H3',
                            format: 'h3'
                        },
                        {
                            title: 'Parágrafo',
                            format: 'p'
                        },
                        {
                            title: 'Citação',
                            format: 'blockquote'
                        },
                    ],

                    // Garante que os estilos sejam aplicados
                    formats: {
                        h1: {
                            block: 'h1',
                            classes: 'h1-custom'
                        },
                        h2: {
                            block: 'h2',
                            classes: 'h2-custom'
                        },
                        h3: {
                            block: 'h3',
                            classes: 'h3-custom'
                        },
                        p: {
                            block: 'p',
                            classes: 'p-custom'
                        },
                        blockquote: {
                            block: 'blockquote',
                            classes: 'blockquote-custom'
                        }
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                tinymce.init({
                    selector: '#payment_details',
                    height: 300,
                    menubar: true, // Ativa o menu superior
                    plugins: 'advlist autolink lists link charmap print preview anchor',
                    toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist outdent indent | removeformat',
                    skin: 'oxide-dark', // Modo escuro para o editor
                    content_css: '/css/tinymce-style.css', // Estilos externos

                    // Adiciona os estilos personalizados de cabeçalhos
                    style_formats: [{
                            title: 'Título H1',
                            format: 'h1'
                        },
                        {
                            title: 'Título H2',
                            format: 'h2'
                        },
                        {
                            title: 'Título H3',
                            format: 'h3'
                        },
                        {
                            title: 'Parágrafo',
                            format: 'p'
                        },
                        {
                            title: 'Citação',
                            format: 'blockquote'
                        },
                    ],

                    // Garante que os estilos sejam aplicados
                    formats: {
                        h1: {
                            block: 'h1',
                            classes: 'h1-custom'
                        },
                        h2: {
                            block: 'h2',
                            classes: 'h2-custom'
                        },
                        h3: {
                            block: 'h3',
                            classes: 'h3-custom'
                        },
                        p: {
                            block: 'p',
                            classes: 'p-custom'
                        },
                        blockquote: {
                            block: 'blockquote',
                            classes: 'blockquote-custom'
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
