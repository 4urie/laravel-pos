<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    private static $usedNames = [];
    private static $usedShopNames = [];

    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('fil_PH');

        $filipinoNames = [
            'Mang Kanor',
            'Aling Nena',
            'Tatay Cardo',
            'Kapitana Susan',
            'Lola Basyang',
            'Mang Jose',
            'Aling Maria',
            'Tatay Ben',
            'Nanay Carmen',
            'Kuya Pedro'
        ];

        $filipinoShopNames = [
            'Sari-Sari ni Nena',
            'Kanto Hardware',
            'Bigas at Gulay Center',
            'Gamit-Bahay Trading',
            'Tindahan ni Tatay',
            'Super Supply Store',
            'Pinoy Goods Trading',
            'Filipino Wholesale Center',
            'Mabuhay General Merchandise',
            'Pinoy Products Direct'
        ];

        // Reset used names if all are used
        if (count(self::$usedNames) >= count($filipinoNames)) {
            self::$usedNames = [];
        }

        // Reset used shop names if all are used
        if (count(self::$usedShopNames) >= count($filipinoShopNames)) {
            self::$usedShopNames = [];
        }

        // Get unique supplier name
        do {
            $name = $this->faker->randomElement($filipinoNames);
        } while (in_array($name, self::$usedNames));
        self::$usedNames[] = $name;

        // Get unique shop name
        do {
            $shopName = $this->faker->randomElement($filipinoShopNames);
        } while (in_array($shopName, self::$usedShopNames));
        self::$usedShopNames[] = $shopName;

        return [
            'name' => $name,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->numerify('09#########'),
            'address' => $this->faker->address(),
            'shopname' => $shopName,
            'type' => $this->faker->randomElement(['Distributor', 'Whole Seller']),
            'account_holder' => $name, // Set account holder to match supplier name
            'account_number' => $this->faker->numerify('########'),
            'bank_name' => $this->faker->randomElement(['BDO', 'BPI', 'Metrobank', 'Landbank', 'PNB', 'Security Bank']),
            'bank_branch' => $this->faker->city(),
            'city' => $this->faker->city(),
        ];
    }
}
