<?php
namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Raffle;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Buscar saldo do usuário
        $credit        = Credit::where('user_id', $user->id)->first();
        $creditBalance = $credit ? $credit->balance : 0;

        // Buscar rifas do usuário e calcular os totais de números
        $raffles = Raffle::where('user_id', $user->id)
            ->withCount([
                'numbers as total_disponiveis' => function ($query) {
                    $query->where('status', 'Disponível');
                },
                'numbers as total_reservados'  => function ($query) {
                    $query->where('status', 'Reservado');
                },
                'numbers as total_confirmados' => function ($query) {
                    $query->where('status', 'Confirmado');
                },
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('user', 'creditBalance', 'raffles'));
    }
}
