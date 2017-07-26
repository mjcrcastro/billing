@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h1> Proyeccion de Compras <br> {{ $note }}</h1>
    <small>
        {{ $title }}
        <br>
        * {{ $days_proyected }} dias de consumo proyectados 
    </small>
    @if ($products_to_buy->count())
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th >{{Lang::get('products.description')}}</th>
                <th style="text-align:right">Consumo en analisis</th>
                <th style="text-align:right">Consumo diario</th>
                <th style="text-align:right">Existencia a {{ $analysis_cut_date }} </th>
                <th style="text-align:right">Consumo proyectado* </th>
                <th style="text-align:right">Existencia a {{ $purchase_date }}</th>
                <th style="text-align:right">Compras del {{ $purchase_date }} </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products_to_buy as $product)
            <tr>

                <td>
                    {{ $product->productDescription()->first()->description }}
                </td>
                <td align='right'> 
                    {{ number_format($product->consumption_period, 2, '.', ',') }} 
                </td>
                <td align='right'> 
                    {{ number_format($product->daily_coms_ave, 2, '.', ',') }} 
                </td>
                <td align='right'> 
                    {{ number_format($product->existence_to_date, 2, '.', ',') }} 
                </td>
                <td align='right'> 
                    {{ number_format($product->proyected_consumption, 2, '.', ',') }} 
                </td>
                <td align='right'> 
                    {{ number_format($product->proyected_existence, 2, '.', ',') }} 
                </td>
                <td align='right'> 
                    {{ number_format(ceil($product->proyected_purchase), 2, '.', ',') }} 
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