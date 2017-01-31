@extends('master')

@section('products_active')
active
@stop

@section('header')

<script type='text/javascript'>
    /*
     * Script to delete a product in the purchase list
     */
    $(window).load(function () {
        $(document).on('click', '#removedescriptor', function () {
            $(this).parents('#productRow').remove();
            return false;
        });
    });
</script>

<script type='text/javascript'>
    /*Shows a datepicker widget for
     * the purchase_date text input control
     */
    $(function () {
        $("#purchase_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
    });</script>

<script type='text/javascript'>
    /*
     * Displays list of products using
     * a datatables jQuery plugin on table id="example"
     */
    $(document).ready(function () {
        //add current products to list
        addToProducts( {{ $products_transaction }} );

    var table = $('#example').dataTable({
    "processing": true,
            "serverSide": true,
            "iDisplayLength": 5,
            "aLengthMenu": [
                    [5, 10, 25, 50, - 1],
                    [5, 10, 25, 50, "All"]],
            dom: 'T<"clear">lfrtip',
            tableTools: {
            "sRowSelect": "multi",
                    "aButtons": [
                    {"sExtends": "text", "sButtonText": "add new product",
                            "fnClick": function (nButton, oConfig, oFlash) {
                                window.open('{{ route("products.create") }}');
                                return false;
                            }
                    },
                    {"sExtends": "text", "sButtonText": "add to purchase",
                            "fnClick": function (nButton, oConfig, oFlash) {
                                var oTT = TableTools.fnGetInstance('example');
                                var aData = oTT.fnGetSelectedData()
                                var values = $("input[id='productarray']")//gets the value of all elements whose id is productarray
                                        .map(function () {
                                            return parseInt($(this).val());
                                        }).get();
                                for (nCount = 0; nCount < aData.length; nCount++) {
                                    //check if there exists a product with same id in purchase list
                                    //$.inArray only compares between numbers or characters
                                    //so I converted the values to Int within the array before comparison.
                                    if (!values.length || $.inArray(aData[nCount]['product_id'], values) === -1) {
                                        addToProducts([{
                                                "id": null,
                                                "product_id": aData[nCount]['product_id'],
                                                "product_description": aData[nCount]['product_description'],
                                                "amount": 0,
                                                "total": 0}]);
                                        $('#myModal').modal('hide');
                                    }
                                }
                            }
                    }
                    ]
            },
            "ajax": {
            "url": "{{ url('jproducts') }}",
                    "type": "GET"
            },
            "columnDefs": [
            {
            "targets": [0],
                    "visible": false,
                    "searchable": false
            }
            ],
            "columns": [//tells where (from data) the columns are to be placed
            {"data": "product_id"},
            {"data": "product_description"}

            ]
    });
    });
            $(document).on('click', '#addProducts', function () {
        //Show modal bootstrap
        $('#myModal').modal('show');
        //return
    });

    function addToProducts(productArray) {

        for (var i = 0; i < productArray.length; i++) {

            $('<div class="container container-fluid">' +
                    '<div class="row" id="productRow">' +
                    '<input type="hidden" id="productarray" name="id[]" value=' + productArray[i].id + '>' +
                    '<input type="hidden" id="productarray" name="product_id[]" value=' + productArray[i].product_id + '>' +
                    '<div class="col-xs-4"> {{ "' + productArray[i].product_description + '" }} </div> ' +
                    '<div class="col-xs-3"> <input class="form-control input-sm" name="amount[]" type="number" min ="0" step="0.01" value="' + productArray[i].amount + '"> </div> ' +
                    '<div class="col-xs-3"> <input class="form-control input-sm" name="total[]" type="number" min ="0" step="0.01" value="' + productArray[i].total + '"> </div> ' +
                    '<div class="col-xs-2"> <a href="#" id="removedescriptor">' +
                    '{{ HTML::image("img/delete.png", "remove", array( "width" => 16, "height" => 16 )) }} ' +
                    '</a></div> ' +
                    '</div></div>').appendTo('#products');
        }
    }


</script>

@stop

@section('main')

<h1> Edit purchase </h1>


<div class="container">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-xs-12">

                {{ Form::model($invTransactionHeader, array('method'=>'PATCH', 'route'=> array('invTransactions.update', $invTransactionHeader->id),'class'=>'form-horizontal','role'=>'form'))  }}

                <div class="form-group row">
                    {{ Form::label('transaction_type_id', 'Transaction Type',array("class"=>"col-xs-2 control-label")) }}
                    <div class=" col-xs-10">
                        {{ Form::select('transaction_type_id', $transaction_types, null, array('class'=>'form-control')) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('date', 'Date', array("class"=>"col-xs-2 control-label")) }}
                    <div class=" col-xs-10">
                        {{ Form::text('document_date', date('Y-m-d'), array('class'=>'form-control',"id"=>"document_date")) }}
                    </div>
                </div>

                <p></p>
                
                <div class="row">
                    <dt>
                    <div class="col-xs-4">
                        Product
                    </div>
                    <div class="col-xs-3">
                        Qt
                    </div>
                    <div class="col-xs-3">
                        Cost
                    </div>
                    <div class="col-xs-2">
                    </div>
                </div>

                <div class="row" id="products">
                </div>
                
                <p></p>
                {{ Html::link('#', 'Add Items',array('class'=>'btn btn-success btn-block col-xs-12','id'=>'addProducts')) }}
                <p></p>
                {{ Form::submit('Submit', array('class'=>'btn  btn-primary col-xs-6')) }}
                {{ link_to_route('invTransactions.index', 'Cancel', [],array('class'=>'btn btn-default col-xs-6')) }}
                {{ Form::close() }}

            </div>

            <p></p>

            </dt>
        </div>
    </div>
</div>


{{-- bootstrap modal --}}

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Search products</h4>
            </div>
            <div class="modal-body">
                <table id="example" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Product</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Product</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- bootstrap modal --}}
@if ($errors->any())
<ul>
    {{ implode('',$errors->all('<li class="error">:message</li>')) }}
</ul>
@endif

@stop