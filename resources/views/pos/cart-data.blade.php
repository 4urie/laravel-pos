@php
    use Gloudemans\Shoppingcart\Facades\Cart;
@endphp

<table class="table">
    <thead>
        <tr class="ligth">
            <th scope="col">Name</th>
            <th scope="col">QTY</th>
            <th scope="col">Price</th>
            <th scope="col">SubTotal</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productItem as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td style="min-width: 140px;">
                <form action="{{ route('pos.updateCart', $item->rowId) }}" method="POST" class="qty-update-form">
                    @csrf
                    <div class="input-group">
                        <input type="number" class="form-control" name="qty" required value="{{ $item->qty }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success border-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sumbit"><i class="fas fa-check"></i></button>
                        </div>
                    </div>
                </form>
            </td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->subtotal }}</td>
            <td>
                <a href="#" class="btn btn-danger border-none delete-cart-item" data-rowid="{{ $item->rowId }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa-solid fa-trash mr-0"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="container row text-center">
    <div class="form-group col-sm-6">
        <p class="h4 text-primary">Quantity: <span id="cart-count">{{ Cart::count() }}</span></p>
    </div>
    <div class="form-group col-sm-6">
        <p class="h4 text-primary">Subtotal: <span id="cart-subtotal">{{ Cart::subtotal() }}</span></p>
    </div>
    <div class="form-group col-sm-6">
        <p class="h4 text-primary">Vat: <span id="cart-tax">{{ Cart::tax() }}</span></p>
    </div>
    <div class="form-group col-sm-6">
        <p class="h4 text-primary">Total: <span id="cart-total">{{ Cart::total() }}</span></p>
    </div>
</div> 