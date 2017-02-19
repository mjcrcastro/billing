@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h1> Current Balance for all products </h1>
    @if ($products->count())
    <table class="table table-striped table-ordered table-condensed">
        <thead>
            <tr>
                <th>{{Lang::get('products.description')}}</th>
                <th>Quantity</th>
                <th>Cost</th>
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
                    {{ $product->qtyTotal()->first()->totalQty }} 
                </td>
                <td> 
                    {{ $product->costTotal()->first()->totalCost }} 
                </td>
                <td> 
                    {{ link_to_route('products.show', 'Kardex', array($product->id), array('class'=>'btn btn-link '.Config::get('global/default.button_size'))) }} 
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $products->links() }}
@else
There are no products
@endif
@stop