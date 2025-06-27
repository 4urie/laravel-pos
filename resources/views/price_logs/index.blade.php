@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Price Change Logs</h4>
                    <p class="mb-0">View the history of all product price changes in your inventory. <br>
                       This log helps track when and by whom product prices were modified.</p>
                </div>
                <div>
                    <a href="{{ route('price-logs.index') }}" class="btn btn-danger add-list"><i class="fa-solid fa-trash mr-3"></i>Clear Search</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('price-logs.index') }}" method="get">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="form-group row">
                        <label for="row" class="col-sm-3 align-self-center">Row:</label>
                        <div class="col-sm-9">
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
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" id="search" class="form-control" name="search" placeholder="Search product or user" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text bg-primary"><i class="fa-solid fa-magnifying-glass font-size-20"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No.</th>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Old Buying Price</th>
                            <th>New Buying Price</th>
                            <th>Old Selling Price</th>
                            <th>New Selling Price</th>
                            <th>Changed By</th>
                            <th>Changed At</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($priceLogs as $log)
                        <tr>
                            <td>{{ (($priceLogs->currentPage() * 10) - 10) + $loop->iteration  }}</td>
                            <td>{{ $log->product_name }}</td>
                            <td>{{ $log->product_code }}</td>
                            <td>{{ number_format($log->old_buying_price, 2) }}</td>
                            <td>{{ number_format($log->new_buying_price, 2) }}</td>
                            <td>{{ number_format($log->old_selling_price, 2) }}</td>
                            <td>{{ number_format($log->new_selling_price, 2) }}</td>
                            <td>{{ $log->user_name ?? 'Unknown' }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('d M Y, H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No price change logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $priceLogs->links() }}
        </div>
    </div>
    <!-- Page end  -->
</div>

@endsection 