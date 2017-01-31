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
                        var aData = table.rows({selected: true}).data().toArray();
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
                                        '<div class="col-xs-3"> {{ Form::number("product_qty[]",null,array("class"=>"form-control input-sm","step"=>"any")) }} </div> ' +
                                        '<div class="col-xs-3"> {{ Form::number("product_cost[]",null,array("class"=>"form-control input-sm","step"=>"any")) }} </div> ' +
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
                "type": "POST",
                'headers': {'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
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

        $('#example').removeClass('display')
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
<div class ="container-fluid">
    <h1> Create Inv Transaction </h1>
    {{ Form::open(array('route'=>'invTransactions.store','class'=>'form','role'=>'form')) }}
    <div class="form-group col-xs-3 col-md-3">
        {{ Form::label('storage_id', 'Storage',array("class"=>"control-label")) }}
        {{ Form::select('storage_id', $storages, null, array('class'=>'form-control')) }}
    </div>
    <div class="form-group col-xs-3 col-md-3">
        {{ Form::label('transaction_type_id', 'Type',array("class"=>"control-label")) }}
        {{ Form::select('transaction_type_id', $transactionTypes, null, array('class'=>'form-control')) }}
    </div>
    <div class="form-group col-xs-3 col-md-3">
        {{ Form::label('date', 'Date', array("class"=>"control-label")) }}
        {{ Form::text('document_date', date('Y-m-d'), array('class'=>'form-control',"id"=>"document_date")) }}
    </div>
    <div class="form-group col-xs-3 col-md-3">
        {{ Form::label('document_number', 'Number', array("class"=>"control-label")) }}
        {{ Form::text('document_number', null, array('class'=>'form-control',"id"=>"document_number")) }}
    </div>
    <div class="col-xs-4">
        <b>Product</b>
    </div>
    <div class="col-xs-3">
        <b>Qt</b>
    </div>
    <div class="col-xs-3">
        <b>Cost</b>
    </div>
    <div class="col-xs-2">
    </div>
    <div id="products">
    </div>
    {{ Html::link('#', 'Add Items',array('class'=>'btn btn-success form-control','id'=>'addProducts')) }}
    {{ Form::submit('Submit', array('class'=>'btn btn-primary form-control')) }}
    {{ link_to_route('invTransactions.index', 'Cancel', [],array('class'=>'btn btn-default form-control')) }}
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

@stop
