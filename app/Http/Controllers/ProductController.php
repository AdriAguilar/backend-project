<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'description' => 'required|max:512',
            'price' => 'required|numeric|gt:0',
            'quantity' => 'required|integer|not_in:0',
            'stock' => 'boolean',
            'images' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id'
        ]);
    
        if ($data->fails()) {
            return response()->json($data->errors());
        }
    
        $quantity = $request->input('quantity');
        $stock = ($quantity > 0);
    
        $productData = $data->validated();
        $productData['stock'] = $stock;
    
        $product = Product::create($productData);
    
        return response()->json($product, 201);
    }    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id) ?? response()->json(['msg' => 'Producto con id '.$id.' no encontrado'], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    // Relaciones

    public function category($id)
    {
        $product = Product::find($id);
        return $product->category ?? response()->json(['msg' => 'Producto con id '.$id.' no encontrado'], 404);
    }

    public function seller($id)
    {
        $product = Product::find($id);
        return $product->user ?? response()->json(['msg' => 'El usuario con id '.$id.' no es un vendedor'], 404);
    }

    public function purchases($id)
    {
        $purchases = Product::find($id);
        return $purchases->purchases ?? response()->json(['msg' => 'Producto con id '.$id.' no encontrado'], 404);
    }

    public function comments($id)
    {
        $product = Product::find($id);
        return $product->comments ?? response()->json(['msg' => 'Producto con id '.$id.' no encontrado'], 404);
    }
}
