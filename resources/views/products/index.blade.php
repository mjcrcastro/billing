@extends('master')

@section('title')
<title>Listado de Productos CAERC</title>
@stop

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h2>  Listado de productos :: 
    </h2>
    <p> {{ link_to_route('products.create', 'Agregar nuevo producto') }} </p>

    <table id="example" class="display" cellspacing="0" width="100%">
    </table>

</div>

@include('products.scripts_index')

@stop

