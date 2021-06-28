<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkJourney extends Model
{
    use HasFactory;

    protected $table = 'link_journey';

    protected $fillable = [
        'link_url',
        'link_type',
        'customer_id',
    ];

    /**
     * @param $customer_id
     * @param $countCurrentCustomerJourneys
     *
     * @return mixed
     */
    public static function getCustomersWithSameCntJourneysAllLinks($customer_id, $countCurrentCustomerJourneys)
    {
        $customerSameCntJourneys = self::select('customer_id', \DB::raw('COUNT(*) as cnt'))
            ->where('customer_id', '!=', $customer_id)
            ->groupBy('customer_id')
            ->having('cnt', $countCurrentCustomerJourneys);

        $customersWithSameCntJourneysAllLinks = self::joinSub($customerSameCntJourneys, 'link_journey_same_cnt', function ($join) {
                $join->on('link_journey.customer_id', '=', 'link_journey_same_cnt.customer_id');
            })
            ->get();

        return $customersWithSameCntJourneysAllLinks;
    }

    /**
     * @param $page
     * @param $start_time
     * @param $end_time
     *
     * @return mixed
     */
    public static function getCounterPageByTimeInterval($page, $start_time, $end_time)
    {
        return self::where('link_type', $page)
            ->where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->count();
    }

    /**
     * @param $search_url
     * @param $start_time
     * @param $end_time
     *
     * @return mixed
     */
    public static function getCounterUrlByTimeInterval($search_url, $start_time, $end_time)
    {
        return self::where('link_url', $search_url)
            ->where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->count();
    }

    /**
     * @param $customer_id
     *
     * @return mixed
     */
    public static function getCustomerJourney($customer_id)
    {
        return self::where('customer_id', $customer_id)
            ->orderBy('created_at')
            ->pluck('link_url');
    }
}
