<?php

namespace App\Http\Controllers;

use App\Enums\SalesEnum;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse(SaleResource::collection(Sale::with('products','users')->get()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'product_id'   => 'required|string',
                'quantity'   => 'required|integer',
            ],
            [
                'product_id.required' => 'product_id cannot be null',
                'quantity.required' => 'quantity cannot be null',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 400, "Bad Request");
        } else {
            try {
                $number = "INV" . date("Ymd", time()) . Auth::id() . $request['quantity']  . rand(100,999);
                $request['invoice'] = $number;
                $request['user_id'] = Auth::id();
                $request['status'] = SalesEnum::PENDING;
                Sale::create($request->all());
                $get = Sale::with('products','users')->where('invoice', $number)->first();
                return $this->sendResponse(new SaleResource($get));
            } catch (QueryException $e) {
                return $this->sendError($e->getMessage(), $e->getMessage());
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeAdmin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id'   => 'required|integer',
                'product_id'   => 'required|string',
                'quantity'   => 'required|integer',
            ],
            [
                'user_id.required' => 'user_id cannot be null',
                'product_id.required' => 'product_id cannot be null',
                'quantity.required' => 'quantity cannot be null',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 400, "Bad Request");
        } else {
            try {
                $number = "INV" . date("Ymd", time()) . Auth::id() . $request['quantity']  . rand(100,999);
                $request['invoice'] = $number;
                $request['status'] = SalesEnum::DONE;
                Sale::create($request->all());
                $get = Sale::with('products','users')->where('invoice', $number)->first();
                return $this->sendResponse(new SaleResource($get));
            } catch (QueryException $e) {
                return $this->sendError($e->getMessage(), $e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $activity = Sale::with('products','users')->where('invoice', $id)->first();
        if (is_null($activity)) {
            return $this->sendError("Purchase with ID $id Not Found");
        } else {
            return $this->sendResponse(new SaleResource($activity));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        try {
            $activity = Sale::with('products','users')->where('invoice', $id)->first();
            $activity->status = SalesEnum::DONE;
            $activity->save();
            return $this->sendResponse(new SaleResource($activity->fresh()));
        } catch (QueryException $e) {
            return $this->sendError($e->getMessage(), $e->getMessage());
        }
    }
}
