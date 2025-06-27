<?php

namespace App\Http\Controllers\Dashboard;

use Exception;
use Illuminate\Database\QueryException;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage; 
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Picqer\Barcode\BarcodeGeneratorHTML;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        return view('products.index', [
            'products' => Product::with(['category', 'supplier'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create', [
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate form data with all required fields
        $rules = [
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_garage' => 'nullable|string',
            'product_store' => 'nullable|numeric',
            'buying_date' => 'nullable|date_format:Y-m-d',
            'expire_date' => 'nullable|date_format:Y-m-d',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ];
        
        $validatedData = $request->validate($rules);
        
        // Generate unique product code if not provided
        if (!isset($validatedData['product_code'])) {
            $validatedData['product_code'] = IdGenerator::generate([
                'table' => 'products',
                'field' => 'product_code',
                'length' => 8,
                'prefix' => 'PC'
            ]);
        }
        
        // Handle file upload
        if ($request->hasFile('product_image')) {
            // Get the file
            $image = $request->file('product_image');
            
            // Create unique filename
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the file in storage/app/public/products folder
            $image->storeAs('public/products', $imageName);
            
            // Save the filename to the database
            $validatedData['product_image'] = $imageName;
        }
        
        // Create the product with all validated data
        Product::create($validatedData);
        
        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Barcode Generator
        $generator = new BarcodeGeneratorHTML();

        $barcode = $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128);

        return view('products.show', [
            'product' => $product,
            'barcode' => $barcode,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', [
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        // Validate input before updating
        $validatedData = $request->validate([
            'product_image' => 'image|file|max:1024',
            'product_name' => 'required|string',
            'category_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'product_garage' => 'string|nullable',
            'product_store' => 'nullable|numeric',
            'buying_date' => 'date_format:Y-m-d|max:10|nullable',
            'expire_date' => 'date_format:Y-m-d|max:10|nullable',
            'buying_price' => ['required', 'numeric', 'min:0'],
'selling_price' => ['required', 'numeric', 'min:0'],
 // Ensure selling price is greater than buying price
        ]);
    
        // Handle image upload if there is a new image
        if ($file = $request->file('product_image')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/products/';
    
            $file->storeAs($path, $fileName);
            $validatedData['product_image'] = $fileName;
        }
    
        try {
            // Update the product with validated data
            $product->update($validatedData);
    
            return Redirect::route('products.index')->with('success', 'Product has been updated!');
        } catch (QueryException $e) {
          
    }
}
    
   

   

    /**
     * Permanently delete a soft-deleted resource.
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        
        // Delete the physical image file
        if($product->product_image && Storage::exists('public/products/'.$product->product_image)){
            Storage::delete('public/products/' . $product->product_image);
        }
        
        $product->forceDelete();
        
        return Redirect::route('products.trash')->with('success', 'Product has been permanently deleted!');
    }

    /**
     * Show the form for importing a new resource.
     */
    
    public function updateStatus(Request $request)
    {
        $order_id = $request->id;
        
        // Only update the order status - the trigger handles stock
        Order::findOrFail($order_id)->update(['order_status' => 'complete']);

        return Redirect::route('order.pendingOrders')->with('success', 'Order has been completed!');
    }
}
