@extends('master')

@section('products_active')
active
@stop

@section('form_search')

{{ Form::open(array('class'=>'navbar-form navbar-left','method'=>'get','role'=>'search','route'=>'products.index')) }}
<div class="form-group">
    {{ Form::text('filter',$filter,array('class'=>'form-control','placeholder'=>'Search')) }}
</div>
{{ Form::submit('Search', array('class'=>'btn btn-default')) }} 
{{ Form::close() }}

@stop

@section('main')
<div class="container-fluid">
    <h1> All products </h1>
    <p> {{ link_to_route('products.create', Lang::get('products.add.new')) }} </p>

    @if ($products->count())
    <table class="table table-striped table-ordered table-condensed">
        <thead>
            <tr>
                <th>{{Lang::get('products.description')}}</th>
                <th>Product type</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>

                <td>
                    @foreach ($product->productDescriptors as $productdescriptor)
                    {{ $productdescriptor->descriptor->description.' '}}
                    @endforeach
                </td>
                <td> 
                   {{ $product->productType->description }} 
                </td>
                <td> 
                    {{ link_to_route('products.edit', 'Edit', array($product->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }} 
                </td>
                <td>
                    {{ Form::open(array('method'=>'DELETE', 'route'=>array('products.destroy', $product->id))) }}
                    {{ Form::submit('Delete', array('class'=>'btn btn-danger '.Config::get('global/default.button_size'), 'onclick'=>"if(!confirm('Are you sure to delete this item?')){return false;};")) }} 
                    {{ Form::close() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $products->appends(array('filter'=>$filter))->links() }}
@else
There are no products
@endif
@stop