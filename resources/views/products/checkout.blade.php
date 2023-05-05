@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h2>{{ __('Checkout') }}<h2></div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="col-md-12">
                        <h3>Product Details</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-secondary">
                                    <th width="10%">Product Image</th>
                                    <th width="20%">Name</th>
                                    <th width="50%">Description</th>
                                    <th width="20%">Price</th>            
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                    <img width="50" src="{{ $productData->image }}" class="card-img-top" alt="{{ $productData->name }}">
                                    </td>
                                    <td>{{ $productData->name }}</td>
                                    <td>{{ $productData->description }}</td>
                                    <td>{{ $productData->PriceWithSymbol }}</td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right"><b>Total</b></td>
                                    <td colspan="2" class="text-left"><b>{{ $productData->PriceWithSymbol }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-8">
                                <form id="paymentForm" method="POST" action="{{ route('purchase') }}" class="card-form mt-3 mb-3">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3>Pay using Credit Card</h3>
                                            <div class="row"> 
                                                <div class="col-md-12">                                    
                                                    <div class="form-group cardHName">
                                                        <label for="name_on_card">Card Holder Name<span class="required-symbol">*</span></label>              
                                                        <input id="name_on_card" class="form-control" name="name_on_card" type="text" value="{{ old('name_on_card') }}" autocomplete="name_on_card" autofocus />              
                                                    </div>
                                                </div>
                                                <div class="col-md-12">                                    
                                                    <div class="form-group cardHAddress">
                                                        <label for="address_on_card">Card Holder Address<span class="required-symbol">*</span></label>              
                                                        <input id="address_on_card" class="form-control" name="address_on_card" type="text" value="{{ old('address_on_card') }}" autocomplete="address_on_card" autofocus />              
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Card Number<span class="required-symbol">*</span></label>
                                                        <div id="card-number"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Expiration Date (MM/YY)<span class="required-symbol">*</span></label>
                                                        <div id="card-expiry"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>CVC<span class="required-symbol">*</span></label>
                                                        <div id="card-cvc"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div id="card-errors" role="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>            
                                    <div class="row">
                                        <div class="col-md-6">
                                        <div class="form-group mt-3 text-center">
                                            <input type="hidden" name="payment_method" class="payment-method">
                                            <input type="hidden" name="productInfo" value="{{ Crypt::encryptString($productData->id) }}" />
                                            <a id="card-button" class="btn btn-primary pay">Place Order</a>
                                        </div>
                                    </div>                                    
                                </form>
                            </div>
                        </div>
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Alert popup --}}
<div class="modal fade" id="default-alert">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close dfmodalClose" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p class="default-msg"></p>
            <p class="dfPopupBtnSec">
                <a class="btn btn-dark" data-dismiss="modal">Ok</a>
            </p>
        </div>
    </div>
</div>
<div class="default-loader" style="display:none">
    <img width="60px" src="{{ asset('images/loader.gif') }}" alt="loader" />
</div>
@endsection

<style>
    .default-loader{background: rgba(0,0,0,0.3); width: 100%; height: 100%; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 2; cursor: pointer;}
    .default-loader img{position: absolute; left: 0; right: 0; margin: auto; top: 0; bottom: 0;}
    .required-symbol{color:red;}
    .StripeElement {display: block; width: 100%; height: calc(1.6em + 0.75rem + 2px); padding: 0.375rem 0.75rem; font-size: 0.9rem; font-weight: 400; line-height: 1.6; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0.25rem; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; }
    .StripeElement--invalid {border-color: #fa755a;}
    .StripeElement--focus {box-shadow: 0 1px 3px 0 #cfd7df;}    
    .StripeElement--webkit-autofill {background-color: #fefde5 !important;}
    input.error{border-color: #fa755a;}
    #card-errors,.error{color:red;}    
    input.error:focus,input:focus{box-shadow: 0 1px 3px 0 #cfd7df; border-color: #fa755a !important;}    
</style>
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>    
    <script>
      var cardButton;

      const stripe = Stripe('{{ config('services.stripe.key') }}');

      /* custom styles for stripe element */
        const customStripeStyles={
            base: {
                'color': '#9e9e9e',    
                '::placeholder': {
                    'color': '#d9d9d9',
                    'fontSize':'14px',
                    'opacity': '1',
                    
                },
            }
        };
      $(document).ready(function(){

         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const elements = stripe.elements();
        const cardNumber = elements.create('cardNumber',{style: customStripeStyles});
        cardNumber.mount('#card-number');
        const cardExpiry = elements.create('cardExpiry',{style: customStripeStyles});
        cardExpiry.mount('#card-expiry');
        const cardCvc = elements.create('cardCvc',{style: customStripeStyles});
        cardCvc.mount('#card-cvc');
        var cardButton = document.getElementById('card-button');
 
        /* creating a stripe element */
        
        const cardHolderName = document.getElementById('name_on_card');
        const cardHolderAddress = document.getElementById('address_on_card');

        let getNewSetupIntent=()=>{        
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{route('ajaxintent')}}",
                    type: 'POST',
                    success: function (data) {                    
                        resolve(data.intent_id)
                    },
                    error: function (error) {  
                        reject(error.msg)
                    },
                });
            });
        }

        cardButton.addEventListener('click', async (e) => {
            $('.default-loader').show();
            getNewSetupIntent().then((intent_id) => {
                let clientSecret = intent_id;
                stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: cardNumber,
                                billing_details: { name: cardHolderName.value,
                                address: {
                                line1: cardHolderAddress.value,
                                city: 'City',
                                postal_code: '12345',
                                state: 'State',
                                country: 'US',
                                }
                            }   
                        }
                    }
                ).then(function (result) {
                    $('.default-loader').hide();
                    if(result.error){
                        $('#card-errors').text(result.error.message)
                    } 
                    else{
                        $('#card-errors').empty();
                        $('.payment-method').val(result.setupIntent.payment_method);
                        $("#paymentForm").submit();
                    }
                });
            }).catch((intent_error) => {
                $('.default-msg').html(intent_error);
                $('#default-alert').modal('show');
                $('.default-loader').hide();
            });
        });
        
      })     
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            /* Validating a checkout page fields */
            $("#paymentForm").validate({
                rules: {
                    name_on_card: {required: true},
                    address_on_card: {required: true},             
                },
                messages: {
                    name_on_card: "Card holder name is required.",
                    address_on_card: "Card holder address is required.",        
                },
                submitHandler: function(form) {
                    $('.default-loader').show();
                    form.submit();            
                }
            });
        });
    </script>

