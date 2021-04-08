@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center">
        <h1 style="font-size: 250%;">PRODUCT TYPE</h1>
    </div>

    <hr style="width:90%;  background-color:gray;">

    <div class="container mb-5">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @foreach($productTypes as $productType)
            @if($loop->index % 4 === 0)
                <div class="row row-cols-4 my-2">
            @endif
            <div class="col-3 p-2" style="width: 19vw;">
                <div class="card text-center" style="width: 100%; background-color: #D6AFA6;">
                
                    <a href="{{ url('product-type/'.$productType->id.'/products') }}">
                        <img src="{{ asset('storage/images/thumbnail/'.$productType->image) }}" class="card-img-top" onerror="noImage(this);">
                    </a>

                    <div class="card-body" style="color: #D6D6D6;">
                        <a style="width: 100%; color: black;" href="{{ url('product-type/'.$productType->id.'/products') }}" class="card-text">{{ $productType->name }}</a>
                    </div>

                    @if(Auth::check())
                        @if(Auth::user()->role == 'admin')
                            <div class="p-0 m-0">
                                <a style="display:inline-block; width: 40%;" href="{{ url('product-type/'.$productType->id.'/edit') }}" class="btn btn-secondary text-dark btn-sm m-0">Update</a>
                                <form style="display:inline-block; width: 40%;" action="{{ url('product-type/'.$productType->id) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button style="display:inline-block; width: 100%;" class="btn btn-secondary text-dark btn-sm m-0" onclick="return confirm('Are you sure to delete this Product Type?')">Delete</button>
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
    </div>
</div>

@endsection