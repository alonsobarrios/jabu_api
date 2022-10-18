<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::all();

        return response()->json([
            'bookings' => $bookings->map(function($booking){
                return $booking->toArray(true);
            })
        ]);
    }

    /**
     * Validator request.
     *
     * @param  array  $data
     * @return array ErrorsMessages
     */
    private function validator(array $data)
    {
        $validateRequest = new StoreBookingRequest();
        $validator = Validator::make($data, $validateRequest->rules(), $validateRequest->messages());

        return $validator->errors()->messages();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $errors = $this->validator($request->all());
            if (count($errors)) {
                return response()->json([
                    'errors' => $errors
                ], 422);
            }

            $booking = new Booking();
            $booking->fill($request->only($booking->getFillable()));
            $booking->save();

            return response()->json([
                'booking' => $booking->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            $errors = $this->validator($request->all());
            if (count($errors)) {
                return response()->json([
                    'errors' => $errors
                ], 422);
            }
            
            $booking->update($request->only($booking->getFillable()));

            return response()->json([
                'booking' => $booking->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
