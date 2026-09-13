@extends('layouts.app')
@section('content')

<style>
    .home-product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 220px));
        gap: 18px;
        align-items: stretch;
    }

    .home-product-grid .product-card {
        width: 220px;
        min-height: 390px;
    }

    .home-product-grid .product-card__media {
        height: 190px;
        min-height: 190px;
        aspect-ratio: auto;
        overflow: hidden;
    }

    .home-product-grid .product-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .home-product-grid .product-card__body {
        gap: 6px;
        padding: 12px;
    }

    .home-product-grid .product-card h6 {
        min-height: 44px;
        margin: 0;
        overflow: hidden;
        line-height: 1.25;
    }

    .home-product-grid .eyebrow,
    .home-product-grid .product-price {
        margin: 0;
    }

    .home-product-grid .product-actions {
        gap: 7px;
        margin-top: 8px;
    }

    @media (max-width: 560px) {
        .home-product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .home-product-grid .product-card {
            width: 100%;
            min-height: 360px;
        }

        .home-product-grid .product-card__media {
            height: 150px;
            min-height: 150px;
        }
    }
</style>

<section class="shop-hero">
    <div class="shop-hero__content">
        <p class="eyebrow">Fresh picks for you</p>
        <h1>Everyday essentials, new arrivals, and smart deals.</h1>
        <p class="shop-hero__text">Find products fast, compare stock at a glance, and move from browsing to checkout without extra steps.</p>

        <form class="home-search" role="search" onsubmit="return false;">
            <label class="sr-only" for="productSearch">Search products</label>
            <input id="productSearch" type="search" placeholder="Search products, categories, or prices" autocomplete="off">
            <button type="submit">Search</button>
            <button type="button" class="btn-ghost" id="clearProductSearch">Clear</button>
        </form>

        <div class="shop-hero__stats">
            <span>{{$products->count()}} products</span>
            <span>{{$categories->count()}} categories</span>
            <span>Cash on delivery</span>
        </div>
    </div>

    <div class="deal-panel" aria-label="Featured shopping benefits">
        <span class="deal-panel__tag">Today</span>
        <strong>New stock is ready</strong>
        <p>Browse the latest products added to E Shop.</p>
        <a href="/latestpro" class="btn btn-accent">New Stock</a>
    </div>
</section>

@if($categories->isNotEmpty())
    <nav class="category-pills" aria-label="Product categories">
        @foreach($categories as $cate)
        <a href="/discate/{{$cate->id}}">{{$cate->name}}</a>
        @endforeach
    </nav>
@endif

<section class="section-heading section-heading--compact">
    <div>
        <p class="eyebrow">Shop now</p>
        <h1>Featured Products</h1>
    </div>
</section>

@if($products->isEmpty())
    <div class="empty-state">
        <strong>No products yet.</strong>
        <p>Products added by the admin will appear here.</p>
        @if(session('role')=='admin')
            <a href="/admin/product">Add Product</a>
        @endif
    </div>
@else
<section class="product-grid home-product-grid" id="productGrid">
    @foreach($products as $product)
    <article class="product-card" data-product-card data-search-text="{{ strtolower($product->name.' '.optional($product->category)->name.' '.$product->price) }}">
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
                    <?php $quan = $product->stock; ?>

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

<div class="empty-state is-hidden" id="searchEmptyState">
    <strong>No matching products.</strong>
    <p>Try another product name, category, or price.</p>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('productSearch');
        const clearButton = document.getElementById('clearProductSearch');
        const cards = Array.from(document.querySelectorAll('[data-product-card]'));
        const emptyState = document.getElementById('searchEmptyState');

        if (!searchInput || !cards.length) {
            return;
        }

        function filterProducts() {
            const term = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            cards.forEach(function (card) {
                const matches = card.dataset.searchText.includes(term);
                card.classList.toggle('is-hidden', !matches);

                if (matches) {
                    visibleCount += 1;
                }
            });

            if (emptyState) {
                emptyState.classList.toggle('is-hidden', visibleCount > 0);
            }
        }

        searchInput.addEventListener('input', filterProducts);

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                searchInput.value = '';
                filterProducts();
                searchInput.focus();
            });
        }
    });
</script>
@endsection