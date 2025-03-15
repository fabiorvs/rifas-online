<?php
namespace App\Http\Controllers;

use App\Models\Raffle;
use App\Models\RaffleNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RaffleNumberController extends Controller
{
    public function numerosConfirmar()
    {
        // Buscar números reservados em rifas criadas pelo usuário logado
        $raffleNumbers = RaffleNumber::whereHas('raffle', function ($query) {
            $query->where('user_id', Auth::id()); // Rifas que o usuário criou
        })
            ->where('status', 'Reservado') // Apenas números que ainda não foram confirmados
            ->with(['raffle', 'user'])     // Carrega os dados da rifa e do usuário que comprou o número
            ->get();

        return view('numbers.to_confirm', compact('raffleNumbers'));
    }

    public function confirmarPagamento(Request $request)
    {
        // Validar se o usuário selecionou números para confirmar
        $request->validate([
            'numbers'   => 'required|array',
            'numbers.*' => 'exists:raffle_numbers,id',
        ]);

        // Buscar os números selecionados
        $numbers = RaffleNumber::whereIn('id', $request->numbers)
            ->whereHas('raffle', function ($query) {
                $query->where('user_id', Auth::id()); // Apenas números das rifas do usuário logado
            })
            ->get();

        // Atualizar status dos números para "Confirmado"
        foreach ($numbers as $number) {
            $number->update(['status' => 'Confirmado']);
        }

        // Verificar se todas as rifas associadas aos números já estão confirmadas
        $raffleIds = $numbers->pluck('raffle_id')->unique();

        foreach ($raffleIds as $raffleId) {
            $raffle = Raffle::find($raffleId);

            if ($raffle) {
                $totalNumbers     = $raffle->total_numbers;
                $confirmedNumbers = RaffleNumber::where('raffle_id', $raffleId)
                    ->where('status', 'Confirmado')
                    ->count();

                // Se todos os números forem confirmados, mudar status da rifa para "Aguardando Sorteio"
                if ($confirmedNumbers == $totalNumbers) {
                    $raffle->update(['status' => 'Aguardando Sorteio']);
                }
            }
        }

        return redirect()->route('raffle_numbers.to_confirm')->with('success', 'Pagamentos confirmados com sucesso!');
    }

    public function cancelarReservas(Request $request)
    {
        $request->validate([
            'numbers'   => 'required|array',
            'numbers.*' => 'exists:raffle_numbers,id',
        ]);

        // Buscar os números selecionados
        $raffleNumbers = RaffleNumber::whereIn('id', $request->numbers)->get();

        // Verifica se todos os números pertencem a rifas do usuário logado
        foreach ($raffleNumbers as $raffleNumber) {
            if ($raffleNumber->raffle->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Você não tem permissão para cancelar uma ou mais reservas.');
            }

            // Impede o cancelamento de números já confirmados
            if ($raffleNumber->status === 'Confirmado') {
                return redirect()->back()->with('error', 'Um ou mais números já foram confirmados e não podem ser cancelados.');
            }
        }

        // Executa a atualização dentro de uma transação
        DB::transaction(function () use ($raffleNumbers) {
            foreach ($raffleNumbers as $raffleNumber) {
                $raffleNumber->update([
                    'status'           => 'Disponível',
                    'user_id'          => null,
                    'transaction_code' => null, // Remove o código da transação
                    'uuid'             => null, // Remove o UUID para nova atribuição no futuro
                ]);
            }
        });

        return redirect()->route('raffle_numbers.to_confirm')
            ->with('success', 'Reservas canceladas e números liberados.');
    }

    public function myRaffles()
    {
        // Buscar rifas em que o usuário comprou números
        $raffles = Raffle::whereHas('numbers', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['numbers' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('raffles.my', compact('raffles'));
    }

    public function myNumbers($id)
    {
        // Buscar a rifa
        $raffle = Raffle::with('numbers')->findOrFail($id);

        // Buscar os números comprados pelo usuário nessa rifa
        $numbers = RaffleNumber::where('raffle_id', $id)
            ->where('user_id', Auth::id())
            ->orderBy('number')
            ->get();

        return view('raffles.my_numbers', compact('raffle', 'numbers'));
    }

}
