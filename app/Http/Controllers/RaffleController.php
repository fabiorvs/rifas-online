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
    public function create()
    {
        return view('raffles.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'description'      => trim(strip_tags($request->input('description'))), // Garante que o campo existe
            'price_per_number' => str_replace(['R$', ',', ' '], '', $request->price_per_number),
        ]);

        // Validação dos dados
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|max:5000',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

    public function confirmarNumero($id)
    {
        $numero = RaffleNumber::findOrFail($id);

        // Verifica se o número já está confirmado
        if ($numero->status === 'Confirmado') {
            return redirect()->back()->with('error', 'Este número já foi confirmado.');
        }

        $numero->status = 'Confirmado';
        $numero->save();

        // Atualiza o status da rifa
        $numero->raffle->updateStatus();

        return redirect()->back()->with('success', 'Número confirmado com sucesso!');
    }

}
