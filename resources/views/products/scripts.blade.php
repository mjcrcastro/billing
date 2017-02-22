<script type='text/javascript'>
    /*
     * Displays list of products using
     * a datatables jQuery plugin on table id="example"
     */
    var dataSet = {!! json_encode($transactions) !!};
            $(document).ready(function () {
    $('#example').DataTable({
    dom: 'Bfrtip',
            buttons: [
                    'print'
            ],
            "ordering": false,
            data: dataSet,
            columns: [
            {title: "Type"},
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