@extends('master')

@section('products_active')
active
@stop


@section('main')
<div class ="container-fluid">
    <h1> Nueva Transaccion de Inventario </h1>
    {{ Form::open(array('route'=>'invTransactions.store','class'=>'form')) }}
        @include('invTransactions.form')
    {{ Form::close() }}
</div>

    @include('invTransactions.modal')

@stop
