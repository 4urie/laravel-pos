<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        static $used = [];

        $categories = [
            'Processors',
            'Motherboards',
            'Graphics Cards',
            'Memory (RAM)',
            'Storage Devices',
            'Power Supplies',
            'Cases',
            'Monitors',
            'Peripherals'
        ];

        $available = array_diff($categories, $used);

        if (empty($available)) {
            $name = 'Default Category ' . Str::random(5);
        } else {
            $name = collect($available)->random();
            $used[] = $name;
        }

        return [
            'slug' => Str::slug($name),
            'name' => $name,
        ];
    }
}
