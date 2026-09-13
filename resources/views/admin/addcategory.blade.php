@extends('layouts.app')
@section('content')
<section class="section-heading">
    <div>
        <p class="eyebrow">Catalog</p>
        <h1>Manage Categories</h1>
    </div>
    <a href="/admin/product">Products</a>
</section>
<div class="admin-two-column">
    <form  method="post" action="/admin/addcategories" enctype="multipart/form-data" class="form-card">
        @csrf
            <h4>Add Category</h4>
            <label for="category_name">Category Name</label>
            <input id="category_name" type="text" name='name' placeholder= 'Add New Category' required>
            <button>Add Category</button>
            </form>

    <div class="form-card">
        <h4>Delete Category</h4>
        @if(($categories ?? collect())->isEmpty())
            <div class="empty-state empty-state--compact">
                <strong>No categories yet.</strong>
                <p>Add a category before using delete.</p>
            </div>
        @else
            <form action="/admin/deletecate" method="post">
                @csrf
                <label for="delete_category_id">Category</label>
                <select id="delete_category_id" name="id" class="form-control" required>
                    @foreach($categories ?? [] as $cat)
                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                    @endforeach
                </select>
                <button class="btn-danger">Delete</button>
            </form>
        @endif
    </div>
</div>

@if(($categories ?? collect())->isNotEmpty())
    <section class="panel-section panel-section--spaced">
        <h4>Existing Categories</h4>
        <div class="category-pills category-pills--static">
            @foreach($categories as $cat)
                <span>{{$cat->name}}</span>
            @endforeach
        </div>
    </section>
@endif
@endsection
