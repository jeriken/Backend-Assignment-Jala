<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseResource;
use App\Models\Product;
use App\Models\Purchase;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse(PurchaseResource::collection(Purchase::with('products')->get()));
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
                'harga'   => 'required|integer',
            ],
            [
                'product_id.required' => 'product_id cannot be null',
                'quantity.required' => 'quantity cannot be null',
                'harga.required' => 'harga cannot be null',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 400, "Bad Request");
        } else {
            try {
                $number = "INV" . date("Ymd", time()) . $request['quantity'] . Auth::id() . rand(100,999);
                $request['invoice'] = $number;
                $activity = Purchase::create($request->all());
                if ($activity != null) {
                    $product = Product::find($request['product_id']);
                    $product->stock = $product->stock + $request['quantity'];
                    $product->save();
                }
                $get = Purchase::with('products')->where('invoice', $number)->first();
                return $this->sendResponse(new PurchaseResource($get));
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
        $activity = Purchase::with('products')->where('invoice', $id)->first();
        if (is_null($activity)) {
            return $this->sendError("Purchase with ID $id Not Found");
        } else {
            return $this->sendResponse(new PurchaseResource($activity));
        }
    }

}
