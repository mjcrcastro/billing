@extends('master')

@section('products_active')
active
@stop

@section('header')



<script type='text/javascript'>
    /*Shows a datepicker widget for
     * the purchase_date text input control
     */
    $(function () {
        $("#document_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
    });

    $(document).on('click', '#removedescriptor', function () {
        $(this).parents('#productRow').remove();
        return false;
    });

</script>

<script type='text/javascript'>
    /*
     * Displays list of products using
     * a datatables jQuery plugin on table id="example"
     */
    $(document).ready(function () {
        var table = $('#example').DataTable({
            "processing": true,
            "serverSide": true,
             select: true,
            "iDisplayLength": 5,
            "aLengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]],
            dom: 'T<"clear">lfrtBip',
            buttons: [
                {
                    text: 'add new product',
                    action: function (e, dt, node, conf) {
                        window.open('{{ route("products.create") }}');
                        return false;
                    }
                },
                {
                    text: 'add to purchase',
                    action: function (e, dt, node, conf) {
                        var aData = table.rows({ selected: true }).data().toArray();
                        var values = $("input[id='productarray']")//gets the value of all elements whose id is productarray
                                .map(function () {
                                    return parseInt($(this).val());
                                }).get();
                        for (nCount = 0; nCount < aData.length; nCount++) {
                            //check if there exists a product with same id in purchase list
                            //$.inArray only compares between numbers or characters
                            //so I converted the values to Int within the array before comparison.
                            if (!values.length || $.inArray(aData[nCount]['product_id'], values) === -1) {
                                $('<id="productRow">' +
                                        '<input type="hidden" id="productarray" name="product_id[]" value=' + aData[nCount]['product_id'] + '>' +
                                        '<div class="col-xs-4">  ' + aData[nCount]['product_description'] + ' </div> ' +
                                        '<div class="col-xs-3"> {{ Form::number("amount[]",null,array("class"=>"form-control input-sm","step"=>"any")) }} </div> ' +
                                        '<div class="col-xs-3"> {{ Form::number("total[]",null,array("class"=>"form-control input-sm","step"=>"any")) }} </div> ' +
                                        '<div class="col-xs-2"> <a href="#" id="removedescriptor">' +
                                        '{{ Html::image("img/delete.png", "remove", array( "width" => 16, "height" => 16 )) }} ' +
                                        '</a></div> ' +
                                        '</div>').appendTo('#products');
                                $('#myModal').modal('hide');
                            }
                        }
                    }
                }
            ],
            "ajax": {
                "url": "{{ url('jproducts') }}",
                "type": "GET",
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

        $('#example')
                .removeClass('display')
                .addClass('table table-striped table-bordered');
    });

    $(document).on('click', '#addProducts', function () {
        //Show modal bootstrap
        $('#myModal').modal('show');
        //return
    });


</script>

@stop

@section('main')

<h1> Create Inv Transaction </h1>

<div class="container-fluid">
    {{ Form::open(array('route'=>'invTransactionHeaders.store','class'=>'form-horizontal','role'=>'form')) }}

    <div class="form-group row">
        <div class=" col-xs-2">
            {{ Form::label('transactionType_id', 'Transaction Type',array("class"=>"control-label pull-right")) }}
        </div>
        <div class=" col-xs-10">
            {{ Form::select('transactionType_id', $transactionTypes, null, array('class'=>'form-control')) }}
        </div>
    </div>
    <div class="form-group row">
        <div class=" col-xs-2">
            {{ Form::label('date', 'Date', array("class"=>"control-label pull-right")) }}
        </div>
        <div class=" col-xs-10">
            {{ Form::text('document_date', date('Y-m-d'), array('class'=>'form-control',"id"=>"document_date")) }}
        </div>
    </div>

    <div class="row">
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
    {{ Html::link('#', 'Add Items',array('class'=>'col-xs-4 btn btn-success','id'=>'addProducts')) }}
    {{ Form::submit('Submit', array('class'=>'col-xs-4 btn btn-primary')) }}
    {{ link_to_route('invTransactionHeaders.index', 'Cancel', [],array('class'=>'col-xs-4 btn btn-default')) }}
    {{ Form::close() }}

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