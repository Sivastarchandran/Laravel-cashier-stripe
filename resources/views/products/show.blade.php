@extends('layouts.app')

@section('content')
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>
    <form action="{{ route('products.purchase', $product) }}" method="POST">
        @csrf
      

<script src="https://js.stripe.com/v3/"></script>
<button id="checkout-button">Checkout</button>

<script>
  var stripe = Stripe('{{ config('services.stripe.key') }}');

  document.getElementById('checkout-button').addEventListener('click', function() {
    stripe.redirectToCheckout({
      lineItems: [{ price: 'YOUR_PLAN_ID', quantity: 1 }],
      mode: 'subscription',
      successUrl: 'https://example.com/success',
      cancelUrl: 'https://example.com/cancel',
      billingAddressCollection: 'required',
      locale: 'auto'
    })
    .then(function(result) {
      // If `redirectToCheckout` fails due to a browser or network
      // error, display the localized error message to your customer
      // using `result.error.message`.
    });
  });
</script>


   
    </form>
@endsection



