<script type='text/javascript'>
    /*
     * Displays list of products using
     * a datatables jQuery plugin on table id="example"
     */
    var dataSet = {!! json_encode($productsArray) !!};
            $(document).ready(function () {
    $('#example').DataTable({
        "pageLength": 8,
    dom: 'Bfrtip',
            buttons: [
            {
            extend: 'print',
            exportOptions: {
                    columns: [ 1, 2, 3, 4 ]
                },
                    customize: function (win) {
                    $(win.document.body)
                            .css('font-size', '8pt');
                            $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
            }
            ],
            "ordering": true,
            data: dataSet,
            columns: [
            { "visible": false },
            {title: "Descripcion"},
            {title: "Tipo"},
            {title: "Ubicacion"},
            {title: "Cantidad"},
             /* Kardex */ {
            mRender: function (data, type, row) {
                return '<a href="/products/'+ row[0] + '">Kardex</a>'
            }
        },
            /* EDIT */ {
            mRender: function (data, type, row) {
                return '<a href="/products/'+ row[0] + '/edit" class="btn btn-info">Editar</a>'
            }
        },
        /* DELETE */ {
            mRender: function (data, type, row) {
                return '<form method="POST" action="/products/' + row[0] + '" accept-charset="UTF-8">' +
                        '<input name="_method" type="hidden" value="DELETE">' +
                        '{{ csrf_field() }}' +
                        '<input class="btn btn-danger " onclick="if(!confirm(&#039;Are you sure to delete this item?&#039;)){return false;};" type="submit" value="Delete">' +
                        '</form>'
                
            }
        },
            ]
    });
            $('#example').removeClass('display')
            .addClass('table table-striped table-bordered');
    });

</script> 