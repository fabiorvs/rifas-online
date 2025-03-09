<?php
namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CreditLog;
use App\Models\Raffle;
use App\Models\RaffleNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RaffleController extends Controller
{

    public function show($identification)
    {
        $raffle      = Raffle::where('identification', $identification)->firstOrFail();
        $numbers     = RaffleNumber::where('raffle_id', $raffle->id)->get();
        $userNumbers = auth()->check() ? $raffle->numbers()->where('user_id', auth()->id())->pluck('id')->toArray() : [];

        return view('raffles.show', compact('raffle', 'numbers', 'userNumbers'));
    }

    public function buyNumbers(Request $request)
    {
        $request->validate([
            'numbers'   => 'required|array',
            'numbers.*' => 'exists:raffle_numbers,id',
        ]);

        foreach ($request->numbers as $numberId) {
            $number = RaffleNumber::find($numberId);
            if ($number->status == 'available') {
                $number->status  = 'reserved';
                $number->user_id = Auth::id();
                $number->save();
            }
        }

        return redirect()->back()->with('success', 'Números reservados com sucesso! Complete o pagamento.');
    }

    public function create()
    {
        return view('raffles.create');
    }

    public function store(Request $request)
    {
        $price = str_replace(['R$', '.', ','], ['', '', '.'], $request->price_per_number);
        $price = floatval($price);
        $request->merge([
            'description'      => trim(preg_replace('/<script\b[^>]*>(.*?)<\/script>|<\?(php)?(.*?)\?>/is', '', $request->input('description'))),
            'price_per_number' => $price,
        ]);

        // Validação dos dados
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|max:5000',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'total_numbers'    => 'required|integer|min:1',
            'price_per_number' => 'required|numeric|min:0',
        ]);

        // Obtém o usuário autenticado
        $user = Auth::user();

        // Buscar saldo do usuário
        $credit     = Credit::where('user_id', $user->id)->first();
        $creditCost = config('app.credit_cost_per_raffle', 1); // Pega o custo da rifa no .env ou assume 1 crédito

        // Verifica se o usuário tem créditos suficientes
        if (! $credit || $credit->balance < $creditCost) {
            return redirect()->back()->withInput()->with('error', 'Você não tem créditos suficientes para criar uma rifa.');
        }

        DB::beginTransaction();
        try {
            // Upload da imagem (se houver)
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('rifas', 'public');
            }

            // Criar a rifa
            $rifa = Raffle::create([
                'user_id'          => $user->id,
                'title'            => $validated['title'],
                'description'      => $validated['description'],
                'image'            => $imagePath,
                'total_numbers'    => $validated['total_numbers'],
                'price_per_number' => $validated['price_per_number'],
            ]);

            // Debitar créditos do usuário
            $credit->balance -= $creditCost;
            $credit->save();

            // Registrar no log de créditos
            CreditLog::create([
                'user_id'     => $user->id,
                'amount'      => -$creditCost,
                'type'        => 'Saída',
                'observation' => 'Criação da rifa: ' . $rifa->title,
            ]);

            DB::commit();

            // Gerar números automaticamente
            $this->gerarNumerosRifa($rifa->id, $request->total_numbers);
            return redirect()->route('dashboard')->with('success', 'Rifa criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Erro ao criar rifa: ' . $e->getMessage());
        }
    }

    private function gerarNumerosRifa($raffleId, $totalNumbers)
    {
        $numbers = [];

        for ($i = 1; $i <= $totalNumbers; $i++) {
            $numbers[] = [
                'raffle_id'  => $raffleId,
                'number'     => $i,
                'status'     => 'Disponível',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        RaffleNumber::insert($numbers);
    }

    public function edit($id)
    {
        $raffle = Raffle::findOrFail($id);

        // Verifica se o usuário é o dono da rifa
        if (Auth::id() !== $raffle->user_id) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para editar esta rifa.');
        }

        return view('raffles.edit', compact('raffle'));
    }

    public function update(Request $request, $id)
    {
        $raffle = Raffle::findOrFail($id);

        // Verifica se o usuário é o dono da rifa
        if (Auth::id() !== $raffle->user_id) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para editar esta rifa.');
        }

        $request->merge([
            'description' => trim(preg_replace('/<script\b[^>]*>(.*?)<\/script>|<\?(php)?(.*?)\?>/is', '', $request->input('description'))),
        ]);

        // Validação dos dados
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Atualiza os dados permitidos
        $raffle->title       = $request->title;
        $raffle->description = $request->description;

        // Atualiza a imagem se um novo arquivo for enviado
        if ($request->hasFile('image')) {
            $raffle->image = $request->file('image')->store('rifas', 'public');
        }

        $raffle->save();

        return redirect()->route('dashboard')->with('success', 'Rifa atualizada com sucesso!');
    }

    public function overview($id)
    {
        // Buscar a rifa com os números e usuários
        $raffle = Raffle::with('numbers.user')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Contar números por status
        $confirmed = $raffle->numbers->where('status', 'Confirmado')->count();
        $reserved  = $raffle->numbers->where('status', 'Reservado')->count();
        $available = $raffle->total_numbers - ($confirmed + $reserved);

        // Buscar o ganhador caso a rifa tenha sido sorteada
        $winner = null;
        if ($raffle->status === 'Sorteada' && $raffle->winning_number) {
            $winner = $raffle->numbers->where('number', $raffle->winning_number)->first();
        }

        return view('raffles.overview', compact('raffle', 'confirmed', 'reserved', 'available', 'winner'));
    }

    public function drawPage($id)
    {
        $raffle = Raffle::findOrFail($id);

        // Garantir que a rifa está no status correto
        if ($raffle->status !== 'Aguardando Sorteio') {
            return redirect()->route('raffles.my')->with('error', 'Esta rifa ainda não está pronta para o sorteio.');
        }

        return view('raffles.draw', compact('raffle'));
    }

    public function performDraw($id)
    {
        $raffle = Raffle::findOrFail($id);

        if ($raffle->status !== 'Aguardando Sorteio') {
            return redirect()->route('raffles.my')->with('error', 'Esta rifa ainda não está pronta para o sorteio.');
        }

        // Sortear um número aleatório entre os confirmados
        $winningNumber = RaffleNumber::where('raffle_id', $raffle->id)
            ->where('status', 'Confirmado')
            ->inRandomOrder()
            ->first();

        if (! $winningNumber) {
            return redirect()->route('raffles.draw', $raffle->id)->with('error', 'Nenhum número confirmado para sorteio.');
        }

        // Atualizar a rifa com o número sorteado
        $raffle->update([
            'status'         => 'Sorteada',
            'winning_number' => $winningNumber->number,
        ]);

        return response()->json(['winningNumber' => $winningNumber->number]);
    }

}
