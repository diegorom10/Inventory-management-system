 <!--Modal resumen de corte-->
 <div class="modal fade" id="listarCortes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                  <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Todos los cortes realizados</h5>
                  </div>
                  <div class="modal-body mx-4" style="height: 30rem;">
                        <div class="row">
                            <div class="d-flex flex-column col-md-12">
                                <div class="table-wrapper-scroll-y my-custom-scrollbar-corte  p-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tabla-cortes">
                                            <colgroup>
                                            <col span="1" style="">
                                            <col span="1" style="">
                                            <col span="1" style="">
                                            <col span="1" style="">
                                            <col span="1" style="">
                                            </colgroup>
                                            <thead>
                                                    <th>Fecha del corte</th>
                                                    <th>Registrados</th>
                                                    <th>Total artículos</th>
                                                    <th>Disponibles</th>
                                                    <th>Comprometidos</th>
                                                    <th>Realizado por</th>
                                                    <th>Acciones</th>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div><!--.table-responsive-->
                                </div><!--.table-wrapper-->
                            </div><!--.d-flex-->
                        </div><!--.row-->
                    </div><!--.modal-body-->
                  <div class="modal-footer">
                    <button type="button"  id="close-listar-cortes" name="close-listar-cortes" class="btn btn-secondary">Regresar</button>
                  </div>
                  </div>
                </div>
</div><!--modal resumen de corte-->

<!--Modal resumen de corte-->
<div class="modal fade" id="modal_deleteCorte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                  <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Deseas eliminar este corte?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <div id="resumen-corte" class="d-flex flex-column pt-0 pl-3 pr-3 pb-3"></div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" id="btn_close_modal_deleteCorte" class="btn btn-secondary">Cerrar</button>
                    <button type="button" id="btn_deleteCorte" class="btn btn-primary">Eliminar</button>
                  </div>
                  </div>
                </div>
</div><!--modal resumen de corte-->



<script>
  var tablaCortes; //almacena la datatable, se tiene que destruir cuando se cierre el modal ya que no se puede reinizializar
  var corte_id;

      function cargarTabla(){
        
           tablaCortes = $('#tabla-cortes').DataTable({
            processing:true,
            serverSide:false,
            order:  [0, "desc"], //ordena por fecha de creacion. mas recientes primero
            ajax:{
                url: "inventario/fetchCortes",
            },
            method: "GET",
            columns:[
                {data: 'fecha',
                className: 'dt-body-center dt-body-corte'},
                {data: 'registradas',
                className: 'dt-body-center dt-body-corte'},
                {data: 'totalArticulos',
                className: 'dt-body-center dt-body-corte'},
                {data: 'totalDisponibles',
                className: 'dt-body-center dt-body-corte'},
                {data: 'totalComprometidas',
                className: 'dt-body-center dt-body-corte'},
                {data: 'personal',
                className: 'dt-body-center dt-body-corte'},
                {data: 'action', orderable:false,
                className: 'dt-body-center dt-body-corte'}
            ],
            responsive: true,
            autoWidth: false,
            "language": {
                searchPlaceholder: "aaaa-mm-dd",
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "Nada encontrado - Disculpa",
                "info": "Mostrando la página _PAGE_ de _PAGES_",
                "infoEmpty": "Sin registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                'search': 'Fecha: ',
                'paginate': {
                    'next': 'Siguiente',
                    'previous': 'Anterior',
                }
            }
          });

      }


      $(document).on('click','.delete-corte', function(){
           corte_id = $(this).attr('id');
           console.log(corte_id);
            $('#seccion_cortes #btn_deleteCorte').text('Eliminar'); 
            $('#seccion_cortes #modal_deleteCorte').modal({
                backdrop: 'static',
                keyboard: false
            });
            // $("#seccion_cortes #confirmModal").css('opacity', 1);
        }); 

        $("#seccion_cortes #btn_close_modal_deleteCorte").click(function(){
          $("#seccion_cortes #modal_deleteCorte").modal('toggle');
        });

          
            //abrir modal
            $('#link-listar-cortes').click(function(){
                cargarTabla();
                $('#seccion_cortes #listarCortes').modal({
                  backdrop: 'static',
                  keyboard: false
                });
            });

            //cerrar modal
            $('#close-listar-cortes').click(function(){
              tablaCortes.destroy();
            $('#listarCortes').modal('toggle');
          })
</script>