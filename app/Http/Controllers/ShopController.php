<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrdersItem;
use App\Models\Payment;

class ShopController extends Controller
{
    public function index(){
        $products = Product::with('category')->get();
    //   where('stock','>',0) 
        $categories = Category::all();
        return view('shop.index', compact('products','categories',));
    }
    public function newproduct(){
                $pro=product::with('category')->latest()->limit(5)->get();
return view('shop.lateststock',compact('pro'));
    }
    public function product($slug){
        $product = Product::where('slug',$slug)->firstOrFail();
        return view('shop.product',compact('product'));
    }
    public function productCategory($id){
        $products=Product::where('category_id',$id)->latest()->get();
        return view('shop.productcategory', compact("products"));

    }

    public function addToCart($id){
        Cart::updateOrCreate(
            ['user_id'=>session('user_id'), 'product_id'=>$id],
            ['quantity'=>\DB::raw('quantity + 1')]
        );
        return back()->with('success','Added to cart');
    }
    public function removeproduct($id){
        Cart::where('product_id', $id)->delete();
                return back();

    }

    public function cart(){
        $cartItems =Cart::with('product')->where('user_id',session('user_id'))->get();
        return view('shop.cart', compact('cartItems'));
    }

    public function updateCart(Request $request, $id){
        Cart::where('id', $id)->update(['quantity'=>$request->quantity]);
        return back();

    }
    public function checkout(){
        $cartItems = Cart::with('product')->where('user_id',session('user_id'))->get();

        if($cartItems->isEmpty())return redirect('/cart');
        return view('shop.checkout', compact('cartItems'));
    }

    public function placeOrder(Request $request){
        $request->validate([
            'shipping_address'=>'required',
            'phone'=>'required',
            'payment_method'=>'required'
        ]);
        $cartItems = Cart::with('product')->where('user_id',session('user_id'))->get();
        $total = $cartItems->sum('total');

        $order = Order::create([
            'user_id'=>session('user_id'),
            'order_number'=>'ORD'.time(),
            'total_amount'=>$total,
            'shipping_address'=>$request->shipping_address,
            'phone'=>$request->phone,
            'status'=>'pending'
        ]);
        foreach($cartItems as $item){
            OrdersItem::create([
                'order_id'=>$order->id,
                'product_id'=>$item->product_id,
                'quantity'=>$item->quantity,
                'price'=>$item->product->price
            ]);
            $item->product->decrement('stock', $item->quantity);
        }
        $paymentStatus = $request->payment_method =='cod' ? 'pending' : 'completed';
        Payment::create([
        'order_id'=>$order->id,
        'payment_method'=>$request->payment_method,
        'amount'=>$total,
        'transaction_id'=>$request->transaction_id,
        'status'=>$paymentStatus
        ]);

        Cart::where('user_id',session('user_id'))->delete();
        return redirect('/orders')->with('success','Order placed!#',$order->order_number);
    }
    
    public function orders(){
        $orders = Order::with('items.product','payment')->where('user_id',session('user_id'))->latest()->get();
        return view('shop.orders', compact('orders'));
    }
   
}
