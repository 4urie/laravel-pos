<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;

/**
 * OrderController handles all order-related operations
 * Including creating orders, managing order status, and generating reports
 */
class OrderController extends Controller
{
    /**
     * Helper method to validate pagination row count
     * 
     * @param int $row Row count from request
     * @return int Validated row count
     */
    private function validateRowCount($row = 10)
    {
        $row = (int) $row;
        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }
        return $row;
    }

    /**
     * Display a listing of pending orders
     * 
     * @return \Illuminate\View\View
     */
    public function pendingOrders()
    {
        $row = $this->validateRowCount(request('row', 10));

      //  $orders = Order::where('order_status', 'pending')
    // ->sortable()
   // ->paginate($row);

        $orders = Order::with('customer')
            ->where('order_status', 'pending')
            ->sortable()
            ->paginate($row);

        return view('orders.pending-orders', [
            'orders' => $orders
        ]);
    }

    /**
     * Display a listing of completed orders
     * 
     * @return \Illuminate\View\View
     */
    public function completeOrders()
    {
        $row = $this->validateRowCount(request('row', 10));

        $orders = Order::where('order_status', 'complete')
            ->sortable()
            ->paginate($row);

        return view('orders.complete-orders', [
            'orders' => $orders
        ]);
    }

    /**
     * Display stock management page
     * 
     * @return \Illuminate\View\View
     */
    public function stockManage()
    {
        $row = $this->validateRowCount(request('row', 10));

        return view('stock.index', [
            'products' => Product::with(['category', 'supplier'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    /**
     * Store a newly created order
     * 
     * @param Request $request The HTTP request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeOrder(Request $request)
    {
        // Validate order data
        $rules = [
            'customer_id' => 'required|numeric',
            'payment_status' => 'required|string',
            'pay' => 'numeric|nullable',
            'due' => 'numeric|nullable',
        ];

        $validatedData = $request->validate($rules);

        // Start database transaction
        return DB::transaction(function() use ($request, $validatedData) {
            try {
                // Generate unique invoice number
                $invoice_no = IdGenerator::generate([
                    'table' => 'orders',
                    'field' => 'invoice_no',
                    'length' => 10,
                    'prefix' => 'INV-'
                ]);
                
                // Prepare order data
                $validatedData['order_date'] = Carbon::now()->format('Y-m-d');
                $validatedData['order_status'] = 'pending';
                $validatedData['total_products'] = Cart::count();
                $validatedData['sub_total'] = Cart::subtotal();
                $validatedData['vat'] = Cart::tax();
                $validatedData['invoice_no'] = $invoice_no;
                $validatedData['total'] = Cart::total();
                $validatedData['due'] = Cart::total() - $validatedData['pay'];
                $validatedData['created_at'] = Carbon::now();

                // Insert order and get ID
                $order_id = Order::insertGetId($validatedData);

                // Create order details from cart contents and update stock
                $contents = Cart::content();
                $oDetails = array();

                foreach ($contents as $content) {
                    $oDetails['order_id'] = $order_id;
                    $oDetails['product_id'] = $content->id;
                    $oDetails['quantity'] = $content->qty;
                    $oDetails['unitcost'] = $content->price;
                    $oDetails['total'] = $content->total;
                    $oDetails['created_at'] = Carbon::now();

                    OrderDetails::insert($oDetails);
                    
                    // Update product stock - reducing stock immediately when order is created
                    $product = Product::find($content->id);
                    if ($product) {
                        $product->product_store -= $content->qty;
                        $product->save();
                    }
                } 
                // DB:commit();
                // Clear cart after order creation
                Cart::destroy();

                return Redirect::route('dashboard')->with('success', 'Order has been created!');
            } catch (\Exception $e) {
                // Log the error
                Log::error('Order creation failed: ' . $e->getMessage());
               // DB:rollback();
                // Transaction will automatically rollback on exception
                return Redirect::back()->with('error', 'Order creation failed: ' . $e->getMessage());
            }
        });
    }

    /**
     * Display details for a specific order
     * 
     * @param int $order_id The order ID
     * @return \Illuminate\View\View
     */
    public function orderDetails(int $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $orderDetails = OrderDetails::with('product')
                        ->where('order_id', $order_id)
                        ->orderBy('id', 'DESC')
                        ->get();

        return view('orders.details-order', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }

    /**
     * Update order status to complete and reduce stock
     * 
     * @param Request $request The HTTP request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request)
    {
        $order_id = $request->id;

        return DB::transaction(function() use ($order_id) {
            try {
                // Get the order
                $order = Order::findOrFail($order_id);
                
                // Update order status to complete
                $order->update(['order_status' => 'complete']);
                
                return Redirect::route('order.pendingOrders')->with('success', 'Order has been completed!');
            } catch (\Exception $e) {
                // Log the error
                Log::error('Order status update failed: ' . $e->getMessage());
                
                // Transaction will automatically rollback on exception
                return Redirect::back()->with('error', 'Order status update failed: ' . $e->getMessage());
            }
        });
    }

    /**
     * Generate and download invoice for order
     * 
     * @param int $order_id The order ID
     * @return \Illuminate\View\View
     */
    public function invoiceDownload(int $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $orderDetails = OrderDetails::with('product')
                        ->where('order_id', $order_id)
                        ->orderBy('id', 'DESC')
                        ->get();

        // Show data (only for debugging)
        // TODO: Implement actual PDF generation and download
        return view('orders.invoice-order', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }

    /**
     * Display orders with pending dues
     * 
     * @return \Illuminate\View\View
     */
    public function pendingDue()
    {
        $row = $this->validateRowCount(request('row', 10));

        $orders = Order::where('due', '>', '0')
            ->sortable()
            ->paginate($row);

        return view('orders.pending-due', [
            'orders' => $orders
        ]);
    }

    /**
     * Return order data for AJAX request
     * 
     * @param int $id The order ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderDueAjax(int $id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update due amount for an order
     * 
     * @param Request $request The HTTP request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDue(Request $request)
    {
        $rules = [
            'order_id' => 'required|numeric',
            'due' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        return DB::transaction(function() use ($request, $validatedData) {
            try {
                $order = Order::findOrFail($request->order_id);
                $mainPay = $order->pay;
                $mainDue = $order->due;

                // Calculate new payment and due amounts
                $paid_due = $mainDue - $validatedData['due'];
                $paid_pay = $mainPay + $validatedData['due'];

                // Update order payment information
                $order->update([
                    'due' => $paid_due,
                    'pay' => $paid_pay,
                ]);

                return Redirect::route('order.pendingDue')->with('success', 'Due Amount Updated Successfully!');
            } catch (\Exception $e) {
                // Log the error
                Log::error('Due update failed: ' . $e->getMessage());
                
                // Transaction will automatically rollback on exception
                return Redirect::back()->with('error', 'Due update failed: ' . $e->getMessage());
            }
        });
    }

    /**
     * Generate sales report using stored procedure
     * Currently commented out - to be implemented
     */
    /* 
    public function salesReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        // Call the stored procedure
        $salesData = DB::select('CALL GetSalesReport(?, ?)', [$startDate, $endDate]);
        
        // Calculate totals for the report
        $totalSales = collect($salesData)->sum('total');
        $totalPaid = collect($salesData)->sum('pay');
        $totalDue = collect($salesData)->sum('due');
        
        return view('reports.sales-report', [
            'salesData' => $salesData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSales' => $totalSales,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue
        ]);
    }
    */
}

