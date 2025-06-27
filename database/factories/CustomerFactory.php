<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected static $customerNames = [
        'Juan Dela Cruz',
        'Maria Clara',
        'Jose Rizal',
        'Andres Bonifacio',
        'Emilio Aguinaldo',
        'Melchora Aquino',
        'Gregoria de Jesus',
        'Apolinario Mabini',
        'Diego Silang',
        'Gabriela Silang',
        'Antonio Luna',
        'Juan Luna',
        'Marcelo H. del Pilar',
        'Mariano Ponce',
        'Trinidad Tecson'
    ];

    protected static $usedNames = [];

    public function definition(): array
    {
        // Set the faker locale to Filipino
        $this->faker = \Faker\Factory::create('fil_PH');

        // Get available names (not yet used)
        $availableNames = array_values(array_diff(self::$customerNames, self::$usedNames));
        
        if (empty($availableNames)) {
            // If all names are used, reset the used names array
            self::$usedNames = [];
            $availableNames = self::$customerNames;
        }
        
        // Get a random name from available names
        $randomIndex = array_rand($availableNames);
        $name = $availableNames[$randomIndex];
        
        // Add the name to used names
        self::$usedNames[] = $name;

        // Custom shop names inspired by Filipino businesses
        $filipinoShopNames = [
            'Tindahan ni ' . explode(' ', $name)[0],
            'Sari-Sari ni ' . explode(' ', $name)[0],
            'Bodega ni ' . explode(' ', $name)[0],
            'Mini Mart ni ' . explode(' ', $name)[0],
            'Pamilihang ' . explode(' ', $name)[0]
        ];

        return [
            'name'            => $name,
            'email'           => $this->faker->unique()->safeEmail(),
            'phone'           => $this->faker->numerify('09#########'),
            'address'         => $this->faker->buildingNumber() . ' ' . $this->faker->streetName(),
            'shopname'        => $this->faker->randomElement($filipinoShopNames),
            'account_holder'  => $name,
            'account_number'  => $this->faker->numerify('########'),
            'bank_name'       => $this->faker->randomElement(['BDO', 'BPI', 'Metrobank', 'PNB', 'Landbank', 'RCBC']),
            'bank_branch'     => $this->faker->city(),
            'city'            => $this->faker->city(),
        ];
    }
}
