@extends('master')

@section('products_active')
active
@stop

@section('main')
<div class="container-fluid">
    <h1>  
        @foreach ($product->productDescriptors as $productdescriptor)
        {{ $productdescriptor->descriptor->description.' '}}
        @endforeach
    </h1>
    <h2>Kardex</h2>
    <p> {{ link_to_route('products.index', Lang::get('products.index')) }} </p>
    @if ($transactions->count())
    {{ $runningCost = 0 }}
    {{ $runningQty = 0 }}
    <table class="table table-striped table-ordered table-condensed">
        <thead>
            <tr>
                <th>Type</th>
                <th>Number</th>
                <th>Date</th>
                <th>Note</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th>Average Cost</th>
                <th>Total Cost</th>
                <th>Total Qty</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                {{ $runningCost = $runningCost + $transaction->efe_cost }}
                {{ $runningQty = $runningQty + $transaction->efe_qty }}
                <td>
                    {{ $transaction->short_description }}
                </td>
                <td>
                    {{ $transaction->document_number }}
                </td>
                <td>
                    {{ $transaction->document_date }} 
                </td>
                <td>
                    {{ $transaction->note }}
                </td>
                <td>
                    {{ $transaction->product_qty }}
                </td>
                <td>
                    {{ $transaction->product_cost }}
                </td>
                <td>
                    {{ $runningCost / $runningQty }} 
                </td>
                <td>
                    {{ $runningCost }}
                </td>
                <td>
                    {{ $runningQty }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $transactions->links() }}
@else
There are no products
@endif
@stop