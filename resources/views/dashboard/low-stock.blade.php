@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Low Stock Products</h4>
                    <p class="mb-0">This dashboard shows products that need to be restocked soon. <br>
                        Current threshold: {{ $threshold }} items or less in stock.</p>
                </div>
                <div>
                    <form action="{{ route('dashboard.low-stock') }}" method="get" class="d-flex">
                        <input type="number" name="threshold" class="form-control me-2" placeholder="Stock threshold" value="{{ $threshold }}">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No.</th>
                            <th>Product Name</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Stock</th>
                            <th>Buying Price</th>
                            <th>Selling Price</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($lowStockProducts as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->product_code }}</td>
                                <td>{{ $product->category_name }}</td>
                                <td>{{ $product->supplier_name }}</td>                                <td>
                                    <span class="badge {{ $product->current_stock <= 5 ? 'bg-danger' : 'bg-warning' }}">
                                        {{ $product->current_stock }}
                                    </span>
                                </td>
                                <td>{{ $product->buying_price }}</td>
                                <td>{{ $product->selling_price }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No low stock products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection