<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaffleNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'raffle_id',
        'user_id',
        'number',
        'status',
    ];

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
