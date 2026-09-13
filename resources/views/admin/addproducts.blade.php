@extends('layouts.app')
@section('content')

<section class="section-heading">
    <div>
        <p class="eyebrow">Catalog</p>
        <h1>Add Product</h1>
    </div>
    <a href="/admin/dashboard">Dashboard</a>
</section>

@if(($categories ?? collect())->isEmpty())
    <div class="empty-state">
        <strong>Add a category first.</strong>
        <p>Products need a category before they can be added to the catalog.</p>
        <a href="/admin/showcategory">Create Category</a>
    </div>
@else
    <form method="POST" action="/admin/products" enctype="multipart/form-data" class="form-card">
        @csrf
        <div class="form-grid">
            <div>
                <label for="product_name">Product Name</label>
                <input id="product_name" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div>
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    @foreach($categories ?? [] as $cat)
                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                    @endforeach
                </select>
                <a href="/admin/showcategory" class="text-link">New category</a>
            </div>
            <div>
                <label for="price">Price</label>
                <input id="price" name="price" type="number" step="0.5" min="0" class="form-control" placeholder="Price" required>
            </div>
            <div>
                <label for="stock">Stock</label>
                <input id="stock" name="stock" type="number" class="form-control" min="0" placeholder="Stock" required>
            </div>
            <div><button class="btn btn-success">Add Product</button></div>
        </div>
        <div class="form-grid form-grid--wide">
            <div>
                <label for="image">Product Image</label>
                <input id="image" type="file" name="image" class="form-control">
            </div>
            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" placeholder="Description"></textarea>
            </div>
        </div>
    </form>
@endif

<section class="panel-section panel-section--spaced">
    <h4>Current Products</h4>
    @if(($products ?? collect())->isEmpty())
        <div class="empty-state empty-state--compact">
            <strong>No products yet.</strong>
            <p>The product list will appear after you add your first item.</p>
        </div>
    @else
        <div class="table-shell">
            <table>
                <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Action</th></tr>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="table-product">
                                <div class="table-product__image">
                                    @if($product->image)
                                        <img src="{{asset('storage/'.$product->image)}}" alt="{{$product->name}}">
                                    @else
                                        <span>No image</span>
                                    @endif
                                </div>
                                <strong>{{$product->name}}</strong>
                            </div>
                        </td>
                        <td>{{optional($product->category)->name ?? 'No category'}}</td>
                        <td>${{$product->price}}</td>
                        <td>
                            @if($product->stock > 0)
                                <span class="stock-note">Stock: {{$product->stock}}</span>
                            @else
                                <span class="stock-note stock-note--danger">Out of Stock</span>
                            @endif
                        </td>
                        <td><a href="/admin/updateproduct/{{$product->id}}" class="btn btn-accent">Update</a></td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif
</section>
@endsection
