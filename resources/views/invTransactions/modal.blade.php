{{-- bootstrap modal --}}

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Busqueda de productos</h4>
            </div>
            <div class="modal-body">
                <table id="example" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Producto</th>
                            <th>Notas</th>
                            <th>Existencia</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Producto</th>
                            <th>Notas</th>
                            <th>Existencia</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
$(document).on('click', '#addProducts', function () {
        //Show modal bootstrap
        $('#myModal').modal('show');
        //return
    });

</script>

{{-- bootstrap modal --}}