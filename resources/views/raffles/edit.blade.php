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
                    menubar: false,
                    plugins: 'advlist autolink lists link charmap print preview anchor',
                    toolbar: 'undo redo | bold italic underline | bullist numlist outdent indent | removeformat'
                });
            });
        </script>
    @endpush
</x-app-layout>
