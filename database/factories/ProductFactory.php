<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Supplier;

class ProductFactory extends Factory
{
    private static $usedProducts = [];

    public function definition(): array
    {
        $products = [
            // Processors
            ['name' => 'Ryzen 5 5600G Processor', 'category' => 'Processors'],
            ['name' => 'Intel Core i5 10400F', 'category' => 'Processors'],
            ['name' => 'Ryzen 7 5800X', 'category' => 'Processors'],
            ['name' => 'Intel Core i7 11700K', 'category' => 'Processors'],
            ['name' => 'Intel Core i9 11900K', 'category' => 'Processors'],
            ['name' => 'Ryzen 9 5900X', 'category' => 'Processors'],
            ['name' => 'Intel Core i3 10100', 'category' => 'Processors'],
            ['name' => 'AMD Athlon 3000G', 'category' => 'Processors'],
            ['name' => 'Intel Pentium Gold G6400', 'category' => 'Processors'],
        
            // Motherboards
            ['name' => 'ASUS Prime A320M-K', 'category' => 'Motherboards'],
            ['name' => 'MSI B450M Pro-VDH Max', 'category' => 'Motherboards'],
            ['name' => 'Gigabyte B550M DS3H', 'category' => 'Motherboards'],
            ['name' => 'ASRock B460M Pro4', 'category' => 'Motherboards'],
            ['name' => 'MSI X570 Tomahawk', 'category' => 'Motherboards'],
            ['name' => 'ASUS ROG Strix B550-F', 'category' => 'Motherboards'],
            ['name' => 'Gigabyte X570 AORUS Elite', 'category' => 'Motherboards'],
            ['name' => 'ASRock H610M-HDV', 'category' => 'Motherboards'],
        
            // Graphics Cards
            ['name' => 'Gigabyte GTX 1660 Super', 'category' => 'Graphics Cards'],
            ['name' => 'ASUS Dual RTX 3060', 'category' => 'Graphics Cards'],
            ['name' => 'MSI RTX 3070 Gaming X', 'category' => 'Graphics Cards'],
            ['name' => 'Sapphire RX 6600 XT', 'category' => 'Graphics Cards'],
            ['name' => 'Zotac RTX 3050 Twin Edge', 'category' => 'Graphics Cards'],
            ['name' => 'PNY RTX 4060', 'category' => 'Graphics Cards'],
            ['name' => 'ASUS TUF RX 6700 XT', 'category' => 'Graphics Cards'],
            ['name' => 'EVGA RTX 3090 FTW3', 'category' => 'Graphics Cards'],
        
            // RAM
            ['name' => 'Corsair Vengeance 8GB DDR4', 'category' => 'Memory (RAM)'],
            ['name' => 'G.Skill Ripjaws 16GB DDR4', 'category' => 'Memory (RAM)'],
            ['name' => 'Kingston HyperX 32GB DDR4', 'category' => 'Memory (RAM)'],
            ['name' => 'Team Elite 8GB DDR4', 'category' => 'Memory (RAM)'],
            ['name' => 'ADATA XPG 16GB DDR4', 'category' => 'Memory (RAM)'],
            ['name' => 'Crucial Ballistix 8GB DDR4', 'category' => 'Memory (RAM)'],
            ['name' => 'Patriot Viper Steel 16GB DDR4', 'category' => 'Memory (RAM)'],
            ['name' => 'Silicon Power 32GB DDR4', 'category' => 'Memory (RAM)'],
        
            // Storage
            ['name' => 'WD Blue 1TB HDD', 'category' => 'Storage Devices'],
            ['name' => 'Samsung 970 EVO 500GB SSD', 'category' => 'Storage Devices'],
            ['name' => 'Crucial MX500 1TB SSD', 'category' => 'Storage Devices'],
            ['name' => 'Seagate Barracuda 2TB HDD', 'category' => 'Storage Devices'],
            ['name' => 'Kingston A2000 512GB NVMe', 'category' => 'Storage Devices'],
            ['name' => 'SanDisk Ultra 1TB SSD', 'category' => 'Storage Devices'],
            ['name' => 'Toshiba Canvio Basics 2TB', 'category' => 'Storage Devices'],
            ['name' => 'PNY CS900 240GB SSD', 'category' => 'Storage Devices'],
        
            // PSU
            ['name' => 'Cooler Master 500W PSU', 'category' => 'Power Supplies'],
            ['name' => 'Corsair RM750x', 'category' => 'Power Supplies'],
            ['name' => 'EVGA 650W Gold', 'category' => 'Power Supplies'],
            ['name' => 'Seasonic Focus 750W', 'category' => 'Power Supplies'],
            ['name' => 'Thermaltake Smart 600W', 'category' => 'Power Supplies'],
            ['name' => 'Gigabyte P650B 650W', 'category' => 'Power Supplies'],
            ['name' => 'XPG Core Reactor 750W', 'category' => 'Power Supplies'],
        
            // Cases
            ['name' => 'Deepcool MATREXX 30', 'category' => 'Cases'],
            ['name' => 'NZXT H510', 'category' => 'Cases'],
            ['name' => 'Phanteks P300', 'category' => 'Cases'],
            ['name' => 'Lian Li LANCOOL 215', 'category' => 'Cases'],
            ['name' => 'Cooler Master MB311L', 'category' => 'Cases'],
            ['name' => 'Fractal Design Meshify C', 'category' => 'Cases'],
            ['name' => 'Thermaltake Versa H18', 'category' => 'Cases'],
        
            // Monitors
            ['name' => 'ASUS TUF Gaming 27"', 'category' => 'Monitors'],
            ['name' => 'Acer 24-inch LED', 'category' => 'Monitors'],
            ['name' => 'LG 32-inch UltraGear', 'category' => 'Monitors'],
            ['name' => 'MSI Optix MAG274QRF-QD', 'category' => 'Monitors'],
            ['name' => 'ViewSonic VX3276-2K-MHD', 'category' => 'Monitors'],
            ['name' => 'Dell 27" Ultrasharp', 'category' => 'Monitors'],
            ['name' => 'BenQ ZOWIE XL2411K', 'category' => 'Monitors'],
        
            // Peripherals
            ['name' => 'TP-Link WiFi Adapter', 'category' => 'Peripherals'],
            ['name' => 'Logitech G502 Mouse', 'category' => 'Peripherals'],
            ['name' => 'Razer BlackWidow Keyboard', 'category' => 'Peripherals'],
            ['name' => 'HyperX Cloud II Headset', 'category' => 'Peripherals'],
            ['name' => 'Redragon K552 Mechanical Keyboard', 'category' => 'Peripherals'],
            ['name' => 'Logitech C920 Webcam', 'category' => 'Peripherals'],
            ['name' => 'Elgato Stream Deck Mini', 'category' => 'Peripherals'],
            ['name' => 'Corsair MM300 Gaming Mouse Pad', 'category' => 'Peripherals'],
        ];
        

        // Reset used products if all are used
        if (count(self::$usedProducts) >= count($products)) {
            self::$usedProducts = [];
        }

        // Get a unique product
        do {
            $selectedProduct = fake()->randomElement($products);
        } while (in_array($selectedProduct['name'], self::$usedProducts));
        
        self::$usedProducts[] = $selectedProduct['name'];
        
        // Find or create the category
        $category = Category::where('name', $selectedProduct['category'])->first() 
            ?? Category::factory()->create(['name' => $selectedProduct['category']]);

        return [
            'product_code' => 'PC' . fake()->unique()->numerify('########'),
            'product_name' => $selectedProduct['name'],
            'category_id' => $category->id,
            'supplier_id' => Supplier::inRandomOrder()->first()?->id ?? Supplier::factory(),
            'product_garage' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'product_store' => fake()->randomNumber(3),
            'buying_price' => fake()->numberBetween(1500, 15000),
            'selling_price' => fake()->numberBetween(2000, 20000),
            'buying_date' => now(),
            'expire_date' => now()->addYears(2),
        ];
    }
}
