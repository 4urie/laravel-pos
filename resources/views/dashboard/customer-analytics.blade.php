@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Analytics</h3>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ collect($customerData)->count() }}</h3>
                                    <p>Total Customers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>
                                        ₱{{ number_format(collect($customerData)->sum('total_spent'), 2) }}
                                    </h3>
                                    <p>Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>
                                        ₱{{ number_format(collect($customerData)->avg('avg_order_value'), 2) }}
                                    </h3>
                                    <p>Average Order Value</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ collect($customerData)->sum('total_orders') }}</h3>
                                    <p>Total Orders</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bar Chart -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Customer Spending Analysis</h3>
                                </div>
                                <div class="col-md-12">
                                    <canvas id="customerSpendingChart" ></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Analytics Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Contact</th>
                                    <th>Total Orders</th>
                                    <th>Total Spent</th>
                                    <th>Avg. Order Value</th>
                                    <th>Last Order</th>
                                    <th>Days Since Last Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customerData as $customer)
                                <tr>
                                    <td>{{ $customer->customer_name }}</td>
                                    <td>
                                        {{ $customer->email }}<br>
                                        {{ $customer->phone }}
                                    </td>
                                    <td>{{ $customer->total_orders }}</td>
                                    <td>₱{{ number_format($customer->total_spent, 2) }}</td>
                                    <td>₱{{ number_format($customer->avg_order_value, 2) }}</td>
                                    <td>{{ $customer->last_order_date ? date('M d, Y', strtotime($customer->last_order_date)) : 'Never' }}</td>
                                    <td>
                                        @if($customer->days_since_last_order)
                                            <span class="badge {{ $customer->days_since_last_order > 30 ? 'bg-danger' : 'bg-success' }}">
                                                {{ $customer->days_since_last_order }} days
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">No orders</span>
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
    const ctx = document.getElementById('customerSpendingChart').getContext('2d');
    
    // Set a fixed height for the chart
    ctx.canvas.style.height = '400px';

    // Extract data from the table rows
    const customerData = [];
    document.querySelectorAll('table tbody tr').forEach(row => {
        customerData.push({
            name: row.cells[0].textContent,
            totalSpent: parseFloat(row.cells[3].textContent.replace('₱', '').replace(',', ''))
        });
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: customerData.map(item => item.name),
            datasets: [{
                label: 'Total Spent (₱)',
                data: customerData.map(item => item.totalSpent),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',  // Makes it a horizontal bar chart
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 0 // Disable animations
            },
            scales: {
                x: {  // This was previously y
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₱' + context.parsed.x.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection