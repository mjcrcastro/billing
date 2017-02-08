@extends('master')

@section('products_active')
active
@stop


@section('main')
<div class ="container-fluid">
    <h1> Edit Inv Transaction </h1>
    {{ Form::model($invTransactionHeader, array('method'=>'PATCH', 'route'=> array('invTransactions.update', $invTransactionHeader->id),'class'=>'form'))  }}
        @include('invTransactions.form')
    {{ Form::close() }}
</div>

    @include('invTransactions.modal')
    
<script type="text/javascript">
    $(document).ready(
            function ()
            {
                productArray = {!! json_encode($productstransaction) !!};
                //add current products to list
                for (var i = 0; i < productArray.length; i++) {
                    addToProducts(productArray[i].id,
                                    productArray[i].product_id, 
                                    productArray[i].product_description,
                                    productArray[i].product_qty,
                                    productArray[i].product_cost);
                }
            }
    );
    
</script>

@stop
