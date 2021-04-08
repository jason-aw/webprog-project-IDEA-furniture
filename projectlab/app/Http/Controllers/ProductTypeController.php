<?php

namespace App\Http\Controllers;

use App\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\Environment\Console;

class ProductTypeController extends Controller
{
    public function __construct()
    {
        // CRUD operations only accessible by admin
        $this->middleware('role:admin');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product-type.create');
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
            'name' => 'required|min:4',
            'imgupload' => 'file|mimes:jpeg,png,gif',
        ]);
        
        // if image exists
        if($request->hasFile('imgupload')){
            // generate file name
            $extension = $request->file('imgupload')->extension();
            $imgname = date('dmYHis'). '_' . $request->name . '.' . $extension;
            
            // store the image
            Storage::putFileAs('public/images/thumbnail', $request->file('imgupload'), $imgname);
        } else {
            $imgname = null;
        }

        // create subfolder for products images
        Storage::makeDirectory('public/images/' . strtolower($request->name));
        
        // create new product type model
        ProductType::create([
            'name' => $request->name,
            'image' => $imgname
        ]);
        
        // return success message
        return redirect()->back()->withSuccess("Product Type " . $request->name . " has been successfully inserted!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function show(ProductType $productType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductType $productType)
    {
        return view('product-type.edit', ['productType' => $productType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductType $productType)
    {
        // validate input
        $this->validate($request, [
            'name' => 'required|min:4',
            'imgupload' => 'file|mimes:jpeg,png,gif',
        ]);

        // if request has image
        if($request->hasFile('imgupload')){
            // generate file name
            $extension = $request->file('imgupload')->extension();
            $imgname = date('dmYHis'). '_' . $request->name . '.' . $extension;
            
            // delete old image from storage, if image exists
            if($productType->image != null){
                Storage::delete('public/images/thumbnail/' . $productType->image);
            }

            // store new image
            Storage::putFileAs('public/images/thumbnail', $request->file('imgupload'), $imgname);
        } else {
            // if delete current image
            if($productType->image != null && $request->deleteImage){
                Storage::delete('public/images/thumbnail/' . $productType->image);
                $imgname = null;
            }

            // no changes otherwise
            $imgname = $productType->image;
        }

        // rename subfolder for product images, if name is updated
        if(!(strtolower($productType->name) === strtolower($request->name))) {
            Storage::rename('public/images/'. strtolower($productType->name).'/', 'public/images/'. strtolower($request->name).'/');
        }
        
        // update model
        $productType->name = $request->name;
        $productType->image = $imgname;
        $productType->save();
        
        // return success message
        return redirect()->back()->withSuccess("Product Type " . $request->name . " has been successfully updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductType $productType)
    {
        // delete model
        $productType->delete();

        // delete image from storage, if image exists
        if($productType->image != null) {
            Storage::delete('public/images/thumbnail/' . $productType->image);
        }

        // delete product images subfolder
        Storage::deleteDirectory('public/images/' . strtolower($productType->name));
        
        // return message
        return redirect('home')->with('status', 'Product Type ' . $productType->name . ' has been deleted!');
    }
}
