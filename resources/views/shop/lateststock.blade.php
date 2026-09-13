@extends('layouts.app')
@section('content')
<section class="section-heading">
    <div>
        <p class="eyebrow">Recently added</p>
        <h1>New Stock</h1>
    </div>
    <a href="/shop">All Products</a>
</section>

@if($pro->isEmpty())
    <div class="empty-state">
        <strong>No new stock yet.</strong>
        <p>Recently added products will appear here.</p>
        @if(session('role')=='admin')
            <a href="/admin/product">Add Product</a>
        @else
            <a href="/shop">Back to Shop</a>
        @endif
    </div>
@else
<section class="product-grid">
    @foreach($pro as $product)
    <article class="product-card">
        <a class="product-card__media" href="/product/{{$product->slug}}">
            @if($product->image)
            <img src="{{asset('storage/'.$product->image)}}" alt="{{$product->name}}">
            @else
            <span>No image</span>
            @endif
        </a>
        <div class="product-card__body">
            <p class="eyebrow">{{optional($product->category)->name ?? 'Product'}}</p>
            <h6>{{$product->name}}</h6>
            <p class="product-price">${{$product->price}}</p>
            <div class="product-actions">
                <a href="/product/{{$product->slug}}">View</a>

                @if(session('role')=='admin')
                @if($product->stock <= 0)
                    <span class="stock-note stock-note--danger">Out of Stock</span>
                @endif
                @if($product->stock > 0)
                <p class="stock-note">Stock: {{$product->stock}}</p>
                @endif
                <a href="/admin/updateproduct/{{$product->id}}">Update Product</a>

                @endif
                @if(session('role')=='customer')
                <?php
                $quan=$product->stock;
                ?>
                 @if($quan > 0) 
                <form method="post" action="/cart/add/{{$product->id}}">
                    @csrf
                    <button>Add to Cart</button>
                </form>
                @endif

                @if($quan <= 0)
                    <span class="stock-note stock-note--danger">Out of Stock</span>
                @endif
                @endif
            </div>
        </div>
    </article>
    @endforeach
</section>
@endif
@endsection
