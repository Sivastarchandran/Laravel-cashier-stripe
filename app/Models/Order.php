<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Str;

class Order extends Model
{
    /* The table associated with the model. */
    protected $table = 'orders';

    /* Mass assignable */
    protected $fillable = [
        'order_number', 'user_id', 'product_id','price','status','payment_intent_id'
    ];

    /* Model Relationships */
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    /* Creating order number */
    public static function createOrderNumber(){
        $currentTime = \Carbon\Carbon::now()->timestamp;
        $ordersObject = new Order;        
        $orderInfo = DB::select("SHOW TABLE STATUS LIKE '".$ordersObject->table."'");
        $getNextOrderId = $orderInfo[0]->Auto_increment;
        $length = Str::length($getNextOrderId);
        $orderNumber ='ORDER-'.$currentTime;
        if($length<5){
            for($inc=1; $inc<5; $inc++){
                $orderNumber .='0';
            }
        }
        return $orderNumber.$getNextOrderId;
    }

    /* Getting product price with symbol. */
    public function getOrderPriceWithSymbolAttribute(){
        return '&#8377 '.$this->price;
    }

    /* Placing an order */
    public static function storeOrder($orderInfo){
        DB::beginTransaction();
        try{
            Order::create([
                'order_number'=>$orderInfo['orderNumber'],
                'user_id'=>$orderInfo['userId'],
                'product_id'=>$orderInfo['productId'],
                'price'=>$orderInfo['price'],
                'status'=>$orderInfo['status'],
                'payment_intent_id'=>$orderInfo['paymentIntentId']
            ]);
            DB::commit();           
            return 1;            
        } catch(Exception $ex) {
            DB::rollback();
            return 0;
        }
    }
}
