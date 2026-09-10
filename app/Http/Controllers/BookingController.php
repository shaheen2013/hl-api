<?php

namespace App\Http\Controllers;

use App\Repositories\Bookings\BookingFunnelTypeInterface;
use App\Services\Bookings\BookingsService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected $bookingService;

    /**
     * BookingController constructor.
     * @param BookingsService $bookingsService
     */
    public function __construct(BookingsService $bookingsService)
    {
        $this->bookingService = $bookingsService;
    }

    /**
     * @SWG\Post(
     *     path="/api/brands/{brand_id}/bookings/funnel",
     *     description="Create new booking funnel",
     *     tags={"Survey Question"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="query",
     *         type="integer",
     *         description="User identifier that make an action to track a booking",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="referrer_id",
     *         in="query",
     *         type="integer",
     *         description="User identifier referred by user_id in case of source_action = share",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="session",
     *         in="query",
     *         type="string",
     *         description="Session cookie track by hotelinking",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="date",
     *         in="query",
     *         type="string",
     *         description="Current date",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="booking_action",
     *         in="query",
     *         type="string",
     *         description="Action made. Can be one of this: home, availability, checkout, booking",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="source",
     *         in="query",
     *         type="string",
     *         description="Source of the action. Can be one of this: facebook, pushtech, captive_portal, hotelinking_birthday_email",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="source_action",
     *         in="query",
     *         type="string",
     *         description="Source action. Can be one of this: share, campaign, redeem_offer, birthday_offer",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="Success with no content",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     * Display the specified resource.
     * @param Request $request
     * @param $brand_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request, $brand_id)
    {
        $userID = $request->get('user_id') == 'NULL' ? null : (int) $request->get('user_id');
        $referrerID = $request->get('referrer_id')  == 'NULL' || !$request->get('referrer_id') ? null : (int) $request->get('referrer_id');
        $session = $request->get('session');
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $bookingAction = $request->get('booking_action');
        $source = $request->get('source');
        $sourceAction = $request->get('source_action');

        if ($brand_id && $bookingAction) {
            $bookingFunnel = new BookingFunnelTypeInterface($brand_id, $userID, $referrerID, $session, $date, $bookingAction, $source, $sourceAction);
            $this->bookingService->saveBookingFunnel($bookingFunnel);
            return response(null, 204);
        }

        return response("Missing data", 422);
    }
}
