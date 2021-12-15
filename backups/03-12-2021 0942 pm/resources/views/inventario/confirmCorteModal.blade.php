 <!--Modal resumen de corte-->
 <div class="modal fade" id="confirmCorte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width:600px;" role="document">
                  <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Resumen de corte</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <div id="resumen-corte" class="d-flex flex-column pt-0 pl-3 pr-3 pb-3"></div>
                  </div>
                  </div>
                </div>
</div><!--modal resumen de corte-->


<script>
    function hacerCorte(data){
             if(data !== null){
                  $.ajax({
                  url:"inventario/hacerCorte",
                  method:"POST",
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  data:{data},
                  error: function(error) {
                    toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
                    $('#realizar-corte').prop('disabled', false);
                    $('#realizar-corte').text('Hacer corte');
                    return false;
                  },
                  success:function(response)
                  {
                    if(response){
                      toastr.success('Se ha creado el corte', 'Exito', {timeOut: 800});
                      $('#realizar-corte').prop('disabled', false);
                      $('#realizar-corte').text('Hacer corte');
                      setTimeout(()=>{ $('#confirmCorte').modal('toggle');}, 1000);
                    }
                  },
                });
             }
          }

          function cargarResumenCorte(data){
            if(data == null){
                  toastr.warning('Hubo un error al obtener el estado del inventario', 'Error interno', {timeOut: 2000});
                  return false;
                }
                
                const herramientas_registradas = data.numero_herramientas;
                const total_unidades = data.total_unidades;
                const total_disponibles = data.total_disponibles;
                const total_comprometidas = data.total_comprometidas;
                const en_prestamo = data.en_prestamo;
                const faltantes = data.faltantes;
                const personal = data.personal;
                
                let resumen = `
                      
                            <div class="d-flex flex-row">
                              <div class="form-group d-flex flex-column align-items-center mr-2" style="width:50%;">
                                <label>Corte realizado el:</label>
                                <input type="text" class="form-control" style="text-align:center; pointer-events:none;" value="{{date("d-m-Y",time())}}" readonly>
                              </div>

                              <div class="form-group d-flex flex-column align-items-center ml-2" style="width:50%;">
                                <label>Realizado por:</label>
                                <input type="text" id="usuario_corte" class="form-control" style="text-align:center; pointer-events:none;" value="${personal}" readonly>
                              </div>
                            </div><!--.flex-row-->
                          
                            <div class="d-flex flex-row">
                              <div class="form-group d-flex flex-column align-items-center mr-2" style="width:33%">
                                <label>Artículos registrados:</label>
                                <input type="text" id="input_registradas" class="form-control" style="text-align:center; pointer-events:none;" value="${herramientas_registradas}" readonly>
                              </div>

                              <div class="form-group d-flex flex-column align-items-center mr-2" style="width:33%">
                                <label>Total de unidades:</label>
                                <input type="text" id="input_unidades" class="form-control" style="text-align:center; pointer-events:none;" value="${total_unidades}" readonly>
                              </div>

                              <div class="form-group d-flex flex-column align-items-center mr-2" style="width:33%">
                                <label>Unidades disponibles:</label>
                                <input type="text" id="input_disponibles" class="form-control" style="text-align:center; pointer-events:none;" value="${total_disponibles}" readonly>
                              </div>
                            </div><!--.flex-row-->

                            <div class="d-flex flex-row">
                              <div class="form-group d-flex flex-column align-items-center mr-2" style="width:33%;">
                                <label>Total comprometidas:</label>
                                <input type="text" class="form-control" id="input_comprometidas" style="text-align:center; pointer-events:none;" value="${total_comprometidas}" readonly>
                              </div>

                              <div class="form-group d-flex flex-column align-items-center mr-2" style="width:33%;">
                                <label>En prestamo ordinario:</label>
                                <input type="text" class="form-control" id="input_espera" style="text-align:center; pointer-events:none;" value="${en_prestamo}" readonly>
                              </div>

                              <div class="form-group d-flex flex-column align-items-center mr-2" style="width:33%;">
                                <label>En prestamo tardío:</label>
                                <input type="text" class="form-control" id="input_faltantes" style="text-align:center; pointer-events:none;" value="${faltantes}" readonly>
                              </div>
                            </div>

                            <div class="d-flex flex-column mt-3">
                            <button name="realizar-corte" id="realizar-corte" class="btn btn-success">
                                Hacer corte
                              </button>
                              </div>
                          </div>`;

              $("#resumen-corte").html(resumen);
              $('#btn-confirm-corte').text('Confirmar');
              $('#confirmCorte').modal('toggle');
          }

          //click en dropdown opcion "realizar corte"
          $('#link-realizar-corte').click(function(){
            $.getJSON("inventario/fetchEstado", function(data) {
                  cargarResumenCorte(data);
              });
          });

          //click boton hacer corte
          $(document).on('click', '#realizar-corte', function(e){
              e.preventDefault();
              $('#realizar-corte').prop('disabled', true);
              $('#realizar-corte').text('Haciendo corte');
              $.getJSON("inventario/fetchEstado", function(data) {
                  hacerCorte(data); 
              });
            });

</script>