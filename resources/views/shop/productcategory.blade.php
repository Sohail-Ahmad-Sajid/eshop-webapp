@extends('layouts.app')
@section('content')
<style>
    .category-product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 220px));
        gap: 18px;
        align-items: stretch;
    }

    .category-product-grid .product-card {
        width: 220px;
        height: 400px;
        overflow: hidden;
    }

    .category-product-grid .product-card__media {
        height: 190px;
        min-height: 190px;
        aspect-ratio: auto;
        overflow: hidden;
        background: #ffffff;
    }

    .category-product-grid .product-card__media img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
    }

    .category-product-grid .product-card__body {
        gap: 6px;
        padding: 12px;
    }

    .category-product-grid .product-card h6 {
        display: -webkit-box;
        min-height: 42px;
        max-height: 42px;
        margin: 2px;
        overflow: hidden;
        line-height: 1.25;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .category-product-grid .product-price {
        margin: 0;
    }

    .category-product-grid .product-actions {
        gap: 7px;
        margin-top: auto;
    }

    @media (max-width: 560px) {
        .category-product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .category-product-grid .product-card {
            width: 100%;
            height: 350px;
        }

        .category-product-grid .product-card__media {
            height: 145px;
            min-height: 145px;
        }
    }
</style>

<section class="section-heading">
    <div>
        <p class="eyebrow">Category</p>
        <h1>{{$products->isNotEmpty() ? (optional($products->first()->category)->name ?? 'Category Products') : 'Category Products'}}</h1>
    </div>
</section>

@if($products->isEmpty())
    <div class="empty-state">
        <strong>No products found.</strong>
        <p>This category does not have products yet.</p>
        <a href="/shop">Back to Shop</a>
    </div>
@else
<section class="product-grid category-product-grid">
    @foreach($products as $product)
    <article class="product-card">
        <a class="product-card__media" href="/product/{{$product->slug}}">
            @if($product->image)
            <img src="{{asset('storage/'.$product->image)}}" alt="{{$product->name}}">
            @else
            <span>No image</span>
            @endif
        </a>
        <div class="product-card__body">
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
                <br>
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
                <br>
            </div>
        </div>
    </article>
    @endforeach
</section>
@endif
@endsection
