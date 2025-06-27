@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div id="success-message-container">
                @if (session()->has('success'))
                    <div class="alert text-white bg-success" role="alert">
                        <div class="iq-alert-text">{{ session('success') }}</div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="ri-close-line"></i>
                        </button>
                    </div>
                @endif
            </div>
            <div>
                <h4 class="mb-3">Point of Sale</h4>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 mb-3">
            <div id="cart-container">
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
                                        <input type="number" class="form-control" name="qty" required value="{{ old('qty', $item->qty) }}">
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
            </div>

            <form action="{{ route('pos.createInvoice') }}" method="POST">
                @csrf
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="input-group">
                            <select class="form-control" id="customer_id" name="customer_id">
                                <option selected="" disabled="">-- Select Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('customer_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-center">
                            <a href="{{ route('customers.create') }}" class="btn btn-primary add-list mx-1">Add Customer</a>
                            <button type="submit" class="btn btn-success add-list mx-1">Create Invoice</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <form action="#" method="get">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="form-group row">
                                <label for="row" class="align-self-center mx-2">Row:</label>
                                <div>
                                    <select class="form-control" name="row">
                                        <option value="10" @if(request('row') == '10')selected="selected"@endif>10</option>
                                        <option value="25" @if(request('row') == '25')selected="selected"@endif>25</option>
                                        <option value="50" @if(request('row') == '50')selected="selected"@endif>50</option>
                                        <option value="100" @if(request('row') == '100')selected="selected"@endif>100</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-3 align-self-center" for="search">Search:</label>
                                <div class="input-group col-sm-8">
                                    <input type="text" id="search" class="form-control" name="search" placeholder="Search product" value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text bg-primary"><i class="fa-solid fa-magnifying-glass font-size-20"></i></button>
                                        <a href="{{ route('products.index') }}" class="input-group-text bg-danger"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                    <div class="table-responsive rounded mb-3 border-none">
                        <table class="table mb-0">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>No.</th>
                                    <th>Photo</th>
                                    <th>@sortablelink('product_name', 'name')</th>
                                    <th>@sortablelink('selling_price', 'price')</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                @forelse ($products as $product)
                                <tr>
                                    <td>{{ (($products->currentPage() * 10) - 10) + $loop->iteration  }}</td>
                                    <td>
                                        <img class="avatar-60 rounded" src="{{ $product->product_image ? asset('storage/products/'.$product->product_image) : asset('assets/images/product/default.webp') }}">
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->selling_price }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary border-none add-to-cart-btn" 
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->product_name }}"
                                            data-price="{{ $product->selling_price }}"
                                            data-toggle="tooltip" 
                                            data-placement="top" 
                                            title="" 
                                            data-original-title="Add">
                                            <i class="far fa-plus mr-0"></i>
                                        </button>
                                    </td>
                                </tr>

                                @empty
                                <div class="alert text-white bg-danger" role="alert">
                                    <div class="iq-alert-text">Data not Found.</div>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Add to cart with AJAX
        $('.add-to-cart-btn').on('click', function() {
            let button = $(this);
            button.prop('disabled', true);
            
            let productId = $(this).data('id');
            let productName = $(this).data('name');
            let productPrice = $(this).data('price');

            $.ajax({
                url: "{{ route('pos.addCart') }}",
                type: "POST",
                data: {
                    id: productId,
                    name: productName,
                    price: productPrice
                },
                success: function(response) {
                    // Show success message
                    $('#success-message-container').html(
                        '<div class="alert text-white bg-success" role="alert">' +
                            '<div class="iq-alert-text">Product has been added!</div>' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<i class="ri-close-line"></i>' +
                            '</button>' +
                        '</div>'
                    );
                    
                    // Update cart display
                    refreshCart();
                    
                    // Re-enable button
                    button.prop('disabled', false);
                    
                    // Reset customer selection
                    $('#customer_id').val('');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    button.prop('disabled', false);
                    
                    $('#success-message-container').html(
                        '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">Error adding product!</div>' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<i class="ri-close-line"></i>' +
                            '</button>' +
                        '</div>'
                    );
                }
            });
        });

        // Update quantity with AJAX
        $(document).on('submit', '.qty-update-form', function(e) {
            e.preventDefault();
            let form = $(this);
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Show success message
                    $('#success-message-container').html(
                        '<div class="alert text-white bg-success" role="alert">' +
                            '<div class="iq-alert-text">Cart has been updated!</div>' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<i class="ri-close-line"></i>' +
                            '</button>' +
                        '</div>'
                    );
                    
                    // Update cart display
                    refreshCart();
                    
                    // Reset customer selection
                    $('#customer_id').val('');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        // Delete cart item with AJAX
        $(document).on('click', '.delete-cart-item', function(e) {
            e.preventDefault();
            let rowId = $(this).data('rowid');
            
            $.ajax({
                url: "{{ url('/pos/delete') }}/" + rowId,
                type: 'GET',
                success: function(response) {
                    // Show success message
                    $('#success-message-container').html(
                        '<div class="alert text-white bg-success" role="alert">' +
                            '<div class="iq-alert-text">Item has been removed!</div>' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<i class="ri-close-line"></i>' +
                            '</button>' +
                        '</div>'
                    );
                    
                    // Update cart display
                    refreshCart();
                    
                    // Reset customer selection
                    $('#customer_id').val('');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        // Function to refresh cart
        function refreshCart() {
            $.ajax({
                url: "{{ route('pos.getCartData') }}",
                type: 'GET',
                success: function(response) {
                    // Update cart items and totals
                    $('#cart-container').html(response.cartHtml);
                    $('#cart-count').text(response.count);
                    $('#cart-subtotal').text(response.subtotal);
                    $('#cart-tax').text(response.tax);
                    $('#cart-total').text(response.total);
                }
            });
        }
    });
</script>
@endsection
