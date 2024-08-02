@extends('components.layouts.app')

@section('content')
    <div>
        <input type="text" wire:model="searchPhone" placeholder="Search by phone number">
        <input type="text" wire:model="searchClient" placeholder="Search by client name">

        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Client Name</th>
                    <th>Phone Number</th>
                    <th>Product Code</th>
                    <th>Final Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->client_name }}</td>
                        <td>{{ $order->phone_number }}</td>
                        <td>{{ $order->product_code }}</td>
                        <td>{{ $order->final_price }}</td>
                        <td>{{ $order->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
