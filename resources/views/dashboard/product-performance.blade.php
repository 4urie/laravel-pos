@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Performance Analysis</h3>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ collect($productData)->sum('total_quantity_sold') }}</h3>
                                    <p>Total Items Sold</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>₱{{ number_format(collect($productData)->sum('total_revenue'), 2) }}</h3>
                                    <p>Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>₱{{ number_format(collect($productData)->sum('total_profit'), 2) }}</h3>
                                    <p>Total Profit</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ collect($productData)->count() }}</h3>
                                    <p>Total Products</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Performance Chart -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Product Performance Metrics</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="productPerformanceChart"></canvas> <!-- Canvas for the chart -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Performance Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Times Ordered</th>
                                    <th>Quantity Sold</th>
                                    <th>Revenue</th>
                                    <th>Profit</th>
                                    <th>Avg. Quantity/Order</th>
                                    <th>Current Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productData as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->category_name }}</td>
                                    <td>{{ $product->times_ordered }}</td>
                                    <td>{{ $product->total_quantity_sold }}</td>
                                    <td>₱{{ number_format($product->total_revenue, 2) }}</td>
                                    <td>₱{{ number_format($product->total_profit, 2) }}</td>
                                    <td>{{ number_format($product->avg_quantity_per_order, 1) }}</td>
                                    <td>
                                        <span class="badge {{ $product->current_stock <= 10 ? 'bg-danger' : 'bg-success' }}">
                                            {{ $product->current_stock }}
                                        </span>
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
    const ctx = document.getElementById('productPerformanceChart').getContext('2d');
    
    // Set a fixed height for the chart
    ctx.canvas.style.height = '400px';

    // Extract data from the table rows
    const productData = [];
    document.querySelectorAll('table tbody tr').forEach(row => {
        productData.push({
            name: row.cells[0].textContent,
            timesOrdered: parseInt(row.cells[2].textContent),
            quantitySold: parseInt(row.cells[3].textContent),
            revenue: parseFloat(row.cells[4].textContent.replace('₱', '').replace(',', '')),
            profit: parseFloat(row.cells[5].textContent.replace('₱', '').replace(',', ''))
        });
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: productData.map(item => item.name),
            datasets: [
                {
                    label: 'Times Ordered',
                    data: productData.map(item => item.timesOrdered),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                },
                {
                    label: 'Quantity Sold',
                    data: productData.map(item => item.quantitySold),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                },
                {
                    label: 'Total Revenue (₱)',
                    data: productData.map(item => item.revenue),
                    backgroundColor: 'rgba(255, 206, 86, 0.5)',
                },
                {
                    label: 'Total Profit (₱)',
                    data: productData.map(item => item.profit),
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
                    stacked: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                },
                y: {
                    stacked: true
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
                            if (context.dataset.label.includes('Revenue') || context.dataset.label.includes('Profit')) {
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