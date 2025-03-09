<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image',
        'total_numbers',
        'price_per_number',
        'status',
        'winning_number',
    ];

    public function numbers()
    {
        return $this->hasMany(RaffleNumber::class);
    }

    public function updateStatus()
    {
        $totalNumeros       = $this->total_numbers;
        $numerosConfirmados = $this->numbers()->where('status', 'Confirmado')->count();

        if ($numerosConfirmados === $totalNumeros) {
            $this->status = 'Aguardando Sorteio';
            $this->save();
        }
    }

}
