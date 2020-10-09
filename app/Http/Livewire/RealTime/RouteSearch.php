<?php

namespace App\Http\Livewire\RealTime;

use App\Models\Route;
use Livewire\Component;

class RouteSearch extends Component
{
    public $search = '';

    public function getRoutesProperty()
    {
        if (! $this->search) {
            return null;
        }

        return Route::main()
            ->where(function ($query) {
                $query->where('short_name', 'ILIKE', "%{$this->search}%")
                      ->orWhere('long_name', 'ILIKE', "%{$this->search}%");
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.real-time.route-search');
    }
}
