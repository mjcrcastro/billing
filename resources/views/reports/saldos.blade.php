@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h1> Current Balance for all products </h1>
    @if ($products->count())
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th >{{Lang::get('products.description')}}</th>
                <th style="text-align:right">Quantity</th>
                <th style="text-align:right">Cost</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>

                <td>
                    {{ $product->productDescription()->first()->description }}
                    
                </td>
                <td align='right'> 
                    {{ number_format($product->total_qty, 2, '.', ',') }} 
                </td>
                <td align='right'> 
                    {{ number_format($product->total_cost, 2, '.', ',') }} 
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