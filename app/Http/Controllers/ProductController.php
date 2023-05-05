<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Stripe\Exception\CardException;
use Stripe\Stripe;
use App\Models\Product; 
use Auth;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Crypt;

class ProductController extends Controller
{
    private $stripe;
    public function __construct()
    {
       $this->stripe = new StripeClient (config('services.stripe.secret'));
    }

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function checkout($id){
        try {
            $id = Crypt::decryptString($id);
            $productData = Product::find($id);    
        } catch (DecryptException $e) {
            return redirect()->route('product-list')->with('error','Invalid Product.');
        }
        
        return view('products.checkout', compact('productData'));
    }

    public function success(){
        return view('products.success');
    }

    public function show(Product $product)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret')); // replace with your API key
        $product = \Stripe\Product::create([
        'name' => $product->name,
        'type' => 'service',
        ]);

        $price = \Stripe\Price::create([
        'product' => $product->id,
        'unit_amount' => $product->price * 100,
        'currency' => 'inr',
        ]);

        $plan_id = $price->recurring->plan;
        return view('products.show', compact('product','plan_id'));
    }

    /* Payment process for stripe */
    public function purchase(Request $request){
        if($request->isMethod('post')){ 
            /* Getting a current logged user details */
            $userdata= Auth::user();

            /* Getting a stripe payment related request form checkout page */
            $paymentMethod = $request->payment_method;
            $productInfo = $request->productInfo;

            
            /* Checking a product is valid or not */
            try {
                $pid=Crypt::decryptString($productInfo);
                $productData = Product::find($pid);    
            } catch (DecryptException $e) {
                return redirect()->route('products')->with('error','Product is invalid.');
            }
            
            /* Processing a stripe payment */
            try {
                /* Checking a user is available in stripe otherwise creating a new user into stripe */
                if (is_null($userdata->stripe_id)) {
                    $createUserOptions = [
                        'name' => $userdata->name,
                        'address' => $userdata->address
                    ];
                    $userdata->createOrGetStripeCustomer($createUserOptions);
                }

                /* Updating a default payment method in stripe for logged user */
                $userdata->updateDefaultPaymentMethod($paymentMethod);

                /*Charging a amout through a credit card */
                $orderNumber=Order::createOrderNumber();
                
                $payment=$userdata->charge($productData->price * 100, $paymentMethod,['description'=>$productData->description,"metadata" => ["orderNumber" => $orderNumber], ['off_session' => true]]);    
               
                /* if payment is success to redirect a success page otherwise displaying a error in the checkout page */
                if($payment->status=='succeeded'){
                    $orderDetails=[
                        'orderNumber'=>$orderNumber,
                        'userId'=>$userdata->id,
                        'productId'=>$pid,
                        'price'=>$productData->price,
                        'status'=>$payment->status,
                        'paymentIntentId'=>$payment->id
                    ];
                    $newOrderCreated=Order::storeOrder($orderDetails);
                    if($newOrderCreated==1){
                        return redirect()->route('success')->with('success','Your order has been placed successfully.');
                    }
                    return back()->with('error','Your order has been placed.But Something went wrong order storing proces. Please contact SM Team.');
                }
                return back()->with('error','Somgthing went wrong in the payment process. Please try again later or contact to SM team.');
            } catch (\Exception $exception) {
                return back()->with('error', $exception->getMessage());
            }
        }
        return back()->with('error','Invalid request.');
    }
  
}
