@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h2>  Listado de transacciones :: 
    </h2>
    <p> {{ link_to_route('invTransactions.create', 'Agregar nueva transaccion') }} </p>

    <table id="example" class="display" cellspacing="0" width="100%">
    </table>

</div>

@include('invTransactions.scripts')

@stop

