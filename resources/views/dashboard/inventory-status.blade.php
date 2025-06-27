@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Inventory Status</h4>
                    <p class="mb-0">This dashboard shows the current status of your inventory, <br> 
                       highlighting products that need attention.</p>
                </div>
                <div>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="ri-add-line"></i> Manage Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Summary Cards -->
        <div class="col-lg-12 mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-info-light">
                                    <i class="ri-stack-line"></i>
                                </div>
                                <div>
                                    <p class="mb-2">Total Products</p>
                                    <h4>{{ $totalProducts }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-danger-light">
                                    <i class="ri-alert-line"></i>
                                </div>
                                <div>
                                    <p class="mb-2">Low Stock</p>
                                    <h4>{{ $lowStockCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-warning-light">
                                    <i class="ri-error-warning-line"></i>
                                </div>
                                <div>
                                    <p class="mb-2">Critical Stock</p>
                                    <h4>{{ $criticalStockCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-warning-light">
                                    <i class="ri-calendar-event-line"></i>
                                </div>
                                <div>
                                    <p class="mb-2">Expiring Soon</p>
                                    <h4>{{ $expiringSoonCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Status Table -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Inventory Status</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                            <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton" data-toggle="dropdown">
                                Filter <i class="ri-arrow-down-s-line ml-1"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right shadow-none" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('dashboard.inventory-status', ['filter' => 'all']) }}">All</a>
                                <a class="dropdown-item" href="{{ route('dashboard.inventory-status', ['filter' => 'low_stock']) }}">Low Stock</a>
                                <a class="dropdown-item" href="{{ route('dashboard.inventory-status', ['filter' => 'critical_stock']) }}">Critical Stock</a>
                                <a class="dropdown-item" href="{{ route('dashboard.inventory-status', ['filter' => 'expiring_soon']) }}">Expiring Soon</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Code</th>
                                    <th>Category</th>
                                    <th>Supplier</th>
                                    <th>Current Stock</th>
                                    <th>Buying Price</th>
                                    <th>Selling Price</th>
                                    <th>Stock Status</th>
                                    <th>Expiry Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventoryStatus as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->product_code }}</td>
                                    <td>{{ $item->category_name }}</td>
                                    <td>{{ $item->supplier_name }}</td>
                                    <td>{{ $item->current_stock }}</td>
                                    <td>₱{{ $item->buying_price }}</td>
                                    <td>₱{{ $item->selling_price }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $item->stock_status == 'In Stock' ? 'bg-success' : 
                                              ($item->stock_status == 'Low Stock' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $item->stock_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->expiry_status == 'Valid' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $item->expiry_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center list-action">
                                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                                href="{{ route('products.edit', $item->id) }}"><i class="ri-pencil-line mr-0"></i></a>
                                            <a class="badge bg-primary mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"
                                                href="{{ route('products.show', $item->id) }}"><i class="ri-eye-line mr-0"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No inventory data found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 