<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;

use Livewire\Component;
use App\Models\Order;

class Orders extends Component
{
    public $searchPhone = '';
    public $searchClient = '';
    
    public function render()
    {

        Log::info('Searching Orders', ['searchPhone' => $this->searchPhone, 'searchClient' => $this->searchClient]);

        $orders = Order::where('phone_number', 'like', '%'.$this->searchPhone.'%')
        ->where('client_name', 'like', '%'.$this->searchClient.'%')
        ->get();

        return view('livewire.orders', ['orders' => $orders]); 

    }
}
