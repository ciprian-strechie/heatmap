<?php

namespace App\Http\Controllers;

use App\Models\LinkJourney;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LinkJourneyController extends Controller
{
    public function index()
    {
        return LinkJourney::all();
    }

    /**
     * @param $page
     * @param $start_time
     * @param $end_time
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function counterPageByTimeInterval($page, $start_time, $end_time)
    {
        $validator = \Validator::make(compact('page', 'start_time', 'end_time'), [
            'page'       => [
                'required',
                Rule::in(\Config::get('custom.link_categories')),
            ],
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time'   => 'required|date_format:Y-m-d\TH:i',
        ]);

        if ($validator->fails())
        {
            return $this->errorResponse('Invalid parameters provided!', $validator->errors());
        }

        $start_time = self::cleanDate($start_time);
        $end_time = self::cleanDate($end_time);
        $counter = LinkJourney::getCounterPageByTimeInterval($page, $start_time, $end_time);

        return $this->successResponse(['count' => $counter], 200);
    }

    /**
     * @param $search_url
     * @param $start_time
     * @param $end_time
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function counterUrlByTimeInterval($start_time, $end_time, $search_url)
    {
        $validator = \Validator::make(compact('search_url', 'start_time', 'end_time'), [
            'search_url' => 'url',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time'   => 'required|date_format:Y-m-d\TH:i',
        ]);

        if ($validator->fails())
        {
            return $this->errorResponse('Invalid parameters provided!', $validator->errors());
        }

        $start_time = self::cleanDate($start_time);
        $end_time = self::cleanDate($end_time);
        $counter = LinkJourney::getCounterUrlByTimeInterval($search_url, $start_time, $end_time);

        return $this->successResponse(['count' => $counter], 201);
    }

    /**
     * @param $customer_id
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function customerJourney($customer_id)
    {
        $validator = \Validator::make(compact('customer_id'), [
            'customer_id' => 'required|integer',
        ]);

        if ($validator->fails())
        {
            return $this->errorResponse('Invalid parameters provided!', $validator->errors());
        }

        $customerJourney = LinkJourney::getCustomerJourney($customer_id);

        return $this->successResponse($customerJourney, 200);
    }

    /**
     * @param $customer_id
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function customersSameJourney($customer_id)
    {
        $validator = \Validator::make(compact('customer_id'), [
            'customer_id' => 'required|integer',
        ]);

        if ($validator->fails())
        {
            return $this->errorResponse('Invalid parameters provided!', $validator->errors());
        }

        $currentCustomerJourney = LinkJourney::getCustomerJourney($customer_id);

        $customerSameJourneyAllLinks = LinkJourney::getCustomersWithSameCntJourneysAllLinks($customer_id, count($currentCustomerJourney));

        $customerSameJourney = [];
        $customersLinkUrls   = [];
        foreach ($customerSameJourneyAllLinks as $customer)
        {
            $customersLinkUrls[$customer->customer_id][] = $customer->link_url;
        }

        foreach ($customersLinkUrls as $customer_id => $customer)
        {
            if (empty(array_diff($customer, $currentCustomerJourney)))
            {
                $customerSameJourney[] = $customer_id;
            }
        }

        return $this->successResponse($customerSameJourney, 201);
    }

    /**
     * @param Request $request
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'link_url'    => 'required|url|max:255',
            'link_type'   => [
                'required',
                Rule::in(\Config::get('custom.link_categories')),
            ],
            'customer_id' => 'required|integer',
        ]);

        if ($validator->fails())
        {

            return $this->errorResponse('Invalid parameters provided!', $validator->errors());
        }

        $linkJourney = LinkJourney::create($request->all());

        return $this->successResponse($linkJourney, 201);
    }

    /**
     * @param       $message
     * @param array $errors
     * @param int   $httpStatus
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function errorResponse($message, $errors = [], $httpStatus = 400)
    {
        $returnData = [
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ];

        return response()->json($returnData, $httpStatus);
    }

    /**
     * @param        $resource
     * @param int    $httpStatus
     * @param string $message
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    private function successResponse($resource, $httpStatus = 200, $message = '')
    {
        $returnData = [
            'status'   => true,
            'message'  => $message,
            'errors'   => [],
            'resource' => $resource,
        ];

        return response()->json($returnData, $httpStatus);

        return $returnData;
    }

    private static function cleanDate($time)
    {
        return str_replace('T', ' ', $time);;
    }
}
