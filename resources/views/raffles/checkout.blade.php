<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ $raffle->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 flex items-center justify-center min-h-screen p-4 text-white">

    <div class="max-w-3xl w-full bg-gray-800 p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-4">Checkout da Rifa: {{ $raffle->title }}</h2>

        <div class="mb-4">
            <p class="text-lg font-semibold">Seus Números Reservados:</p>
            <div class="grid grid-cols-5 gap-2 mt-2">
                @foreach ($reservedNumbers as $num)
                    <div class="p-3 text-center font-bold text-white bg-orange-500 rounded-md shadow">
                        <p class="text-lg">{{ $num->number }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="text-lg font-semibold mt-4">Código da Transação:
            <span class="text-green-600 font-bold">{{ $transactionCode }}</span>
        </p>

        <p class="text-lg font-semibold mt-4">Total a pagar:
            <span class="text-green-600 font-bold">R$ {{ number_format($totalPrice, 2, ',', '.') }}</span>
        </p>

        @if ($raffle->payment_details)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-4 rounded">
                <p class="font-semibold">📢 Instruções de Pagamento:</p>
                <p>{!! $raffle->payment_details !!}</p>
            </div>
        @endif

        <div class="mt-6 text-center">
            <a href="{{ route('raffle.show', $raffle->identification) }}"
                class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-lg hover:bg-blue-700 transition">
                Voltar para a Rifa
            </a>
        </div>
    </div>

</body>

</html>
