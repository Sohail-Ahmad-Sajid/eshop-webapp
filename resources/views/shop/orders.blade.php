@extends('layouts.app')
@section('content')
<section class="section-heading">
    <div>
        <p class="eyebrow">Purchase history</p>
        <h1>Your Orders</h1>
    </div>
    <a href="/shop">Shop More</a>
</section>

@if(($orders ?? collect())->isEmpty())
    <div class="empty-state">
        <strong>No orders yet.</strong>
        <p>Your completed orders will show here after checkout.</p>
        <a href="/shop">Start Shopping</a>
    </div>
@else
<div class="table-shell">
    <table class="table">
    <tr><th>Order #</th><th>Items</th><th>Total</th><th>Status</th></tr>
    @foreach($orders ?? [] as $order)
    <tr>
        <td>{{$order->order_number}}</td>
        
        <td>
            <ul class="order-items-list">
                @forelse($order->items as $item)
                    <li>{{$item->product->name}} x {{$item->quantity}}</li>
                @empty
                    <li>No items found.</li>
                @endforelse
            </ul>
    </td>
        <td>${{$order->total_amount}}</td>
        <td><span class="status-pill">{{$order->status}}</span></td>
      
    </tr>
    @endforeach
    </table>
</div>
@endif

@endsection
