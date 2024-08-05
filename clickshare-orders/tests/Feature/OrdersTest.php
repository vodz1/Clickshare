<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use Livewire\Livewire;

class OrdersTest extends TestCase
{
    public function test_search_orders_by_phone_number()
    {
        $order = Order::factory()->create(['phone_number' => '1234567890']);

        Livewire::test('orders')
            ->set('searchPhone', '1234567890')
            ->call('search')
            ->assertSee($order->client_name)
            ->assertSee($order->phone_number);
    }


    public function test_search_orders_by_client_name()
    {
        $order = Order::factory()->create(['client_name' => 'John Doe']);

        Livewire::test('orders')
            ->set('searchClient', 'John Doe')
            ->call('search')
            ->assertSee($order->id)
            ->assertSee($order->phone_number);
    }

    public function test_no_orders_found_message()
    {
        Livewire::test('orders')
            ->set('searchPhone', 'nonexistent')
            ->call('search')
            ->assertSee('No Records Found');
    }

    public function test_validation_errors()
    {
        Livewire::test('orders')
            ->set('searchPhone', str_repeat('a', 16)) 
            ->call('search')
            ->assertHasErrors(['searchPhone']);
        
        Livewire::test('orders')
            ->set('searchClient', str_repeat('a', 21))
            ->call('search')
            ->assertHasErrors(['searchClient']);
    }
}

