@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

@section('main')

<h1> Edit Product type </h1>

{{ Form::model($productType, array('method'=>'PATCH', 'route'=> array('productTypes.update', $productType->id)))  }}
    @include('productTypes.form')
{{ Form::close() }}

@stop