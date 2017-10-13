<?php

use Illuminate\Database\Seeder;
use App\Order;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
       Order::truncate();

       $faker = \Faker\Factory::create();

       // And now, let's create a few articles in our database:
       for ($i = 0; $i < 50; $i++) {
           Order::create([
               'title' => $faker->catchPhrase,
               'email' => $faker->email,
               'city' => $faker->city,
               'state' => $faker->state,
               'address' => $faker->streetAddress,
               'picture_path' => $faker->url,
               'shirt_color' => $faker->safeColorName,
           ]);
       }
    }
}
