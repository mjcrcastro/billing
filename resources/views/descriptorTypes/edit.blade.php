@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

@section('main')

<h1> Edit Descriptor type </h1>

{{ Form::model($descriptorType, array('method'=>'PATCH', 'route'=> array('descriptorTypes.update', $descriptorType->id)))  }}
    @include('descriptorTypes.form')
{{ Form::close() }}

@stop