<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <td>{{ $item->name }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $item->email }}</td>
    </tr>
    <tr>
        <th>Phone</th>
        <td>{{ $item->phone }}</td>
    </tr>
    <tr>
        <th>Delivery Service</th>
        <td>{{ $item->delivery->name}} <br> <small>{!!($item->resi) ? 'Receipt Number: <strong>'.$item->resi.'</strong>' : ''!!}</small></td>
    </tr>
    <tr>
        <th>Shipping Address</th>
        <td>{{ $item->address }}</td>
    </tr>
    <tr>
        <th>Purchase Date</th>
        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F j, Y') }}</td>
    </tr>
    <tr>
        <th>Status Product</th>
        <td style="color: {{ $item->status === 'The customer has received the order' ? '#349e5a' : '#e7973c' }}">
            <strong>{{ $item->status }}</strong> 
        </td>
    </tr>
    <tr>
        <th>Total Products</td>
        <td>
            <table class="table table-bordered w-100 mb-0">
                <tr>
                    <th>Photo</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
                @php
                    $profit = $item->stores->profit;
                @endphp
                @foreach($item->details as $detail )
                <tr>
                    <td> <img src="{{ $detail->product->galleries[0]->photo }}" width="100px;" height="auto;" alt="" /></td>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <?php
                    $basePrice = $detail->product->price + ($detail->product->price * $profit / 100);
                    $promoPrice = ($detail->product->promo_price != 0) ? $basePrice -$detail->product->promo_price : 0;
                    ?>
                    <td style="color: #e7973c;font-size:18px;"><strong> ${{($promoPrice != 0) ? number_format($promoPrice * $detail->quantity, 2) : number_format($basePrice *$detail->quantity, 2)  }}</strong></td>
                </tr>
                @endforeach
            </table>
        </td>
    </tr>
    <tr>
        <th>Tax & Shipping Cost</th>
        <td style="color: #e7973c;font-size:18px;"><strong>${{ $item->tax }}</strong> </td>
    </tr>
    <tr>
        <th>Total Payment</th>
        <td style="color: #e7973c;font-size:18px;"><strong>${{ $item->profit }}</strong> </td>
    </tr>
</table>
<div class="row">
    @if (Auth::user()->role == 2 && $item->status=='Waiting process')
        <div class="col-12"><a href="{{ route('transactions.status',$item->id) }}?status=Process" class="btn btn-success w-100"><i class="fa fa-check"></i> Confirm</a></div>
    @endif
    
    @if (Auth::user()->role == 1)
    <form action="{{ route('transactions.status', $item->id) }}" class="w-100" method="get">
        <div class="col-12 mb-1">
            <label for="status-produk" class="form-controll">Status Product</label>
            <select name="status" class="form-control" id="status-produk" required>
                <option value="The order is being prepared" {{ $item->status == 'The order is being prepared' ? 'selected' : '' }}>The order is being prepared</option>
                <option value="The order is being packed" {{ $item->status == 'The order is being packed' ? 'selected' : '' }}>The order is being packed</option>
                <option value="The order has been taken by the delivery service" {{ $item->status == 'The order has been taken by the delivery service' ? 'selected' : '' }}>The order has been taken by the delivery service</option>
                <option value="The order is in the process of being sent to the sorting center" {{ $item->status == 'The order is in the process of being sent to the sorting center' ? 'selected' : '' }}>The order is in the process of being sent to the sorting center</option>
                <option value="The order is in the delivery process" {{ $item->status == 'The order is in the delivery process' ? 'selected' : '' }}>The order is in the delivery process</option>
                <option value="The order is being delivered to the destination address" {{ $item->status == 'The order is being delivered to the destination address' ? 'selected' : '' }}>The order is being delivered to the destination address</option>
                <option value="The customer has received the order" {{ $item->status == 'The customer has received the order' ? 'selected' : '' }}>The customer has received the order</option>
            </select>

            <div class="form-group pt-3" id="receiptNumberInput" style="{{ $item->status == 'The order has been taken by the delivery service' ? '' : 'display:none' }}">
                <label for="receipt_number">Receipt Number:</label>
                <input type="text" name="resi" class="form-control" value="{{ $item->resi ?? old('resi') }}">
            </div>
        
        </div>
        <div class="col-12 pt-3">
            <button type="submit" class="btn btn-success w-100">Update Status</button>
        </div>
    </form>
    @endif
</div>

<script>
    document.getElementById('status-produk').addEventListener('change', function () {
        var receiptNumberInput = document.getElementById('receiptNumberInput');
        var selectedOption = this.options[this.selectedIndex];

        if (selectedOption.value === 'The order has been taken by the delivery service') {
            receiptNumberInput.style.display = 'block';
        } else {
            receiptNumberInput.style.display = 'none';
        }
    });
</script>
