<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Log; 


class Orders extends Component
{
    public $searchPhone = '';
    public $searchClient = '';
    public $orders = [];

    protected $rules = [
        'searchPhone' => 'nullable|string|max:15', 
        'searchClient' => 'nullable|string|max:20',
    ];


    public function mount()
    {
        $this->orders = Order::all(); 
    }

    public function search()
    {
        $this->validate();

        Log::info('Searching with:', ['phone' => $this->searchPhone, 'client' => $this->searchClient]);
    
        if (empty($this->searchPhone) && empty($this->searchClient)) {
            $this->orders = Order::all();
            return;
        }elseif (!empty($this->searchPhone)) {
            $this->orders = Order::where('phone_number', 'like', $this->searchPhone . '%')->get();
        }elseif (!empty($this->searchClient)) {
            $this->orders = Order::where('client_name', 'like', $this->searchClient . '%')->get();
        }
    
    
        foreach ($this->orders as $order) {
            Log::info('Order found:', [
                'id' => $order->id,
                'client_name' => $order->client_name,
                'phone_number' => $order->phone_number,
                'product_code' => $order->product_code,
                'final_price' => $order->final_price,
                'quantity' => $order->quantity,
            ]);
        }
    
    }
    

    public function render()
    {
        return view('livewire.orders', ['orders' => $this->orders]);
    }
}
