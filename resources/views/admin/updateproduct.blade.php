@extends('layouts.app')
@section('content')

<div class="page-actions page-actions--start">
    <a href="/admin/dashboard">Dashboard</a>
    <a href="/product/{{$products->slug}}">View Product</a>
</div>
<section class="section-heading">
    <div>
        <p class="eyebrow">Catalog</p>
        <h1>Update Product</h1>
    </div>
</section>
<form method="POST" action="/admin/updateproducts/{{$products->id}}" enctype="multipart/form-data" class="form-card">
    @csrf
    <div class="form-grid">
        <div>
            <label for="product_name">Product Name</label>
            <input id="product_name" name="name" class="form-control" value= "{{$products->name}}" placeholder="Name" required>
        </div>
        <div>
            <label>Current Category</label>
            <p class="category-label">{{optional($products->category)->name ?? 'No category'}}</p>
        </div>
        <div>
            <label for="price">Price</label>
            <input id="price" name="price" type="number" value="{{$products->price}}" min="0" step="0.5" class="form-control" placeholder="Price" required>
        </div>
        <div>
            <label for="stock">Stock</label>
            <input id="stock" name="stock" type="number" value="{{$products->stock}}" min="0" class="form-control" placeholder="Stock" required>
        </div>
        <div><button class="btn btn-success">Update Product</button></div>
    </div>
    <div class="form-grid form-grid--wide">
        <div>
            <label for="image">Product Image</label>
            <input id="image" type="file" name="image" value="{{$products->image}}" class="form-control">
        </div>

  
        @if($products->image != [])
            <a href="/admin/deleteimage/{{$products->id}}" class="btn-danger">Remove existing image</a>
        @endif
        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" placeholder="Description">{{$products->description}}</textarea>
        </div>
    </div>
</form>


<div class="admin-two-column">
    <form method="post" action="/admin/changecate/{{$products->id}}" class="form-card">
        @csrf
        <h4>Change Category</h4>
        @if(($categories ?? collect())->isEmpty())
            <div class="empty-state empty-state--compact">
                <strong>No categories available.</strong>
                <p>Add a category before moving this product.</p>
            </div>
        @else
            <label for="change_category_id">Category</label>
            <select id="change_category_id" name="category_id" class="form-control" required>
                    @foreach($categories ?? [] as $cat)
                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                    @endforeach
                </select>
            <button>Change</button>
        @endif
    </form>
    <form  method="post" action="/admin/addcate" enctype="multipart/form-data" class="form-card">
        @csrf
            <h4>Add New Category</h4>
            <label for="new_category_name">Category Name</label>
            <input id="new_category_name" type="text" name='name' placeholder= 'Add New Category' required>
            <button>Add Category</button>
            </form>
</div>
    
@endsection
