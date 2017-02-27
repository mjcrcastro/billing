@extends('master')

@section('config_active')
active
@stop

@section('main')
<div class ="container-fluid">
<h1> Purchases Projection </h1>
<small>
    This projection is based on estimated product products delivery to the shop 
    <br>
    (e.g., when the products will be available at the shop's counter)
</small>

{{ Form::open(array('route'=>'reports.toBuyRpt','class'=>'vertical','role'=>'form')) }}

<div class="form-group form-group-sm">
    {{ Form::label('this_purchase_date', 'This Delivery Date', array("class"=>"control-label small")) }}
    {{ Form::text('this_purchase_date', null, array('class'=>'form-control')) }}
</div>

<div class="form-group form-group-sm">
    {{ Form::label('next_purchase_date', 'Next Delivery Date', array("class"=>"control-label small")) }}
    {{ Form::text('next_purchase_date', null, array('class'=>'form-control')) }}
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
        $("#this_purchase_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
    });
    
    $(function () {
        $("#next_purchase_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
    });


</script>

@stop