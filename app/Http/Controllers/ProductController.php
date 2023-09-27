<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products  = Product::all();
        return $this->sendResponse($products->toArray(), 'Products received');
    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return $this->sendResponse($product, 'Product created successfully');
    }
    public function show($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return   $this->sendError('Product not found');
        }
        return $this->sendResponse($product, 'Product found');
    }

    public function update(Request $request, Product $product)
    {
        $validator  = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return $this->sendResponse($product, 'Product created successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse($product, 'Product deleted');
    }
}
