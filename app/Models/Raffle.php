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
        'status',
        'winning_number',
    ];

    public function numbers()
    {
        return $this->hasMany(RaffleNumber::class);
    }
}
