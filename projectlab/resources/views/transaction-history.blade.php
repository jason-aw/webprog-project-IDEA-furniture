@extends('layouts.app')

@section('content')
<div class="container">

    <div class="text-center">
        <h1 style="font-size: 250%;">TRANSACTION HISTORY</h1>
    </div>

    @if(!$transactions->isEmpty())
        @foreach($transactions as $transaction)
        <h4 class="p-2 m-0" style="background-color: #E9917C;">Transaction Date: {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('D, d M Y') }}</h4>
        <table class="table table-hover table-condensed">
            <thead style=" background-color:#D6AFA6;">
            <tr>
                <th style="width:40%">Product</th>
                <th style="width:15%">Price</th>
                <th style="width:18%">Quantity</th>
                <th style="width:25%">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @php                    
                $total = 0
            @endphp
            @foreach($transaction->detailTransactions as $detailTransaction)
            <tr>
                <td data-th="Product">
                    <div class="container pl-0">
                    <div class="row">
                        <div class="col-4"><img src="{{ asset('storage/images/'. $detailTransaction->product->productType->name. '/' .$detailTransaction->product->image) }}" class="img-fluid" onerror="noImage(this);"/></div>
                        <div class="col-8">
                            <h4 class="mt-3">{{ $detailTransaction->product->name }}</h4>
                            <p class="mt-3">{{ $detailTransaction->product->description }}</p>
                        </div>
                    </div>
                    </div>
                </td>
                <td data-th="Price">
                    <div class="mt-3">
                        Rp {{ number_format ($detailTransaction->product->price, $decimals = 2 ,  $dec_point = "," ,  $thousands_sep = "." ) }}
                    </div>    
                </td>
                <td data-th="Quantity">
                    <div class="mt-3">
                        {{ $detailTransaction->qty }}
                    </div>
                </td>
                @php
                    $subtotal = $detailTransaction->product->price * $detailTransaction->qty;
                    $total += $subtotal;  
                @endphp
                <td data-th="Subtotal">
                    <div class="mt-3">
                    Rp {{ number_format ($subtotal, $decimals = 2 ,  $dec_point = "," ,  $thousands_sep = "," ) }}
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2"></td>
                <td><strong>TOTAL</strong></td>
                <td><strong>Rp {{ number_format ($total, $decimals = 2 ,  $dec_point = "," ,  $thousands_sep = "." ) }}</strong></td>
            </tr>
            </tfoot>
        </table>
        @if($loop->last)
            <a href="{{ url('/') }}" class="btn btn-secondary"><i class="fas fa-chevron-left"></i> Back to Shopping</a>
        @endif
        @endforeach
    @else
    <div class="text-center">
        <h1 style="font-size: 175%;">You don't have any transactions yet.</h1>
        <a href="{{ url('/') }}" class="btn btn-secondary"><i class="fas fa-chevron-left"></i> Back to Shopping</a>
    </div>
    @endif
</div>
@endsection