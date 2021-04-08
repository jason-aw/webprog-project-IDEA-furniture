<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        // CRUD operations only accessible by admin
        $this->middleware('role:admin')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ProductType $productType)
    {
        // search query
        if($request->search != null){
            $products = Product::where([
                ['product_type_id', $productType->id],
                ['name', 'like', "%$request->search%"]
            ])->paginate(10);
        } else {
            $products = Product::where('product_type_id', $productType->id)->paginate(10);
        }
        
        return view('product.index', ['products' => $products, 'productType' => $productType]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create', ['productTypes' => ProductType::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        // validate input
        $this->validate($request, [
            'name' => 'required|min:5|max:255',
            'productType' => 'required|exists:product_types,id',
            'stock' => 'required|integer|min:1',
            'price' => 'required|integer|min:1',
            'imgupload' => 'file|mimes:jpeg,png,gif',
            'description' => 'required|min:15',
        ]);
        
        $productType = ProductType::find($request->productType);

        // if image exists
        if($request->hasFile('imgupload')){
            // generate file name
            $extension = $request->file('imgupload')->extension();
            $imgname = date('dmYHis') . '_' . $request->name . '.' . $extension;
            
            // store the image
            Storage::putFileAs('public/images/'. strtolower($productType->name), $request->file('imgupload'), $imgname);
        } else {
            $imgname = null;
        }

        // create product
        Product::create([
            'name' => $request->name,
            'image' => $imgname,
            'product_type_id' => $request->productType,
            'stock' => $request->stock,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        // return success message
        return redirect()->back()->withSuccess("Product " . $request->name . " as Product Type " . $productType->name." has been successfully inserted!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $productType = ProductType::find($product->product_type_id);

        return view('product.show', ['product' => $product, 'productType' => $productType]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('product.edit', ['product' => $product, 'productTypes' => ProductType::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // validate input
        $this->validate($request, [
            'name' => 'required|min:5|max:255',
            'productType' => 'required|exists:product_types,id',
            'stock' => 'required|integer|min:1',
            'price' => 'required|integer|min:1',
            'imgupload' => 'file|mimes:jpeg,png,gif',
            'description' => 'required|min:15',
        ]);
        
        $productType = ProductType::find($request->productType);

        // if request has image
        if($request->hasFile('imgupload')){
            // generate file name
            $extension = $request->file('imgupload')->extension();
            $imgname = date('dmYHis') . '_' . $request->name . '.' . $extension;

            // delete product image from storage, if image exists
            if($product->image != null) {
                Storage::delete('public/images/' . $product->productType->name. '/' .$product->image);
            }
            
            // store new image
            Storage::putFileAs('public/images/'.strtolower($productType->name), $request->file('imgupload'), $imgname);
        } else {
            // if delete current image
            if($product->image != null && $request->deleteImage) {
                Storage::delete('public/images/' . $product->productType->name. '/' .$product->image);
                $imgname = null;
            }

            // no changes otherwise
            $imgname = $product->image;
        }

        // update model
        $product->name = $request->name;
        $product->image = $imgname;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        // return success message
        return redirect()->back()->withSuccess("Product " . $request->name . " has been successfully updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // delete model
        $product->delete();
        
        // delete product image from storage, if image exists
        if($product->image != null) {
            Storage::delete('public/images/' . $product->productType->name. '/' .$product->image);
        }
        
        // return message
        return redirect()->back()->with('status', 'Product ' . $product->name . ' has been deleted!');
    }
}