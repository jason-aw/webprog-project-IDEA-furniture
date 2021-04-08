@extends('layouts.app')

@section('content')

<div class="container">
    <div class="text-center">
        <h1 style="font-size: 250%;">{{ $productType->name }}</h1>
    </div>

    @if(!$products->isEmpty())
    <div style="display: inline-block;">
        <form class="form-inline" class="float-right">
            <!-- SEARCH BAR -->
            <input class="form-control" placeholder="Search" name="search" value="">
            <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    @endif

    <hr style="width:90%;  background-color:gray;">

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    
    @if(!$products->isEmpty())
        @foreach($products as $product)
            @if($loop->index % 4 === 0)
                <div class="row row-cols-4 my-2">
            @endif
            <div class="col-3 p-2" style="width: 19vw;">
                <div class = "card" style="width: 100%; height: 100%; background-color: #D6AFA6;">
                    
                    <a href="{{ url('products/'.$product->id) }}">
                        <img src="{{ asset('storage/images/'. $product->productType->name. '/' .$product->image) }}" class="card-img-top" onerror="noImage(this);">
                    </a>
                    
                    <div class="card-body" style="color: #D6D6D6;">
                        <a href="{{ url('products/'.$product->id) }}" class="card-text" style="width: 100%; color: black; font-size:30px">{{ $product->name }}</a>
                        <p style="color: black; font-size:13px" class="card-title">{{ $product->description }}</p>
                        <h3 style="color: black;" class="card-text">Rp {{ number_format ($product->price, $decimals = 2 , $dec_point = "," , $thousands_sep = "." ) }}</h3>
                    </div>
                    @if(Auth::check())
                        @if(Auth::user()->role == 'admin')
                            <div class="text-center">
                                <a style="display:inline-block; width: 40%;" href="{{ url('products/'.$product->id.'/edit') }}" class="btn btn-secondary text-dark btn-sm">Update</a> 
                                    <form style="display:inline-block; width: 40%;" action="{{ url('products/'.$product->id) }}" method="POST" class="hidden">
                                        @method('DELETE')
                                        @csrf
                                        <button style="display:inline-block; width: 100%;" class="btn btn-secondary text-dark btn-sm" onclick="return confirm('Are you sure to delete this Product?')">Delete</button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @if($loop->iteration % 4 === 0 || $loop->last)
                </div>
            @endif
        @endforeach
            @if(method_exists($products, 'withQueryString'))
                <div class="row">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
    @else
        <div class="text-center">
            <h1 style="font-size: 175%;">No products available. Please check our other product type</h1>
            <a href="{{ url('/') }}" class="btn btn-secondary"><i class="fas fa-chevron-left"></i> Back to Shopping</a>
        </div>
    @endif
</div>
@endsection