<?php

namespace Database\Seeders;

use App\Models\LinkJourney;
use Illuminate\Database\Seeder;

class LinkJourneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        LinkJourney::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 50; $i++) {
            LinkJourney::create([
                'link_url' => $faker->url,
                'link_type' => $faker->randomElement(\Config::get('custom.link_categories')),
                'customer_id' => $faker->biasedNumberBetween(10, 20),
            ]);
        }
    }
}
