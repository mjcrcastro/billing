@extends('master')

@section('config_active')
active
@stop

@section('main')
<div class ="container-fluid">
<h1> Reporte Consolidado por tipo de transaccion </h1>
{{ Form::open(array('route'=>'reports.tipTransRpt','class'=>'vertical','role'=>'form')) }}

<div class="form-group form-group-sm">
    {{ Form::label('analysis_start_date', 'Fecha de Inicio de Reporte', array("class"=>"control-label small")) }}
    {{ Form::text('analysis_start_date', null, array('class'=>'form-control')) }}
</div>

<div class="form-group form-group-sm">
    {{ Form::label('analysis_end_date', 'Fecha de Fin de Reporte', array("class"=>"control-label small")) }}
    {{ Form::text('analysis_end_date', null, array('class'=>'form-control')) }}
</div>

<div class="form-group form-group-sm">
    {{ Form::label('transaction_type_id', 'Tipo de Transaccion',array("class"=>"control-label small")) }}
    {{ Form::select('transaction_type_id', $transaction_types, null, array('class'=>'form-control')) }}
</div>

{{ Form::submit('Submit', array('class'=>'btn btn-primary form-control small')) }}
{{ link_to_route('products.index', 'Cancel', [],array('class'=>'btn btn-default form-control small')) }}

{{ Form::close() }}
</div>

<script type='text/javascript'>
    /*Shows a datepicker widget for
     * the purchase_date text input control
     */
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