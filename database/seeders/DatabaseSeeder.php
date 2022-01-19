<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Client;
use App\Models\Delivery;
use App\Models\Deliveryman;
use App\Models\Point;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Point::factory(100)->create();
        Client::factory(100)->create();
        Deliveryman::factory(100)->create();
        Delivery::factory(100)->create();
    }
}
