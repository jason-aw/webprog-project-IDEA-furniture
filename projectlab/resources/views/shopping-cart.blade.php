@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())    
    <div class="alert alert-danger" role="alert">
        <ul class="m-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="text-center">
        <h1 style="font-size: 250%;">SHOPPING CART</h1>
    </div>

    @if(!$cartItems->isEmpty())
    <form action="{{ url('shopping-cart/delete-all') }}" method="POST" class="hidden my-3">
        @csrf
        <button type="submit" class="btn btn-danger" onclick="return confirm('Remove all items from your cart?')">Remove All Items from Cart</button>
    </form>
    <table class="table table-hover table-condensed" style="border-bottom-color:#000000">
        <thead style="background-color:#D6AFA6;">
        <tr>
            <th style="width:40%">Product</th>
            <th style="width:15%">Price</th>
            <th style="width:18%">Quantity</th>
            <th style="width:22%">Subtotal</th>
            <th style="width:5%"></th>
        </tr>
        </thead>
        <tbody>
        @php                    
            $total = 0
        @endphp
        @foreach($cartItems as $cartItem)
        <tr style="border-bottom-color:#000000">
            <td data-th="Product" class="">
                <div class="container pl-0">
                <div class="row">
                    <div class="col-4">
                        <img src="{{ asset('storage/images/'. $cartItem->product->productType->name. '/' .$cartItem->product->image) }}" class="img-fluid" onerror="noImage(this);">
                    </div>
                    <div class="col-8">
                        <h4 class="mt-3">{{ $cartItem->product->name }}</h4>
                        <p class="mb-3">{{ $cartItem->product->description }}</p>
                    </div>
                </div>
                </div>
            </td>
            <td data-th="Price">
                <div class="mt-3">
                    Rp {{ number_format ($cartItem->product->price, $decimals = 2 ,  $dec_point = "." ,  $thousands_sep = "," ) }}
                </div>
            </td>
            <td data-th="Quantity">
                <form class="mt-2" action="{{ url('shopping-cart/'.$cartItem->product_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input style="display:inline-block; width: 50%;" type="number" class="form-control text-center" value="{{ $cartItem->qty }}" name="qty">
                    <button style="display:inline-block;"class="btn btn-info btn-sm"><i class="fas fa-redo-alt" style="color: white;"></i></button>
                </form>
            </td>
            @php
                $subtotal = $cartItem->product->price * $cartItem->qty;
                $total += $subtotal;  
            @endphp
            <td data-th="Subtotal">
                <div class="mt-3">
                    Rp {{ number_format ($subtotal, $decimals = 2 ,  $dec_point = "," ,  $thousands_sep = "." ) }}</td>
                </div>
            <td data-th="">
                <form class="mt-3" action="{{ url('shopping-cart/'.$cartItem->product_id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Remove product from cart?')"><i class="far fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td class="text-right"><strong>TOTAL</strong></td>
            <td><strong>Rp {{ number_format ($total, $decimals = 2 ,  $dec_point = "," ,  $thousands_sep = "." ) }}</strong></td>
        </tr>
        <tr>
            <td><a href="{{ url('/') }}" class="btn btn-warning"><i class="fas fa-chevron-left"></i> Continue Shopping</a></td>
            <td colspan="3"></td>
            <td>
                <form action="{{ url('checkout') }}" method="POST" class="hidden">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Checkout now?')"><i class="fas fa-shopping-cart"></i> Checkout</button>
                </form>
            </td>
        </tr>
        </tfoot>
    </table>
    @else
    <div class="text-center">
        <h1 style="font-size: 175%;">You don't have any items in the cart</h1>
        <a href="{{ url('/') }}" class="btn btn-secondary"><i class="fas fa-chevron-left"></i> Back to Shopping</a>
    </div>

    @endif
</div>
@endsection