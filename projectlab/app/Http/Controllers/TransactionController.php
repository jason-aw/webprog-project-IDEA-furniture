<?php

namespace App\Http\Controllers;

use App\CartItem;
use App\DetailTransaction;
use App\HeaderTransaction;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        // only members can access
        $this->middleware('role:member');
    }
    
    public function checkout()
    {
        // get user's cart item
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->get();
        
        $errors = array();

        // check for cart items quantity
        foreach($cartItems as $cartItem){
            if($cartItem->qty > $cartItem->product->stock){
                array_push($errors, 'Product '. $cartItem->product->name .' currently doesn\'t have enough quantity for your purchase, please update the quantity. Current stock: '. $cartItem->product->stock);
            }
        }

        // if there are any errors
        if(!empty($errors)){
            return redirect()->back()->withErrors($errors);
        }
        
        // create header transaction
        $headerTransaction = HeaderTransaction::create([
            'user_id' => $user->id,
            'transaction_date' => date('Y-m-d'),
        ]);
        
        foreach($cartItems as $cartItem) {
            // create detail transaction
            DetailTransaction::create([
                'header_transaction_id' => $headerTransaction->id,
                'product_id' => $cartItem->product->id,
                'qty' => $cartItem->qty
            ]);
            
            // subtract stock from product
            $product = Product::find($cartItem->product_id);
            $product->stock -= $cartItem->qty;
            $product->save();

            // delete cart item
            $cart = CartItem::where([['user_id', $cartItem->user_id], ['product_id', $cartItem->product->id]])->first();
            $cart->delete();
        }

        // return success message
        return redirect()->back()->with('status', 'Checkout successful!');
    }

    public function index()
    {
        $transactions = HeaderTransaction::all();
        return view('transaction-history', compact('transactions'));
    }
}
