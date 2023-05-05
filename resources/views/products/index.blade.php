@extends('layouts.app')
<style>
.card_image {
    width: 200px!important;
    height: 250px;
    padding: 10%;
    margin: 0 auto;
    }
</style>


@section('content')
<div class="container">
        <div class="row">
        <div class=""><h5>{{ __('Products') }}</h5></div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if(!$products->isEmpty())
                        <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="{{ $product->image }}" class="card-img-top card_image" alt="{{ $product->name }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">{{ $product->description }}</p>
                                        <p class="card-text"> $ {{ $product->price }}</p>
                                        <a class="btn btn-primary" href="{{ route('checkout',['id'=>Crypt::encryptString($product->id)]) }}">Buy now</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>  
                    @else
                        <p class="text-center"><b>No Products available.</b></p>                     
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

