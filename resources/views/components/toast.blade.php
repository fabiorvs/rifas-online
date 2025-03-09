<div x-data="{ show: false, message: '', type: 'success' }" x-init="@if (session('success')) show = true; message = '{{ session('success') }}'; type = 'success';
        @elseif (session('error'))
            show = true; message = '{{ session('error') }}'; type = 'error'; @endif
setTimeout(() => show = false, 4000);" x-show="show" x-transition
    class="fixed top-5 right-5 px-4 py-3 rounded shadow-lg text-white"
    :class="{
        'bg-green-500': type === 'success',
        'bg-red-500': type === 'error'
    }">
    <span x-text="message"></span>
</div>
