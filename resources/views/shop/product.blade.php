@extends('layouts.app')

@section('content')

<style>

.product-detail-page{

    display:grid;
    grid-template-columns:1fr 1fr;

    width:100%;
    max-width:1200px;

    margin:25px auto;

    overflow:hidden;

    border-radius:12px;


    border:1px solid #e5e7eb;

}



/* IMAGE SECTION */

.product-detail-page .product-detail__media{

    width:100%;

    aspect-ratio:1 / 1;

    display:flex;

    justify-content:center;

    align-items:center;

    padding:25px;


    border-right:1px solid #e5e7eb;

}



.product-detail-page .product-detail__media img{

    width:100%;

    height:100%;

    object-fit:contain;

    display:block;

}



.no-image{

    width:100%;

    height:100%;

    display:flex;

    justify-content:center;

    align-items:center;

    color:#64748b;

    background:#f8fafc;

}



/* PRODUCT INFO */

.product-detail-page .product-detail__info{

    width:100%;

    padding:35px;

    display:flex;

    flex-direction:column;

    justify-content:center;

    background:#f8fafc;

}



.product-detail-page .eyebrow{

    color:#64748b;

    font-size:14px;

    font-weight:700;

    text-transform:uppercase;

    margin-bottom:10px;

}



.product-detail-page h1{

    margin:0 0 15px;

    color:#111827;

    font-size:clamp(26px,3vw,40px);

    font-weight:900;

    line-height:1.2;

}



.product-detail-page .product-price{

    margin:0 0 15px;

    color:#f85606;

    font-size:32px;

    font-weight:900;

}



/* DESCRIPTION */

.product-detail-page .product-description{

    color:#64748b;

    font-size:16px;

    line-height:1.7;

    margin-bottom:25px;


    display:-webkit-box;

    -webkit-line-clamp:3;

    -webkit-box-orient:vertical;

    overflow:hidden;

    text-overflow:ellipsis;

}



/* ACTIONS */

.product-detail-page .product-actions{

    display:flex;

    flex-wrap:wrap;

    gap:12px;

    align-items:center;

}



.product-detail-page .product-actions form{

    margin:0;

}



.product-detail-page .product-actions a,

.product-detail-page .product-actions button{

    padding:12px 22px;

    border:none;

    border-radius:8px;

    background:#2563eb;

    color:white;

    font-weight:800;

    text-decoration:none;

    cursor:pointer;

}



.product-detail-page .product-actions a:hover,

.product-detail-page .product-actions button:hover{

    background:#1d4ed8;

}



/* STOCK */

.product-detail-page .stock-note{

    padding:8px 14px;

    border-radius:8px;

    background:#ecfdf3;

    color:#15803d;

    font-weight:800;

}



.product-detail-page .stock-note--danger{

    background:#fef2f2;

    color:#dc2626;

}



/* FEATURES */

.product-detail-page .product-detail__meta{

    display:flex;

    flex-wrap:wrap;

    gap:10px;

    margin-top:30px;

}



.product-detail-page .product-detail__meta span{

    padding:10px 14px;

    border-radius:8px;

    background:#fff7ed;

    color:#9a3412;

    font-size:14px;

    font-weight:700;

}



/* TABLET */

@media(max-width:900px){


    .product-detail-page{

        grid-template-columns:1fr;

        margin:15px;

    }


    .product-detail-page .product-detail__media{

        border-right:none;

        border-bottom:1px solid #e5e7eb;

    }

}



/* MOBILE */

@media(max-width:520px){


    .product-detail-page{

        margin:10px;

    }


    .product-detail-page .product-detail__info{

        padding:20px;

    }


    .product-detail-page .product-detail__media{

        padding:15px;

    }


    .product-detail-page .product-actions{

        flex-direction:column;

        align-items:stretch;

    }


    .product-detail-page .product-actions a,

    .product-detail-page .product-actions button{

        width:100%;

        text-align:center;

    }


}

</style>



<article class="product-detail product-detail-page">


    <!-- IMAGE -->

    <div class="product-detail__media">

        @if($product->image)

            <img 
            src="{{asset('storage/'.$product->image)}}"
            alt="{{$product->name}}">

        @else

            <div class="no-image">
                No Image Available
            </div>

        @endif

    </div>



    <!-- DETAILS -->

    <div class="product-detail__info">


        <p class="eyebrow">

            {{optional($product->category)->name ?? 'Product'}}

        </p>



        <h1>

            {{$product->name}}

        </h1>



        <p class="product-price">

            ${{$product->price}}

        </p>



        <!-- DATABASE DESCRIPTION -->

        @if($product->description)

            <p class="product-description">

                {{$product->description}}

            </p>

        @endif




        <div class="product-actions">


            @if(session('role')=='admin')


                @if($product->stock <= 0)

                    <span class="stock-note stock-note--danger">
                        Out of Stock
                    </span>

                @else

                    <span class="stock-note">
                        Stock: {{$product->stock}}
                    </span>

                @endif



                <a href="/admin/updateproduct/{{$product->id}}">
                    Update Product
                </a>


            @endif




            @if(session('role')=='customer')


                @if($product->stock > 0)


                    <span class="stock-note">
                        Available: {{$product->stock}}
                    </span>


                    <form method="POST" action="/cart/add/{{$product->id}}">

                        @csrf

                        <button type="submit">
                            Add to Cart
                        </button>


                    </form>


                @else


                    <span class="stock-note stock-note--danger">
                        Out of Stock
                    </span>


                @endif


            @endif


        </div>



        <div class="product-detail__meta">

            <span>Secure checkout</span>

            <span>Quick order tracking</span>

            <span>Fresh inventory updates</span>

        </div>



    </div>


</article>


@endsection