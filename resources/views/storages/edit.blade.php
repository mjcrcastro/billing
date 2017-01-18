@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

@section('main')

<h1> Edit Storage </h1>

{{ Form::model($storage, array('method'=>'PATCH', 'route'=> array('storages.update', $storage->id)))  }}
    @include('storages.form')
{{ Form::close() }}

@stop