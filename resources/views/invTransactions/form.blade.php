<div class="form-group form-group-sm">
    {{ Form::label('storage_id', 'Storage',array("class"=>"control-label small")) }}
    {{ Form::select('storage_id', $storages, null, array('class'=>'form-control')) }}
</div>    
<div class="form-group form-group-sm">
    {{ Form::label('transaction_type_id', 'Type',array("class"=>"control-label small")) }}
    {{ Form::select('transaction_type_id', $transaction_types, null, array('class'=>'form-control')) }}
</div>
<div class="form-group form-group-sm">
    {{ Form::label('document_date', 'Date', array("class"=>"control-label small")) }}
    {{ Form::text('document_date', null, array('class'=>'form-control')) }}
</div>
<div class="form-group form-group-sm">
    {{ Form::label('document_number', 'Number', array("class"=>"control-label small")) }}
    {{ Form::text('document_number', null, array('class'=>'form-control',"id"=>"document_number")) }}
</div>
<div class="form-group form-group-sm">
    {{ Form::label('note', 'Note', array("class"=>"control-label small")) }}
    {{ Form::text('note', null, array('class'=>'form-control',"id"=>"note")) }}
</div>
{{ Html::link('#addProducts', 'Add Items',array('class'=>'btn btn-success form-control small','id'=>'addProducts')) }}
{{ Form::submit('Submit', array('class'=>'btn btn-primary form-control small')) }}
{{ link_to_route('invTransactions.index', 'Cancel', [],array('class'=>'btn btn-default form-control small')) }}
<hr>
<div class="row">
    <div class="col-xs-5 small">
        <b>Product</b>
    </div>
    <div class="col-xs-3 small">
        <b>Qt</b>
    </div>
    <div class="col-xs-3 small">
        <b id="cost_label" >Cost</b>
    </div>
    <div class="col-xs-1 small">
    </div>
</div>
<div id="products">
</div>

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
        //change to price or cost in function of the trans type
        priceOrCost();

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
                                addToProducts(null,
                                        aData[nCount]['product_id'],
                                        aData[nCount]['product_description'],
                                        null);
                            }
                        }
                        $('#myModal').modal('hide');
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

</script>

<script type='text/javascript'>
    /*dynamically adds a product to the product list
     * id: corresponds to inv_transactions_detail.id
     * product row is added <div id="products">
     */
    function addToProducts(id, product_id, product_description, product_qty, product_cost) {

        $('<div id="productRow" class="row">' +
                '<div><input type="hidden" id="detailarray" name="detail_id[]" value=' + id + '></div>' +
                '<div><input type="hidden" id="productarray" name="product_id[]" value=' + product_id + '></div>' +
                '<div class="col-xs-5 small">  ' + product_description + '  </div>' +
                '<div class="col-xs-3"> <input class="form-control input-sm" name="product_qty[]" type="number" min ="0" step="0.01" value="' + product_qty + '"> </div> ' +
                '<div class="col-xs-3"> <input class="form-control input-sm" name="product_cost[]" type="number" min ="0" step="0.01" value="' + product_cost + '"> </div> ' +
                '<div class="col-xs-1"> <a href="#" id="removedescriptor">' + '{{ Html::image("img/delete.png", "remove", array( "width" => 16, "height" => 16 )) }} ' + '</a></div>' +
                '</div>').prependTo('#products');
    }
</script>

<script type='text/javascript'>
    $("#transaction_type_id").change(function () {
        priceOrCost();
        return false;
    });
</script>

<script type="text/javascript">
    //function is called to change the label from cost to price
    //as a function of the current transaction
    //for a bill (factura) the label should be set to price
    //for any other transaction, the label is put to cost
    function priceOrCost() {
        $var = $("#transaction_type_id").find(":selected").val();
        if ($var === "{{ $fact_id }}") {
            $("b#cost_label").html("Price");
        } else {
            $("b#cost_label").html("Cost");
        }
        ;
    }

</script>
