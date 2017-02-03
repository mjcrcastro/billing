@extends('master')

@section('products_active')
active
@stop


@section('main')
<div class ="container-fluid">
    <h1> Create Inv Transaction </h1>
    {{ Form::open(array('route'=>'invTransactions.store','class'=>'form','role'=>'form')) }}
        @include('invTransactions.form')
    {{ Form::close() }}
</div>

    @include('invTransactions.modal')

@stop
