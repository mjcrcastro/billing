@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h1> Current Balance for all products in storage {{ $storage->description }} </h1>
    @if ($products->count())
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th >{{Lang::get('products.description')}}</th>
                <th style="text-align:right">Quantity</th>
                <th style="text-align:right">Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>

                <td>
                    {{ $product->productDescription()->first()->description }}
                </td>
                <td align='right'> 
                    {{ number_format($product->Qty, 2, '.', ',') }} 
                </td>
                <td align='right'> 
                    {{ number_format($product->Cost, 2, '.', ',') }} 
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
There are no products
@endif
@stop