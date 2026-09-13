@extends('layouts.app')
@section('content')
@php
    $dashboardOrders = $orders ?? collect();
    $dashboardProducts = $products ?? collect();
    $outOfStockProducts = $dashboardProducts->filter(fn($product) => $product->stock < 1);
    $lowStockProducts = $dashboardProducts->filter(fn($product) => $product->stock < 10 && $product->stock > 0);
@endphp

<section class="section-heading">
    <div>
        <p class="eyebrow">Operations</p>
        <h1>Admin Dashboard</h1>
    </div>
    <div class="page-actions page-actions--inline">
        <a href="/admin/product">Add Product</a>
        <a href="/admin/showcategory">Categories</a>
    </div>
</section>
<div class="dashboard-grid">
    <div class="metric-card"><span>Products</span><strong>{{$stats['products'] ?? 0}}</strong></div>
    <div class="metric-card"><span>Orders</span><strong>{{$stats['orders'] ?? 0}}</strong></div>
    <div class="metric-card"><span>Revenue</span><strong>${{$stats['revenue'] ?? 0}}</strong></div>
    <div class="metric-card"><span>Pending</span><strong>{{$stats['pending'] ?? 0}}</strong></div>
</div>



<section class="panel-section">
    <h4>Recent Orders</h4>
    @if($dashboardOrders->isEmpty())
        <div class="empty-state empty-state--compact">
            <strong>No orders yet.</strong>
            <p>New customer orders will appear here when checkout is completed.</p>
        </div>
    @else
        <div class="table-shell">
            <table class="table">
            <tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Action</th></tr>
            @foreach($dashboardOrders as $order)
            <tr>
                <td>{{$order->order_number}}</td>
                <td>{{$order->user->name ?? 'N/A'}}</td>
                <td>${{$order->total_amount}}</td>
                <td><span class="status-pill">{{$order->status}}</span></td>
                <td>
                    <form method="POST" action="/admin/orders/{{$order->id}}/status" class="inline-form">
                    @csrf
                    <select name="status" onchange="this.form.submit()">
                                    <option {{$order->status=='pending'?'selected':''}}>pending</option>
                        <option {{$order->status=='paid'?'selected':''}}>paid</option>
                        <option {{$order->status=='shipped'?'selected':''}}>shipped</option>
                        <option {{$order->status=='delivered'?'selected':''}}>delivered</option>

                    </select>
                    </form>
                </td>
            </tr>
            @endforeach
            </table>
        </div>
    @endif
</section>

<section class="inventory-alerts">
    <div class="panel-section">
        <h4>Out of Stock Products</h4>
        @if($outOfStockProducts->isEmpty())
            <div class="empty-state empty-state--compact">
                <strong>No out-of-stock products.</strong>
                <p>Inventory issues will show here.</p>
            </div>
        @else
            @foreach($outOfStockProducts as $product)
                <div class="inventory-row"><h5>{{$product->name}}</h5><span class="stock-note stock-note--danger">Out</span><a href="/admin/updateproduct/{{$product->id}}">Update</a></div>
            @endforeach
        @endif
    </div>
    <div class="panel-section">
        <h4>Near to Out of Stock Products</h4>
        @if($lowStockProducts->isEmpty())
            <div class="empty-state empty-state--compact">
                <strong>No low-stock products.</strong>
                <p>Products below 10 units will appear here.</p>
            </div>
        @else
            @foreach($lowStockProducts as $product)
                <div class="inventory-row"><h5>{{$product->name}}</h5><span>Stock: {{$product->stock}}</span><a href="/admin/updateproduct/{{$product->id}}">Update</a></div>
            @endforeach
        @endif
    </div>
</section>
@endsection
