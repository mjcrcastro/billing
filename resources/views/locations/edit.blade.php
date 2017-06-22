@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

@section('main')

<h1> Editar Ubicacion </h1>

{{ Form::model($location, array('method'=>'PATCH', 'route'=> array('locations.update', $location->id)))  }}
    @include('locations.form')
{{ Form::close() }}

@stop