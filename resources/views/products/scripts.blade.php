<script type='text/javascript'>
    /*
     * Displays list of products using
     * a datatables jQuery plugin on table id="example"
     */
    var dataSet = {!! json_encode($kardex) !!};
            $(document).ready(function () {
    $('#example').DataTable({
    dom: 'Bfrtip',
            buttons: [
                    {
                extend: 'print',
                customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' );
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }
            }
            ],
            "ordering": false,
            data: dataSet,
            columns: [
            {title: "none"},
            {title: "Number"},
            {title: "Date"},
            {title: "Note"},
            {title: "Qty"},
            {title: "Cost"},
            {title: "Average"},
            {title: "Total Cost"},
            {title: "Total Qty"}
            ]
    });
            $('#example').removeClass('display')
            .addClass('table table-striped table-bordered');
    });

</script>