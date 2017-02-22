@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h2>  Kardex :: 
        {{ $product->productDescription()->first()->description }}
    </h2>
    <title>  Kardex :: 
        {{ $product->productDescription()->first()->description }}
    </title>
    <p> {{ link_to_route('products.index', Lang::get('products.index')) }} </p>

    <table id="example" class="display" cellspacing="0" width="100%">
    </table>

</div>

@include('products.scripts')

@stop

