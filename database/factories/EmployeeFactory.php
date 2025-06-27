<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('fil_PH');
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('09#########'),
            'address' => $this->faker->address(),
            'experience' => $this->faker->randomElement(['0 Taon', '1 Taon', '2 Taon', '3 Taon', '4 Taon', '5 Taon']),
            'city' => $this->faker->randomElement([
                'Manila', 'Quezon City', 'Davao City', 'Cebu City', 'Makati', 
                'Taguig', 'Pasig', 'Cagayan de Oro', 'Parañaque', 'Caloocan',
                'Iloilo City', 'Bacolod', 'Zamboanga City', 'Baguio', 'Tagaytay',
                'Angeles City', 'Lapu-Lapu City', 'General Santos', 'Muntinlupa',
                'Marikina', 'Valenzuela', 'Mandaluyong', 'Las Piñas'
            ]),
        ];
    }
}
