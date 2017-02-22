@extends('master')

@section('config_active')
active
@stop

@section('header')

@stop

@section('main')

<h1> Edit 

    {{ $product->productDescription()->first()->description }}

</h1>


{{ Form::model($product, array('method'=>'PATCH', 'route'=> array('products.update', $product->id)))  }}

<div class="form-group @if ($errors->has('product_type_id')) has-error @endif">
    {{ Form::label('productType', 'Descriptor Type:') }}
    {{ Form::select('product_type_id', $productTypes, $product_type_id, array('class="form-control"')) }}
    @if ($errors->has('product_type_id')) 
    <div class="small">
        {{ $errors->first('product_type_id', ':message') }} 
    </div>
    @endif
    <p></p>
    <p></p>
    {{ Form::submit('Submit', array('class'=>'btn  btn-primary col-xs-6')) }}
    {{ link_to_route('products.index', 'Cancel', [],array('class'=>'btn btn-default col-xs-6')) }}
</div>

@stop