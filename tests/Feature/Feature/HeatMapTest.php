<?php

namespace Tests\Feature\Feature;

use App\Models\LinkJourney;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HeatMapTest extends TestCase
{
    use RefreshDatabase;


    /**
     *
     * @return void
     */
    public function testStoreLinkJourneySuccessfully()
    {
        $payload = [
            'link_url' => 'http://google.com',
            'link_type' => 'product',
            'customer_id' => 2
        ];

        $this->json('POST', 'api/link_journeys', $payload)
            ->assertStatus(201)
            ->assertJson([
                'status' => true ,
                'message' => '',
                'errors' => [],
                'resource' => [
                    "link_url" => $payload["link_url"],
                    "link_type" => $payload["link_type"],
                    "customer_id" => $payload["customer_id"]
                ]
            ]);
    }

    /**
     *
     */
    public function testCounterPageByTimeInterval()
    {
        LinkJourney::factory()
            ->count(2)
            ->create([
                'created_at' => '2021-06-28 01:00:00',
            ]);

        $this->json('GET', 'api/link_journeys/counter_page/product/2021-05-28T00:00/2021-06-28T23:00')
            ->assertStatus(200)
            ->assertJson([
                'status' => true ,
                'message' => '',
                'errors' => [],
                'resource' => [
                    'count' => 2
                ]
            ]);
    }

    /**
     *
     */
    public function testCustomerJourney()
    {
        LinkJourney::factory()
            ->count(2)
            ->create([
                'customer_id' => 1,
            ]);

        $this->json('GET', 'api/link_journeys/customers_same_journey/2')
            ->assertStatus(200)
            ->assertJson([
                'status' => true ,
                'message' => '',
                'errors' => [],
                'resource' => [
                    'count' => 2
                ]
            ]);
    }

    /**
     *
     */
    public function testCustomersSameJourney()
    {
        LinkJourney::factory()
            ->create([
                'link_url' => 'http://google.com',
                'customer_id' => 1,
            ]);

        LinkJourney::factory()
            ->create([
                'link_url' => 'http://adobe.com',
                'customer_id' => 1,
         ]);

        LinkJourney::factory()
            ->create([
                'link_url' => 'http://google.com',
                'customer_id' => 2,
            ]);

        LinkJourney::factory()
            ->create([
                'link_url' => 'http://adobe.com',
                'customer_id' => 2,
            ]);

        $this->json('GET', 'api/link_journeys/customers_same_journey/1')
            ->assertStatus(200)
            ->assertJson([
                'status' => true ,
                'message' => '',
                'errors' => [],
                'resource' => 2
            ]);
    }
}
