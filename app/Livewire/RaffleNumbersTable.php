<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RaffleNumber;

class RaffleNumbersTable extends Component
{
    use WithPagination;

    public $raffleId;
    public $search = ''; // Input do usuário
    public $query = ''; // Armazena o termo de busca antes da pesquisa
    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        // Reseta a paginação quando a busca muda
        $this->resetPage();
    }

    public function mount($raffleId)
    {
        $this->raffleId = $raffleId;
    }

    public function searchNumbers()
    {
        // Atualiza o termo de busca apenas ao clicar no botão
        $this->search = $this->query;
        $this->resetPage();
    }

    public function render()
    {
        $numbers = RaffleNumber::where('raffle_id', $this->raffleId)
            ->with('user')
            ->when($this->search, function ($query) {
                $query->where('number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('number')
            ->paginate(20);

        return view('livewire.raffle-numbers-table', compact('numbers'));
    }
}
