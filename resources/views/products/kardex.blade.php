@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h2>  Kardex :: 
        @foreach ($product->productDescriptors as $productdescriptor)
        {{ $productdescriptor->descriptor->description.' '}}
        @endforeach
    </h2>
    <p> {{ link_to_route('products.index', Lang::get('products.index')) }} </p>

    <table id="example" class="display" cellspacing="0" width="100%">
    </table>

</div>

@include('products.scripts')

@stop

