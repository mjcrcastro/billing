@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
active
@stop


@section('main')

<h1> Create Product type </h1>

{{ Form::open(array('route'=>'productTypes.store')) }}
    @include('productTypes.form')
{{ Form::close() }}

@stop