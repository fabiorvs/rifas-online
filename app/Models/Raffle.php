<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'payment_details',
        'winning_number',
        'identification',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($raffle) {
            if (empty($raffle->identification)) {
                $raffle->identification = strtoupper(Str::random(6));
            }
        });
    }

    //Relacionamento com os números da rifa
    public function numbers()
    {
        return $this->hasMany(RaffleNumber::class);
    }

    //Atualiza o status automaticamente
    public function updateStatus()
    {
        $totalNumeros       = $this->total_numbers;
        $numerosConfirmados = $this->numbers()->where('status', 'Confirmado')->count();

        if ($numerosConfirmados === $totalNumeros && $this->status !== 'Aguardando Sorteio') {
            $this->status = 'Aguardando Sorteio';
            $this->save();
        }
    }
}
