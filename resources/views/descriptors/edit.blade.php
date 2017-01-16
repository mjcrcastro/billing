@extends('master')

@section('config_active')
    active
@stop

@section('header')

@stop

@section('main')

<h1> Edit descriptor </h1>


{{ Form::model($descriptor, array('method'=>'PATCH', 'route'=> array('descriptors.update', $descriptor->id)))  }}
   @include('descriptors.form')
{{ Form::close() }}

@stop