@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Price History for {{ $product->product_name }}</h4>
                    <p class="mb-0">View the complete price change history for this product. <br>
                       Product Code: {{ $product->product_code }}</p>
                </div>
                <div>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary add-list"><i class="fa-solid fa-eye mr-3"></i>View Product</a>
                    <a href="{{ route('price-logs.index') }}" class="btn btn-info add-list"><i class="fa-solid fa-list mr-3"></i>All Price Logs</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Current Price Information</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" value="{{ $product->product_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code">Product Code</label>
                                <input type="text" class="form-control" id="product_code" value="{{ $product->product_code }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="buying_price">Current Buying Price</label>
                                <input type="text" class="form-control" id="buying_price" value="{{ number_format($product->buying_price, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selling_price">Current Selling Price</label>
                                <input type="text" class="form-control" id="selling_price" value="{{ number_format($product->selling_price, 2) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-4">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No.</th>
                            <th>Old Buying Price</th>
                            <th>New Buying Price</th>
                            <th>Old Selling Price</th>
                            <th>New Selling Price</th>
                            <th>Changed By</th>
                            <th>Changed At</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($priceLogs as $index => $log)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ number_format($log->old_buying_price, 2) }}</td>
                            <td>{{ number_format($log->new_buying_price, 2) }}</td>
                            <td>{{ number_format($log->old_selling_price, 2) }}</td>
                            <td>{{ number_format($log->new_selling_price, 2) }}</td>
                            <td>{{ $log->user_name ?? 'Unknown' }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('d M Y, H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No price change history found for this product.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>

@endsection 