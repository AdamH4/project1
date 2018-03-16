<?php

namespace App\Http\Controllers;

use App\Mail\CardOrder;
use App\Mail\Order;
use App\Product;
use App\Transaction;
use App\User;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Gloudemans\Shoppingcart\Cart;

class CartController extends Controller
{
    public function index(){
        if (auth()->check()){
            $userId = auth()->user()->id;
            $user = new User();
            $u = $user->verified($userId);
            $cart = app(Cart::class);
            $products = $cart->instance($userId)->content();
            $total = $cart->instance($userId)->subtotal();
        }
        return view('shop.cart.index',compact('products','total','u'));
    }

    public function store($id){
        $quantity = request('quantity');
        $userId= auth()->user()->id;
        $p = Product::find($id);
        $cart = app(Cart::class);
        $cart->instance($userId)->add([
            'id'=>$p->id,
            'name'=>$p->name,
            'qty'=>$quantity,
            'price'=>$p->price,
            'options'=>[
               'picture'=>$p->picture,
            ],
        ]);
        return back();
    }


    public function deleteAll(){
        $userId = auth()->user()->id;
        $cart = app(Cart::class);
        $cart->instance($userId)->destroy();
        return redirect()->back();
    }

    public function deleteOne($id){
        $userId = auth()->user()->id;
        $cart = app(Cart::class);
        $cart->instance($userId)->remove($id);
        return redirect()->back();
    }

    public function plus($id){
        $userId = auth()->user()->id;
        $cart = app(Cart::class);
        $item = $cart->instance($userId)->get($id);
        $cart->instance($userId)->update($id,$item->qty + 1);
        return redirect()->back();
    }

    public function minus($id){
        $userId = auth()->user()->id;
        $cart = app(Cart::class);
        $item = $cart->instance($userId)->get($id);
        $cart->instance($userId)->update($id,$item->qty - 1);
        return redirect()->back();
    }

    public function selectPayment($type){
        $user = auth()->user();
        $cart = app(Cart::class);
        $products = $cart->instance($user->id)->content();
        $total = $cart->instance($user->id)->subtotal();
        return view('shop.cart.select-payment',compact('total','products','type'));
    }

    public function selectDelivery(){
        $user = auth()->user();
        $cart = app(Cart::class);
        $products = $cart->instance($user->id)->content();
        $total = $cart->instance($user->id)->subtotal();
        return view('shop.cart.select-delivery',compact('products','total'));
    }

    public function card($type){
        $user = auth()->user();
        $cart = app(Cart::class);
        $products = $cart->instance($user->id)->content();
        $total = $cart->instance($user->id)->subtotal();
        $information = User::hasInformation($user->id);
        return view('shop.cart.card',compact('total','products','type','information'));
    }

    public function checkout($type){
        $user = auth()->user();
        $cart = app(Cart::class);
        $total = $cart->instance($user->id)->subtotal();
        $products = $cart->instance($user->id)->content();
        $information = request()->all();
        try {
            Stripe::charges()->create([
                'amount' => $total,
                'currency' => 'EUR',
                'source' => $information['stripeToken'],
                'description' => 'Payment from your customer',
                'receipt_email' => 'adam.harnusek@gmail.com',
                'metadata' => [
                    'data1' => 'metadata 1',
                    'data2' => 'metadata 2',
                    'data3' => 'metadata 3',
                ],
            ]);
            \Mail::to($user)->send(new cardorder($products,$information,$total,$type));
            $transaction = new Transaction();
            $transaction->addProduct($products, $user->id, $total,'card',$type);
            $cart->instance($user->id)->destroy();
            return view('shop.cart.success', compact('total'));
            } catch(\CardErrorException $e){
                return view('shop.cart.unsuccess')->with('error','Something went wrong try again');
            }
    }

    public function cashOnDelivery($type){
        $user = auth()->user();
        $cart = app(Cart::class);
        $products = $cart->instance($user->id)->content();
        $total = $cart->instance($user->id)->subtotal();
        $information = User::hasInformation($user->id);
        return view('shop.cart.cashOnDelivery',compact('total','products','type','information'));
    }

    public function cashOnDeliveryCheckout($type){
        /*$this->validate(request(),[
           'first_name'=>'required',
           'second_name'=>'required',
           'city'=>'required',
           'street'=>'required',
           'address'=>'required',
           'second_address'=>'required',
           'postcode'=>'required|numeric',
           'phone'=>'required|numeric',
        ]);*/
        $information = request()->all();
        $userId = auth()->user()->id;
        $cart = app(Cart::class);
        $total = $cart->instance($userId)->subtotal();
        $products = $cart->instance($userId)->content();
        \Mail::to(auth()->user())->send(new Order($products,$information,$total,$type));
        $transaction = new Transaction();
        $transaction->addProduct($products, $userId, $total,'cash',$type);
        $cart->instance($userId)->destroy();
        return view('shop.cart.success',compact('total'));
    }
}
