@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sales Summary</h3>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ collect($salesData)->sum('total_orders') }}</h3>
                                    <p>Total Orders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ number_format(collect($salesData)->sum('total_items_sold')) }}</h3>
                                    <p>Total Items Sold</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>₱{{ number_format(collect($salesData)->sum('total_revenue'), 2) }}</h3>
                                    <p>Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>₱{{ number_format(collect($salesData)->sum('total_profit'), 2) }}</h3>
                                    <p>Total Profit</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Data Chart -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Sales Revenue and Profit Over Time</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesRevenueChart"></canvas> <!-- Canvas for the chart -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Orders</th>
                                    <th>Items Sold</th>
                                    <th>Revenue</th>
                                    <th>Profit</th>
                                    <th>Products Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesData as $sale)
                                <tr>
                                    <td>{{ $sale->sale_date }}</td>
                                    <td>{{ $sale->total_orders }}</td>
                                    <td>{{ $sale->total_items_sold }}</td>
                                    <td>₱{{ number_format($sale->total_revenue, 2) }}</td>
                                    <td>₱{{ number_format($sale->total_profit, 2) }}</td>
                                    <td>{{ $sale->products_sold }}</td>
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
    const ctx = document.getElementById('salesRevenueChart').getContext('2d');
    
    // Set a fixed height for the chart
    ctx.canvas.style.height = '400px';
    
    // Extract data from the table rows
    const salesData = [];
    document.querySelectorAll('table tbody tr').forEach(row => {
        salesData.push({
            date: row.cells[0].textContent,
            revenue: parseFloat(row.cells[3].textContent.replace('₱', '').replace(',', '')),
            profit: parseFloat(row.cells[4].textContent.replace('₱', '').replace(',', ''))
        });
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: salesData.map(item => item.date),
            datasets: [
                {
                    label: 'Total Revenue (₱)',
                    data: salesData.map(item => item.revenue),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                },
                {
                    label: 'Total Profit (₱)',
                    data: salesData.map(item => item.profit),
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
                            return '₱' + value.toLocaleString();
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
                            return context.dataset.label + ': ₱' + context.parsed.x.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection