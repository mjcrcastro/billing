<script type='text/javascript'>
    /*
     * Displays list of products using
     * a datatables jQuery plugin on table id="example"
     */
    var dataSet = {!! json_encode($trans_array) !!};
            $(document).ready(function () {
    $('#example').DataTable({
    dom: 'Bfrtip',
            buttons: [
            {
            extend: 'print',
                    customize: function (win) {
                    $(win.document.body)
                            .css('font-size', '10pt');
                            $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
            }
            ],
            "ordering": false,
            data: dataSet,
            columns: [
            { "visible": false },
            {title: "Tipo"},
            {title: "Numero"},
            {title: "Fecha"},
            {title: "Nota"},
            {title: "Bodega"},
            /* EDIT */ {
            mRender: function (data, type, row) {
                return '<a href="http://inv.divino/invTransactions/'+ row[0] + '/edit" class="btn btn-info ">Editar</a>'
            }
        },
        /* DELETE */ {
            mRender: function (data, type, row) {2
                return '{{ Form::open(array("method"=>"DELETE", "route"=>array("invTransactions.destroy",' +  row[0] + '))) }}' + 
                '{{ Form::submit("Borrar", array("class"=>"btn btn-danger ".Config::get("global/default.button_size"), "onclick"=>"if(!confirm("Are you sure to delete this item?")){return false;};")) }}' +
                '{{ Form::close() }}'
            }
        },
            ]
    });
            $('#example').removeClass('display')
            .addClass('table table-striped table-bordered');
    });

</script>