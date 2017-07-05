@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h1> Proyeccion de Compras </h1>
    <small>
        {{ $title }}
    </small>
    @if ($products_to_buy->count())
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th >{{Lang::get('products.description')}}</th>
                <th style="text-align:right">Existencia a {{ $analysis_cut_date }} </th>
                <th style="text-align:right">Consumo del periodo</th>
                <th style="text-align:right">Consumo diario</th>
                <th style="text-align:right">Existencia a {{ $purchase_date }}</th>
                <th style="text-align:right">Compras proyectads</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products_to_buy as $product)
            <tr>

                <td>
                    {{ $product->productDescription()->first()->description }}
                </td>
                <td align='right'> 
                    {{ number_format($product->existence_to_date, 2, '.', ',') }} 
                </td>
                <td align='right'> 
                    {{ number_format($product->ave_coms_cycle, 2, '.', ',') }} 
                </td>
                <td align='right'> 
                    {{ number_format($product->daily_coms_ave, 2, '.', ',') }} 
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