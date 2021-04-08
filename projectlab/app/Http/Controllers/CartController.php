<?php

namespace App\Http\Controllers;

use App\CartItem;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        // only member can access
        $this->middleware('role:member');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get current user
        $user = Auth::user();

        $cartItems = CartItem::where('user_id', $user->id)->get();

        return view('shopping-cart', ['cartItems' => $cartItems]);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // get product
        $product = Product::find($request->productID);
        
        // get current user
        $user = Auth::user();
        
        // if cart item already exists
        $cartItem = CartItem::where([['user_id', $user->id], ['product_id', $product->id]])->first();

        if($cartItem){
            // validate input
            $this->validate($request, [
                'qty' => ['required', 'min:1', 'numeric', 'max:' . ($product->stock - $cartItem->qty)],
            ],[
                'qty.max' => 'You already have '. $cartItem->qty.' of this product in your cart, adding '. $request->qty . ' more will exceed our current stock!'
            ]);

            // add qty to previous qty if cart item already exists
            $cartItem->qty = $cartItem->qty + $request->qty;
            $cartItem->save();
        } else{
            // validate input
            $this->validate($request, [
                'qty' => ['required', 'min:1', 'numeric', 'max:' . ($product->stock)],
            ], [
                'qty.max' => 'Sorry, we don\'t have that amount of stock currently.'
            ]);

            // else create new cart item
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'qty' => $request->qty,
            ]);
        }

        // return message
        return redirect()->back()->with('status', 'Product ' . $product->name . ' has been added to your cart!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product_id)
    {
        // get current user
        $user = Auth::user();

        // get cart item
        $cartItem = CartItem::where([['user_id', $user->id], ['product_id', $product_id]])->first();

        // get product
        $product = Product::find($product_id);

        // validate input
        $this->validate($request, [
            'qty' => ['required', 'min:1', 'numeric', 'max:' . ($product->stock)],
        ]);

        // Update model
        $cartItem->qty = $request->qty;
        $cartItem->save();

        // return message
        return redirect()->back()->with('status', 'Product ' . $cartItem->product->name . ' quantity has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id)
    {
        // get current user
        $user = Auth::user();

        // get cart item
        $cartItem = CartItem::where([['user_id', $user->id], ['product_id', $product_id]])->first();
        
        $cartItem->delete();
        return redirect()->back()->with('status', 'Product ' . $cartItem->product->name . ' has been removed from your cart.');
    }

    public function deleteAll(){
        // get user's cart item
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->get();
        
        foreach($cartItems as $cartItem){
            $cartItem->delete();
        }
        
        return redirect()->back()->with('status', 'All products have been removed from your shopping cart.');
    }
}
