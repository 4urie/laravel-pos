@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Sales by Category</h4>
                    <p class="mb-0">This dashboard shows sales data grouped by product category. <br>
                        Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>
                <div>
                    <form action="{{ route('dashboard.sales-by-category') }}" method="get" class="d-flex">
                        <div class="input-group me-2">
                            <span class="input-group-text">From</span>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="input-group me-2">
                            <span class="input-group-text">To</span>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
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
                            <th>Category</th>
                            <th>Orders</th>
                            <th>Total Sales</th>
                            <th>Total Paid</th>
                            <th>Total Due</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($salesByCategory as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->category_name }}</td>
                                <td>{{ $category->order_count }}</td>
                                <td>{{ number_format($category->total_sales, 2) }}</td>
                                <td>{{ number_format($category->total_paid, 2) }}</td>
                                <td>{{ number_format($category->total_due, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No sales data found for this period</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection