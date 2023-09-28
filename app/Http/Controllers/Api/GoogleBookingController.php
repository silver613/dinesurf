<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vendor;
use App\Services\AllServices\Admin;
use Illuminate\Http\Request;

class GoogleBookingController extends Controller
{
    public function healthCheck()
    {
        return Helper::apiRes('Site is up and running');
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'slot' => 'required',
            'slot.merchant_id' => 'required',
        ]);

        $slot = (object) $request->slot;
        $merchant_id = $slot->merchant_id;
        $vendor = Vendor::where('id', $merchant_id)->first();
        if (! $vendor) {
            response()->json([
                'message' => 'merchant not found',
            ], 404);
        }

        return response()->json([
            'slot' => $slot,
            'count_available' => $vendor->open ? 1 : 0,
            'duration_requirement' => 'DURATION_REQUIREMENT_UNSPECIFIED',
            // TO-DO: populate proper values and other fields, such as
            // availability_update
        ]);
    }

    public function createBooking(Request $request)
    {
        $request->validate([
            'slot' => 'required',
            'slot.merchant_id' => 'required',
            'user_information' => 'required',
            'user_information.user_id' => 'required',
        ]);

        // TO-DO: validate req, e.g. (req.user_information !== null)
        // TO-DO: add code to create a booking
        // CreateBookingResponse
        $slot = (object) $request->slot;
        $resources = (object) $slot->resources;
        $user_information = (object) $request->user_information;

        $merchant_id = $slot->merchant_id;
        $vendor = Vendor::where('id', $merchant_id)->first();
        if (! $vendor) {
            response()->json([
                'message' => 'merchant not found',
            ], 404);
        }

        $user = User::where('email', $user_information->email)->first();
        if (! $user) {
            $user = new User;
            $user->google_id = $user_information->user_id;
            $user->first_name = $user_information->given_name;
            $user->last_name = $user_information->family_name;
            $user->email = $user_information->email;
            $user->image = $user_information->avatar;
            $user->password = md5(rand(1, 10000));
            $user->save();
        } else {
            $user->google_id = $user->google_id ?: $user_information->user_id;
            $user->save();
        }

        try {
            $reservation = Admin::createReservation($vendor->id, $resources->party_size, $resources->date ?? now()->toDateString(), $resources->time ?? now()->addHour()->toTimeString(), $user->id, null, false, [], null, null, 'google');
        } catch (\Throwable $th) {
            throw $th;
        }

        $resp['booking'] = [
            'booking_id' => $reservation->id,
            'slot' => $slot,
            'user_information' => $user_information,
            'payment_information' => null,
            'status' => $reservation->status,
        ];

        return response()->json($resp);
    }

    public function updateBooking(Request $request)
    {
        $request->validate([
            'booking' => 'required',
            'booking.booking_id' => 'required',
        ]);

        // TO-DO: validate req, e.g.
        //   (req.booking !== null && req.booking.booking_id !== null)
        // TO-DO: add code to update the provided booking
        // UpdateBookingResponse
        $booking = (object) $request->booking;
        $slot = (object) $booking->slot;
        $resources = (object) $slot->resources;
        $user_information = (object) $booking->user_information;

        $reservation = Reservation::where('id', $booking->booking_id)->first();
        if (! $reservation) {
            response()->json([
                'message' => 'booking not found',
            ], 404);
        }

        $reservation->party_size = $resources->party_size ?? $reservation->party_size;
        $reservation->status = $request->status ?? $reservation->status;
        $reservation->save();

        $resp['booking'] = [
            'booking_id' => $booking->booking_id,
            'status' => $reservation->status,
        ];

        return response()->json($resp);
    }

    public function getBookingStatus(Request $request)
    {
        $request->validate([
            'booking_id' => 'required',
        ]);

        // TO-DO: validate req, e.g. (req.booking_id !== null)
        // TO-DO: add code to retrieve the booking status
        // GetBookingStatusResponse
        $reservation = Reservation::where('id', $request->booking_id)->first();
        if (! $reservation) {
            response()->json([
                'message' => 'booking not found',
            ], 404);
        }

        $resp = [
            'booking_id' => $reservation->id,
            'booking_status' => $reservation->status,
        ];

        return response()->json($resp);
    }

    public function listBookings(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        // TO-DO: validate req, e.g. (req.user_id !== null)
        // TO-DO: add code to fetch all bookings for the user_id
        // ListBookingsResponse

        $user = User::where('google_id', $request->user_id)->first();
        if (! $user) {
            response()->json([
                'message' => 'user not found',
            ], 404);
        }

        $bookings = [];

        foreach ($user->reservations as $key => $reservation) {
            array_push($bookings, [
                'booking_id' => $reservation->id,
                'slot' => [
                    'merchant_id' => $reservation->vendor_id,
                    'service_id' => 'string',
                    'start_sec' => 0,
                    'duration_sec' => 0,
                    'resources' => [
                        'party_size' => $reservation->party_size,
                        'room_id' => 'string',
                    ],
                ],
                'user_information' => [
                    'user_id' => $user->google_id,
                    'given_name' => $user->first_name,
                    'family_name' => $user->last_name,
                    'telephone' => $reservation->phone,
                    'email' => $user->email,
                ],
                'status' => 'Unspecified',
                'payment_information' => [
                    'prepayment_status' => 'Unspecified',
                    'payment_processed_by' => 'Unspecified',
                ],
            ]);
        }

        $resp = [
            'bookings' => $bookings,
        ];

        return response()->json($resp);
    }

    public function checkOrderFulfillability(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required',
        ]);

        // CheckOrderFulfillabilityRequest
        // TO-DO: validate req, e.g. (req.merchant_id !== null)
        // TO-DO: add code to validate individual items and calculate the total price

        $resp = [
            'fulfillability' => [
                'result' => 'CAN_FULFILL',
                'item_fulfillability' => [],
            ],
            'fees_and_taxes' => ['price_micros' => 1000000, 'currency_code' => 'USD'],
        ];

        return response()->json($resp);
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'user_information' => 'required',
        ]);

        // TO-DO: validate req, e.g. (req.user_information !== null)
        // TO-DO: check for req.idempotency_token uniqueness
        // TO-DO: create and process the order
        // CreateOrderResponse
        $resp['order'] = [
            // 'order_id' => '1234',
            // 'merchant_id' => $req['order']['merchant_id'],
            // 'item' => []
        ];

        return response()->json($resp);
    }

    public function listOrders(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'order_ids' => 'required',
        ]);

        // TO-DO: validate req, e.g. if ("user_id" in req || "order_ids" in req)
        // TO-DO: fetch orders for req.user_id or a list of req.order_ids
        // ListOrdersResponse
        $resp = [
            'order' => [],
        ];

        return response()->json($resp);
    }
}
