<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkJourney extends Model
{
    use HasFactory;

    protected $table = 'link_journey';

    protected $fillable = ['link_url', 'link_type', 'customer_id'];

    /**
     * @param $customer_id
     * @param $countCurrentCustomerJourneys
     *
     * @return mixed
     */
    public static function getCustomersWithSameCntJourneysAllLinks($customer_id, $countCurrentCustomerJourneys)
    {
        $customerSameCntJourneys = \DB::table('link_journey')
            ->select('customer_id', \DB::raw('COUNT(*) as cnt'))
            ->where('customer_id', '!=', $customer_id)
            ->groupBy('customer_id')
            ->having('cnt', $countCurrentCustomerJourneys);

        $customersWithSameCntJourneysAllLinks = \DB::table('link_journey')
            ->joinSub($customerSameCntJourneys, 'link_journey_same_cnt', function ($join) {
                $join->on('link_journey.customer_id', '=', 'link_journey_same_cnt.customer_id');
            })
            ->get();

        return $customersWithSameCntJourneysAllLinks;
    }
}
