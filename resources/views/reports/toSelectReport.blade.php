@extends('master')

@section('products_active')
active
@stop

@section('main')
<h1> Saldo de Inventario por Bodega </h1>
<div class ="container-fluid">
    {{  Form::open(array('action'=>'ReportsController@selectedReport', 'method' => 'get')) }}
    <div class="form-group form-group-sm">
        {{ Form::label('storage_id', 'Storage',array("class"=>"control-label small")) }}
        {{ Form::select('storage_id', $storages, null, array('class'=>'form-control')) }}
    </div>
     <div class="form-group form-group-sm">
    {{ Form::label('full_report', 'Reporte Completo (sin paginacion):') }}
    {{ Form::checkbox('full_report', 1, False, array('class="checkbox"')) }}
    </div>
    {{ Form::submit('Submit', array('class'=>'btn  btn-primary col-xs-12')) }}
    {{ Form::close() }}
</div>
@stop
