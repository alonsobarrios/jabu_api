<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Shopper;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if ($id) {
            $ticket = Ticket::find($id);
        } else {
            $tickets = Ticket::all();
        }

        return response()->json([
            'tickets' => $id ? $ticket : $tickets->map(function($ticket){
                return $ticket->toArray(true);
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
        $validateRequest = new StoreTicketRequest();
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

            $ticket = new Ticket();
            $ticket->fill($request->only($ticket->getFillable()));
            $ticket->save();

            return response()->json([
                'ticket' => $ticket->fresh()
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
            $ticket = Ticket::findOrFail($id);
            
            $errors = $this->validator($request->all());
            if (count($errors)) {
                return response()->json([
                    'errors' => $errors
                ], 422);
            }
            
            $ticket->update($request->only($ticket->getFillable()));

            return response()->json([
                'ticket' => $ticket->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->delete();

            return response()->json([
                'ticket' => $ticket
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function getTickets()
    {
        $tickets = Ticket::doesnthave('booking')->get();
        $shoppers = Shopper::where('status', 1)->orderBy('full_name')->get();

        return response()->json([
            'tickets' => $tickets,
            'shoppers' => $shoppers
        ]);
    }
}
