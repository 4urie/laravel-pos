<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSalesSummary;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard page
     */
    public function index()
    {
        // Get top 10 products ordered by total revenue
        $topProducts = DB::table('product_performance_view')
            ->join('products', 'product_performance_view.id', '=', 'products.id')
            ->select('product_performance_view.*', 'products.product_image')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.index', [
            'topProducts' => $topProducts,
            'total_paid' => Order::sum('pay'),
            'total_due' => Order::sum('due'),
            'complete_orders' => Order::where('order_status', 'complete')->get(),
            'products' => Product::orderBy('product_store')->take(5)->get(),
            'new_products' => Product::orderBy('buying_date')->take(2)->get(),
        ]);
    }
    
    /**
     * Show the low stock products dashboard
     */
    public function lowStock(Request $request)
    {
        $threshold = $request->get('threshold', 10);
        
        $lowStockProducts = DB::table('inventory_status_view')
            ->where('current_stock', '<=', $threshold)
            ->orderBy('current_stock', 'asc')
            ->get();
            
        return view('dashboard.low-stock', [
            'lowStockProducts' => $lowStockProducts,
            'threshold' => $threshold
        ]);
    }
    
    /**
     * Show inventory status dashboard
     */
    public function inventoryStatus(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $query = DB::table('inventory_status_view');
        
        // Apply filters based on request
        if ($filter === 'low_stock') {
            $query->where('stock_status', 'Low Stock');
        } elseif ($filter === 'critical_stock') {
            $query->where('stock_status', 'Critical Stock');
        } elseif ($filter === 'expiring_soon') {
            $query->where('expiry_status', 'Expiring Soon');
        }
        
        // Get inventory data from the view
        $inventoryStatus = $query->orderBy('current_stock', 'asc')->get();
        
        // Get summary counts
        $totalProducts = DB::table('inventory_status_view')->count();
        $lowStockCount = DB::table('inventory_status_view')->where('stock_status', 'Low Stock')->count();
        $criticalStockCount = DB::table('inventory_status_view')->where('stock_status', 'Critical Stock')->count();
        $expiringSoonCount = DB::table('inventory_status_view')->where('expiry_status', 'Expiring Soon')->count();
        
        return view('dashboard.inventory-status', [
            'inventoryStatus' => $inventoryStatus,
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'criticalStockCount' => $criticalStockCount,
            'expiringSoonCount' => $expiringSoonCount,
            'filter' => $filter
        ]);
    }
    
    /**
     * Get sales by category for a given date range
     */
    public function salesByCategory(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        // Call the stored procedure
        $salesByCategory = DB::select('CALL GetSalesByCategory(?, ?)', [$startDate, $endDate]);
        
        return view('dashboard.sales-by-category', [
            'salesByCategory' => $salesByCategory,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    public function salesSummary()
    {
        $salesData = DB::table('sales_summary_view')
            ->orderBy('sale_date', 'desc')
            ->get();

        return view('dashboard.sales-summary', compact('salesData'));
    }

    public function productPerformance()
    {
        $productData = DB::table('product_performance_view')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('dashboard.product-performance', compact('productData'));
    }

    public function customerAnalytics()
    {
        $customerData = DB::table('customer_analytics_view')
            ->orderBy('total_spent', 'desc')
            ->get();

        return view('dashboard.customer-analytics', compact('customerData'));
    }

    public function supplierPerformance()
    {
        $supplierData = DB::table('supplier_performance_view')
            ->orderBy('total_inventory_value', 'desc')
            ->get();

        return view('dashboard.supplier-performance', compact('supplierData'));
    }
}
