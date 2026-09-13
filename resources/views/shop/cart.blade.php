@extends('layouts.app')
@section('content')
<section class="section-heading">
    <div>
        <p class="eyebrow">Ready when you are</p>
        <h1>Your Cart</h1>
    </div>
    <a href="/shop">Continue Shopping</a>
</section>
@if($cartItems->isEmpty())
<div class="empty-state">
    <strong>Your cart is empty.</strong>
    <p>Add products from the shop to review them here.</p>
    <a href="/shop">Shop now</a>
</div>
@else
<div class="table-shell">
    <table>
    <tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr>
    @foreach($cartItems as $item)
    <tr>
        <td>
            <div class="table-product">
                <div class="table-product__image">
                    @if($item->product->image)
                        <img src="{{asset('storage/'.$item->product->image)}}" alt="{{$item->product->name}}">
                    @else
                        <span>No image</span>
                    @endif
                </div>
                <strong>{{$item->product->name}}</strong>
            </div>
        </td>
        <td>${{$item->product->price}}</td>
        <td><form action="/cart/update/{{$item->id}}" method="POST" class="inline-form">
            @csrf
            <input type="number" name="quantity" value="{{$item->quantity}}" min="1" max="{{$item->product->stock}}">
            <button>Update</button>
        </form>
                <a href="/remove/{{$item->product_id}} " class="btn btn-danger" >Remove</a>

    </td>
        <td>${{$item->total}}</td>
    </tr>
    @endforeach

    <tr class="total-row">
        <td colspan="3">
            Grand Total:

        </td>
        <td>
            ${{$cartItems->sum('total')}}
        </td>
    </tr>
    </table>
</div>
<div class="page-actions">
    <a href="/checkout" class="btn btn-accent">Checkout</a>
</div>
@endif
@endsection
