<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition()
    {
        return [
            'order_no' => rand(0, 9999),
            'total' => rand(100, 1000),
        ];
    }
}
