@extends('master')

@section('invTransactionHeaders_active')
active
@stop

@section('form_search')
{{-- Creates a form search on the menu bar --}}
{{ Form::open(array('class'=>'navbar-form navbar-left','method'=>'get','role'=>'search','route'=>'invTransactionHeaders.index')) }}
<div class="form-group">
    {{ Form::text('filter',$filter,array('class'=>'form-control','placeholder'=>'Search')) }}
</div>
{{ Form::submit('Search', array('class'=>'btn btn-default')) }} 
{{ Form::close() }}

@stop

@section('main')

<h1> All Inventory Transactions </h1>

<p> {{ link_to_route('invTransactionHeaders.create', Lang::get('invTransactionHeaders.add.new')) }} </p>

@if ($invTransactionHeaders->count())
<table class="table table-striped table-ordered table-condensed">
    <thead>
        <tr>
            <th>{{Lang::get('invTransactionHeaders.store')}}</th>
            <th>Date</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invTransactionHeaders as $invTransactionHeader)
        <tr>

            <td> 
                {{ $invTransactionHeader->transaction_type_id }}
            </td>

            <td>
                {{ $invTransactionHeader->document_date }}
            </td>

            <td> 
                {{ link_to_route('invTransactionHeaders.edit', 'Edit', array($invTransactionHeader->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }} 
            </td>

            <td>
                {{ Form::open(array('method'=>'DELETE', 'route'=>array('invTransactionHeaders.destroy', $invTransactionHeader->id))) }}
                {{ Form::submit('Delete', array('class'=>'btn btn-danger '.Config::get('global/default.button_size'), 'onclick'=>"if(!confirm('Are you sure to delete this item?')){return false;};")) }} 
                {{ Form::close() }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $invTransactionHeaders->appends(array('filter'=>$filter))->links() }}
@else
    There are no Inventory Transactions
@endif
@stop