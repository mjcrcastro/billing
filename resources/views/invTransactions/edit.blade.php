@extends('master')

@section('products_active')
active
@stop


@section('main')
<div class ="container-fluid">
    <h1> Edit Inv Transaction </h1>
    {{ Form::model($invTransactionHeader, array('method'=>'PATCH', 'route'=> array('invTransactions.update', $invTransactionHeader->id),'class'=>'form','role'=>'form'))  }}
        @include('invTransactions.form')
    {{ Form::close() }}
</div>

    @include('invTransactions.modal')
    
<script type="text/javascript">
    $(document).ready(
            function ()
            {
                //add current products to list
                addToProducts({!! json_encode($productstransaction) !!}
                );
            }
    );
    
    
    /*
     * Displays list of products using
     * a datatables jQuery plugin on table id="example"
     */

    function addToProducts(productArray) {

        for (var i = 0; i < productArray.length; i++) {

            $('<div class="row" id="productRow">' +
                    '<input type="hidden" id="detailarray" name="detail_id[]" value=' + productArray[i].id + '>' +
                    '<input type="hidden" id="productarray" name="product_id[]" value=' + productArray[i].product_id + '>' +
                    '<div class="col-xs-4">  ' + productArray[i].product_description + '  </div> ' +
                    '<div class="col-xs-3"> <input class="form-control input-sm" name="product_qty[]" type="number" min ="0" step="0.01" value="' + productArray[i].product_qty + '"> </div> ' +
                    '<div class="col-xs-3"> <input class="form-control input-sm" name="product_cost[]" type="number" min ="0" step="0.01" value="' + productArray[i].product_cost + '"> </div> ' +
                    '<div class="col-xs-2"> <a href="#" id="removedescriptor">' +
                    '{{ Html::image("img/delete.png", "remove", array( "width" => 16, "height" => 16 )) }} ' +
                    '</a></div> ' +
                    '</div>').appendTo('#products');
        }
    }
    
</script>

@stop
