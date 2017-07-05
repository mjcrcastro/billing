@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h1> Movimientos en transaccion {{ $transaction_type->description }} </h1>
    <small>
        {{ $title }}
    </small>
    @if ($products->count())
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th >{{Lang::get('products.description')}}</th>
                <th style="text-align:right">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>

                <td>
                    {{ $product->productDescription()->first()->description }}
                </td>
                <td align='right'> 
                    {{ number_format($product->mov_to_date, 2, '.', ',') }} 
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