<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShopperRequest;
use App\Models\Shopper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ShopperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if ($id) {
            $shoppers = Shopper::find($id);
        } else {
            $shoppers = Shopper::all();
        }

        return response()->json([
            'shoppers' => $shoppers
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
        $validateRequest = new StoreShopperRequest();
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

            $shopper = new Shopper();
            $shopper->fill($request->only($shopper->getFillable()));
            $shopper->save();

            return response()->json([
                'shopper' => $shopper->fresh()
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
            $shopper = Shopper::findOrFail($id);
            
            $errors = $this->validator($request->all());
            if (count($errors)) {
                return response()->json([
                    'errors' => $errors
                ], 422);
            }
            
            $shopper->update($request->only($shopper->getFillable()));

            return response()->json([
                'shopper' => $shopper->fresh()
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
            $shopper = Shopper::findOrFail($id);
            $shopper->delete();

            return response()->json([
                'shopper' => $shopper
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
