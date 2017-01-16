@extends('master')

@section('config_active')
active
@stop

@section('main')

<h1> Create descriptor {{ $label }} </h1>

{{ Form::open(array('route'=>'descriptors.store','class'=>'vertical','role'=>'form')) }}

    @include('descriptors.form')

{{ Form::close() }}

@stop