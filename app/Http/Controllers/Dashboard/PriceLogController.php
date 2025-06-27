<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class PriceLogController extends Controller
{
    /**
     * Constructor - Apply permission middleware
     */
    public function __construct()
    {
        $this->middleware('permission:price_logs.menu');
    }
    
    /**
     * Display a listing of the price logs.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $search = request('search');
        
        $query = DB::table('price_logs')
            ->join('products', 'price_logs.product_id', '=', 'products.id')
            ->join('users', 'price_logs.changed_by', '=', 'users.id', 'left')
            ->select(
                'price_logs.*',
                'products.product_name',
                'products.product_code',
                'users.name as user_name'
            )
            ->orderBy('price_logs.changed_at', 'desc');
            
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('products.product_name', 'like', "%{$search}%")
                  ->orWhere('products.product_code', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }
        
        $priceLogs = $query->paginate($row)->appends(request()->query());
        
        return view('price_logs.index', compact('priceLogs'));
    }
    
    /**
     * Get price history for a specific product
     */
    public function productHistory($productId)
    {
        $product = Product::findOrFail($productId);
        
        $priceLogs = DB::table('price_logs')
            ->join('users', 'price_logs.changed_by', '=', 'users.id', 'left')
            ->select(
                'price_logs.*',
                'users.name as user_name'
            )
            ->where('product_id', $productId)
            ->orderBy('changed_at', 'desc')
            ->get();
            
        return view('price_logs.product_history', compact('product', 'priceLogs'));
    }
} 