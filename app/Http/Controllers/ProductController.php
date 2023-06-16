<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'name' => 'required|min:3|max:32',
            'description' => 'required|min:256|max:1024',
            'price' => 'required|numeric|gt:0',
            'quantity' => 'required|integer|not_in:0',
            'stock' => 'boolean',
            'images' => 'required',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id'
        ]);
    
        if ($data->fails()) {
            return response()->json($data->errors(), 400);
        }
        
        $quantity = $request->input('quantity');
        $stock = ($quantity > 0);
        
        $file = $request->file('images');
        $imageName = uniqid(time().'_').'.'.$file->extension();
        $imagePath = $file->storeAs('public/images/products', $imageName);        
        
        $productData = $data->validated();
        $productData['stock'] = $stock;
        $productData['images'] = Storage::url($imagePath);

        // dd($productData);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Producto con id '.$id.' no encontrado'], 404);
        }
        
        $data = Validator::make($request->all(), [
            'name' => 'min:3|max:32',
            'description' => 'min:256|max:1024',
            'price' => 'numeric|gt:0',
            'quantity' => 'integer|not_in:0',
            'images' => 'sometimes',
            'category_id' => 'exists:categories,id'
        ]);

        if ($data->fails()) {
            return response()->json($data->errors(), 400);
        }

        $quantity = $request->input('quantity');
        $stock = is_null($quantity) ? $product->stock : ($quantity > 0);

        $file = $request->file('images');
        if ($file) {
            $imageName = uniqid(time() . '_') . '.' . $file->extension();
            $imagePath = $file->storeAs('public/images/products', $imageName);
    
            // Eliminar la imagen anterior si existe
            if ($product->images) {
                Storage::delete(str_replace('/storage', 'public', $product->images));
            }
    
            $product->images = Storage::url($imagePath);
        }

        $product->name = $request->input('name', $product->name);
        $product->description = $request->input('description', $product->description);
        $product->price = $request->input('price', $product->price);
        $product->quantity = $request->input('quantity', $product->quantity);
        $product->stock = $stock;
        $product->category_id = $request->input('category_id', $product->category_id);

        $product->save();

        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if( $product ) {
            if( $product->images ) {
                Storage::delete(str_replace('/storage', 'public', $product->images));
            }
            Product::destroy($id);
            return response()->json(['success' => 'Producto '.$id.' eliminado correctamente'], 200);
        }

        return response()->json(['error' => 'Producto con id '.$id.' no encontrado'], 404);
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
