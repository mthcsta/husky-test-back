<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\Client;
use App\Models\Delivery;
use App\Models\Deliveryman;
use App\Models\Point;

class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = Delivery::getStatusArray();
        $statusRandIndex = array_rand($status, 1);
        $statusRand = $status[$statusRandIndex];
        $deliveryman_id = null;
        if ($statusRandIndex !== 0) {
            $deliveryman_id = Deliveryman::inRandomOrder()->first()->id;
        }
        return [
            'client_id' => Client::inRandomOrder()->first()->id,
            'deliveryman_id' => $deliveryman_id,
            'status' => $statusRand,
            'collect_point_id' => Point::inRandomOrder()->first()->id,
            'destination_point_id' => Point::inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
