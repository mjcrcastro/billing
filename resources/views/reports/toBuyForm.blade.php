@extends('master')

@section('config_active')
active
@stop

@section('main')
<div class ="container-fluid">
<h1> Proyeccion de compras </h1>
{{ Form::open(array('route'=>'reports.toBuyRpt','class'=>'vertical','role'=>'form')) }}

<div class="form-group form-group-sm">
    {{ Form::label('analysis_start_date', 'Fecha de Inicio de Analisis', array("class"=>"control-label small")) }}
    {{ Form::text('analysis_start_date', null, array('class'=>'form-control')) }}
</div>

<div class="form-group form-group-sm">
    {{ Form::label('analysis_end_date', 'Fecha de Fin de Analisis', array("class"=>"control-label small")) }}
    {{ Form::text('analysis_end_date', null, array('class'=>'form-control')) }}
</div>

<div class="form-group form-group-sm">
    {{ Form::label('this_delivery_date', 'Fecha prevista para la entrega de esta compra', array("class"=>"control-label small")) }}
    {{ Form::text('this_delivery_date', null, array('class'=>'form-control')) }}
</div>

<div class="form-group form-group-sm">
    {{ Form::label('next_delivery_date', 'Fecha prevista para entrega de proxima compra', array("class"=>"control-label small")) }}
    {{ Form::text('next_delivery_date', null, array('class'=>'form-control')) }}
</div>

{{ Form::submit('Submit', array('class'=>'btn btn-primary form-control small')) }}
{{ link_to_route('invTransactions.index', 'Cancel', [],array('class'=>'btn btn-default form-control small')) }}

{{ Form::close() }}
</div>

<script type='text/javascript'>
    /*Shows a datepicker widget for
     * the purchase_date text input control
     */
    $(function () {
        $("#this_delivery_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
    });
    
    $(function () {
        $("#next_delivery_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
    });
    
    $(function () {
        $("#analysis_start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
    });
    
    $(function () {
        $("#analysis_end_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
    });
    

</script>

@stop