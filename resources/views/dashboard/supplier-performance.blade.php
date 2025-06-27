@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Supplier Performance Analysis</h3>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $supplierData->count() }}</h3>
                                    <p>Total Suppliers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $supplierData->sum('total_products_supplied') }}</h3>
                                    <p>Total Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>₱{{ number_format($supplierData->sum('total_inventory_value'), 2) }}</h3>
                                    <p>Total Inventory Value</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $supplierData->sum('low_stock_products') }}</h3>
                                    <p>Low Stock Products</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Performance Chart -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Supplier Performance Metrics</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="supplierPerformanceChart"></canvas> <!-- Canvas for the chart -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Performance Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Contact</th>
                                    <th>Products Supplied</th>
                                    <th>Total Stock</th>
                                    <th>Inventory Value</th>
                                    <th>Low Stock Items</th>
                                    <th>Expiring Soon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplierData as $supplier)
                                <tr>
                                    <td>{{ $supplier->supplier_name }}</td>
                                    <td>
                                        {{ $supplier->email }}<br>
                                        {{ $supplier->phone }}
                                    </td>
                                    <td>{{ $supplier->total_products_supplied }}</td>
                                    <td>{{ $supplier->total_stock_supplied }}</td>
                                    <td>₱{{ number_format($supplier->total_inventory_value, 2) }}</td>
                                    <td>
                                        @if($supplier->low_stock_products > 0)
                                            <span class="badge bg-danger">{{ $supplier->low_stock_products }}</span>
                                        @else
                                            <span class="badge bg-success">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($supplier->products_expiring_soon > 0)
                                            <span class="badge bg-warning">{{ $supplier->products_expiring_soon }}</span>
                                        @else
                                            <span class="badge bg-success">0</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('supplierPerformanceChart').getContext('2d');
    
    // Set a fixed height for the chart
    ctx.canvas.style.height = '400px';

    // Extract data from the table rows
    const supplierData = [];
    document.querySelectorAll('table tbody tr').forEach(row => {
        supplierData.push({
            name: row.cells[0].textContent,
            productsSupplied: parseInt(row.cells[2].textContent),
            totalStock: parseInt(row.cells[3].textContent),
            inventoryValue: parseFloat(row.cells[4].textContent.replace('₱', '').replace(',', '')),
            lowStockItems: parseInt(row.cells[5].textContent.trim())
        });
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: supplierData.map(item => item.name),
            datasets: [
                {
                    label: 'Products Supplied',
                    data: supplierData.map(item => item.productsSupplied),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                },
                {
                    label: 'Total Stock',
                    data: supplierData.map(item => item.totalStock),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                },
                {
                    label: 'Inventory Value (₱)',
                    data: supplierData.map(item => item.inventoryValue),
                    backgroundColor: 'rgba(255, 206, 86, 0.5)',
                },
                {
                    label: 'Low Stock Items',
                    data: supplierData.map(item => item.lowStockItems),
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 0 // Disable animations
            },
            scales: {
                x: {
                    beginAtZero: true,
                    stacked: false,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                },
                y: {
                    stacked: false
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.dataset.label.includes('Value')) {
                                label += '₱' + context.parsed.x.toLocaleString();
                            } else {
                                label += context.parsed.x.toLocaleString();
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection