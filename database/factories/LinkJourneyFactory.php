<?php

namespace Database\Factories;

use App\Models\LinkJourney;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LinkJourneyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LinkJourney::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'link_url' => $this->faker->url,
            'link_type' => $this->faker->randomElement(\Config::get('custom.link_categories')),
            'customer_id' => $this->faker->biasedNumberBetween(10, 20),
        ];
    }

}
