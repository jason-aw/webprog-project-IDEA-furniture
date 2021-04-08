@extends('layouts.app')

@section('content')

<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="container" style="background-color:#D6AFA6">
        <div class="row p-0">
            <div class="col-md-6 p-0">
                <img src="{{ asset('storage/images/'. $product->productType->name. '/' .$product->image) }}" style = "height:70vh; width:100%" onerror="noImage(this);">
            </div>
            <div class="col-md-6 p-2">
                <div class = "card p-2">
                    <div class="card-body">
                        <h3 class="card-title">{{ $product->name }}</h3>
                        <p class="card-title">{{ $product->description }}</p>
                        <h3 class="card-text">Rp {{ number_format ($product->price, $decimals = 2 , $dec_point = "," , $thousands_sep = "." ) }}</h3>
                        <h5 class="card-text">Stock: {{ $product->stock }}</h5>

                        @if(Auth::check() && Auth::user()->role == 'member')
                        <hr style="width:100%;  background-color:gray;">
                            <form action="{{ url('shopping-cart') }}" method="POST">
                                @csrf
                                <label style="display:inline-block;" for="qty">{{ __('Quantity') }}</label>
                                <input type="hidden" name="productID" value="{{ $product->id }}">
                                <div>
                                    <input id="qty" style="display:inline-block;" type="number" class="form-control @error('qty') is-invalid @enderror" name="qty" value="{{ old('qty') }}" autofocus>
                                    @error('qty')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-secondary btn-block" onclick="return confirm('Add this Product?')">{{ __('Add to cart') }}</a>
                            </form>                            
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection