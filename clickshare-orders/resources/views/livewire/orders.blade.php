
<div>
    <input type="text" wire:model="searchPhone" class="form-control mb-3" placeholder="Search by phone number">
    @error('searchPhone') <span class="text-danger">{{ $message }}</span> @enderror

    <input type="text" wire:model="searchClient" class="form-control mb-3" placeholder="Search by client name">
    @error('searchClient') <span class="text-danger">{{ $message }}</span> @enderror

    <button wire:click='search'>Search</button>
    
    <table class="table table-striped table-bordered">
        <thead class="table-light">
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
    @if ($orders->isEmpty())
        <h3>No Records Found</h3>
    @endif
</div>


{{-- <script>
function filterOrders() {
    const searchPhone = document.getElementById('searchPhone').value.toLowerCase();
    const searchClient = document.getElementById('searchClient').value.toLowerCase();
    const rows = document.querySelectorAll('.order-row');

    rows.forEach(row => {
        const phoneCell = row.cells[2].innerText.toLowerCase(); // Phone Number
        const clientCell = row.cells[1].innerText.toLowerCase(); // Client Name

        const phoneMatch = phoneCell.includes(searchPhone);
        const clientMatch = clientCell.includes(searchClient);

        if (phoneMatch && clientMatch) {
            row.style.display = ''; 
        } else {
            row.style.display = 'none'; 
        }
    });
}
</script>
@endsection --}} 
