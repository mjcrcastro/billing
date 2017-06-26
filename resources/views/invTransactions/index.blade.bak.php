@extends('master')

@section('invTransactions_active')
active
@stop

@section('form_search')
{{ Form::open(array('method'=>'get','role'=>'search','route'=>'invTransactions.index')) }}
@include('form_search_file')
@stop

@section('main')
<div class="container-fluid">
<h1> Lista de Transacciones de Inventario </h1>

<p> {{ link_to_route('invTransactions.create', 'Agregar nueva transaccion') }} </p>

@if ($invTransactionHeaders->count())
<table class="table table-striped table-ordered table-condensed">
    <thead>
        <tr>
            <th>{{ 'Tipo de Transaccion ' }}</th>
            <th>Numero</th>
            <th>Nota</th>
            <th>Fecha</th>
            <th>Bodega</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invTransactionHeaders as $invTransactionHeader)
        <tr>

            <td> 
                {{ $invTransactionHeader->transType->description }}
            </td>
            
            <td>
                {{ $invTransactionHeader->document_number }}
            </td>
            
            <td>
                {{ $invTransactionHeader->note }}
            </td>

            <td>
                {{ $invTransactionHeader->document_date }}
            </td>
            
            <td>
                {{ $invTransactionHeader->storage->description }}
            </td>

            <td> 
                {{ link_to_route('invTransactions.edit', 'Editar', array($invTransactionHeader->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }} 
            </td>

            <td>
                {{ Form::open(array('method'=>'DELETE', 'route'=>array('invTransactions.destroy', $invTransactionHeader->id))) }}
                {{ Form::submit('Borrar', array('class'=>'btn btn-danger '.Config::get('global/default.button_size'), 'onclick'=>"if(!confirm('Are you sure to delete this item?')){return false;};")) }} 
                {{ Form::close() }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
{{ $invTransactionHeaders->appends(array('filter'=>$filter))->links() }}
@else
    There are no Inventory Transactions
@endif
@stop