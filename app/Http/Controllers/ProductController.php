<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse(ProductResource::collection(Product::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama'   => 'required',
                'harga'   => 'required|integer',
            ],
            [
                'nama.required' => 'email cannot be null',
                'harga.required' => 'harga cannot be null',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 400, "Bad Request");
        } else {
            try {
                $request['sku'] = ".";
                $activity = Product::create($request->all());
                return $this->sendResponse(new ProductResource($activity));
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
        $activity = Product::find($id);
        if (is_null($activity)) {
            return $this->sendError("Product with ID $id Not Found");
        } else {
            return $this->sendResponse(new ProductResource($activity));
        }
    }

    /**
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $activity = Product::find($id);
            if (is_null($activity)) {
                return $this->sendError("Product with ID $id Not Found");
            } else {
                $activity->update($request->all());
                return $this->sendResponse(new ProductResource($activity->fresh()));
            }
        } catch (QueryException $e) {
            return $this->sendError($e->getMessage(), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $activity = Product::find($id);
            if (is_null($activity)) {
                return $this->sendError("Product with ID $id Not Found");
            } else {
                $activity->delete();
                return $this->sendResponse((object)array());
            }
        } catch (QueryException $e) {
            return $this->sendError($e->getMessage(), $e->getMessage());
        }
    }
}
