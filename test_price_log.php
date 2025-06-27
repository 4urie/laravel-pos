<?php
require __DIR__.'/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Testing the price_logs trigger...\n\n";

// Check if price_logs table exists
$tablesExist = DB::select("SHOW TABLES LIKE 'price_logs'");
if (empty($tablesExist)) {
    echo "ERROR: price_logs table does not exist!\n";
    echo "Run the SQL from database/triggers/log_price_changes.sql to create it first.\n";
    exit(1);
}

// Check if trigger exists
$triggerExists = DB::select("SHOW TRIGGERS LIKE 'after_product_price_update'");
if (empty($triggerExists)) {
    echo "ERROR: after_product_price_update trigger does not exist!\n";
    echo "Run the SQL from database/triggers/log_price_changes.sql to create it first.\n";
    exit(1);
}

// Get a sample product
$product = DB::table('products')->first();
if (!$product) {
    echo "ERROR: No products found in the database!\n";
    exit(1);
}

echo "Found product #{$product->id}: {$product->name}\n";
echo "Current buying price: {$product->buying_price}\n";
echo "Current selling price: {$product->selling_price}\n\n";

// Set the current user ID (simulate being logged in)
DB::statement("SET @current_user_id = 1");

// Find current log count for this product
$oldLogCount = DB::table('price_logs')
    ->where('product_id', $product->id)
    ->count();

// Calculate new prices (add 10% to current prices)
$newBuyingPrice = $product->buying_price * 1.1;
$newSellingPrice = $product->selling_price * 1.1;

echo "Updating prices...\n";
echo "New buying price: {$newBuyingPrice}\n";
echo "New selling price: {$newSellingPrice}\n\n";

// Update the product prices
DB::table('products')
    ->where('id', $product->id)
    ->update([
        'buying_price' => $newBuyingPrice,
        'selling_price' => $newSellingPrice
    ]);

// Check if a new log entry was created
$newLogCount = DB::table('price_logs')
    ->where('product_id', $product->id)
    ->count();

$logEntries = DB::table('price_logs')
    ->where('product_id', $product->id)
    ->orderBy('id', 'desc')
    ->limit(1)
    ->get();

if ($newLogCount > $oldLogCount) {
    echo "SUCCESS! Price change was logged.\n";
    echo "Log entry details:\n";
    print_r($logEntries[0]);
    
    // Restore original prices
    DB::table('products')
        ->where('id', $product->id)
        ->update([
            'buying_price' => $product->buying_price,
            'selling_price' => $product->selling_price
        ]);
    echo "\nRestored original prices.\n";
} else {
    echo "ERROR: No new log entry was created!\n";
    echo "The trigger may not be working correctly.\n";
    
    // Still restore original prices
    DB::table('products')
        ->where('id', $product->id)
        ->update([
            'buying_price' => $product->buying_price,
            'selling_price' => $product->selling_price
        ]);
    echo "\nRestored original prices.\n";
} 