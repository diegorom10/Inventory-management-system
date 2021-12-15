@extends('layouts.plantilla')

@section('contenido')

<style>
  @media (max-width: 576px) {
    .column {
      position: relative;
      left: -8px;
    }
   }

</style>

<div class="mt-4 container">
  <div class="botones" style="margin-bottom: 15px; text-align:center;">
    <div class="column">
      <button style="border: solid #000 1px" type="button" id="butonPrestamo" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-backdrop="static" data-toggle="modal" data-target="#prestamoModal">Hacer un prestamo</button>
      <button style="border: solid #000 1px" type="button" id="buttonRegreso" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-backdrop="static" data-toggle="modal" data-target="#regresoModal">Regresar un prestamo</button>
    </div>
    <div class="column">
      <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-backdrop="static" data-toggle="modal" data-target="#llegadaModal" id="buttonEntrada">Entrada de artículos</button>
      <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-backdrop="static" data-toggle="modal" data-target="#ajusteModal" id="buttonAjuste">Ajustes de artículos</button>
    </div>
  </div>
  <!--hacer_movimiento-->


  <div class="contenedor" style="margin-bottom: 25px">
    <table id="tabla-inventario" class="cell-border hover table table-dark" style="width:100%;">
    <colgroup>
                <col span="1" style="width: 10%;">
								<col span="1" style="width: 66%;">
								<col span="1" style="width: 8%;">
								<col span="1" style="width: 8%;">
								<col span="1" style="width: 8%;">
			</colgroup>
      <thead>
      <td class="td-table">Codigo/SN</td>
        <td class="td-table">Descripcion</td>
        <td class="td-table">Total</td>
        <td class="td-table">Disponible</td>
        <td class="td-table">Prestado</td>
      </thead>
    </table>

    <div class="d-flex">
      <a href="{{Route('export.inventario')}}" style="background-color: #38A143; border:none; padding: 7px;" class="link-descarga btn btn-primary mx-1" title="Descargar el estado del inventario">Descargar Excel</a>

      <div class="dropdown mx-1" id="button-dropdown-cortes">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownCortesButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 6px;">
         Cortes
        </button>
        <div class="dropdown-menu" id="dropdown-cortes" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" id="link-realizar-corte">Hacer un corte de inventario</a>
          <a class="dropdown-item" id="link-listar-cortes" data-backdrop="static" data-toggle="modal" data-target="#cortesModal">Ver todos los cortes</a>
        </div>
      </div>
      

      <div class="dropdown mx-1" id="button-dropdown-ajustes">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 6px;">
          Pendientes
        </button>
        <div class="dropdown-menu" id="dropdown-ajustes" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" id="link-ajuste" data-backdrop="static" data-toggle="modal" data-target="#pendientesModal">Recuperar o perder herramientas</a>
          <a class="dropdown-item" class="link-descarga" id="link-perdidos" href="{{Route('export.faltantes', ['accion'=> 'perdidos'])}}">Descargar historial de perdidos</a>
          <a class="dropdown-item" class="link-descarga" id="link-recuperados" href="{{Route('export.faltantes', ['accion'=> 'recuperados'])}}">Descargar historial de recuperados</a>
        </div>
      </div>
    </div>

     <!--Modal resumen-->
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
                      <div id="resumen-corte" class="d-flex flex-column p-3"></div>
                      <!-- <div id="div-button d-flex flex-column p-3">
                        <button></button>
                      </div> -->
                  </div>
                  <div class="modal-footer">
                    <button type="button"  id="close-confirmar-corte" name="close-confirmar-corte" class="btn btn-secondary">Regresar</button>
                  </div>
                  </div>
                </div>
            </div><!--modal resumen-->


  </div>


  @include('inventario.prestamoModal')
  @include('inventario.llegadaModal')
  @include('inventario.regresoModal')
  @include('inventario.ajusteModal')
  @include('inventario.pendientesModal')





      <div id="backdrop"></div> <!--se oscurece con modal simulado-->
</div><!-- .container -->
        <script type="text/javascript" src="js/inventario/show.js"></script>
        <script type="text/javascript" src="js/catalogo/add.js"></script>
        <script type="text/javascript" src="js/catalogo/delete.js"></script>
        <script type="text/javascript" src="js/catalogo/edit.js"></script>
        <script type="text/javascript" src="js/catalogo/update.js"></script>
        <script>



// inicia js de corte
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
         

          function descargaIniciada(){
            toastr.success('Ha comenzado la descarga', 'Descarga', {timeOut: 2000});	
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

                            <div class="d-flex flex-column">
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

            //cerrar modal
            $('#close-confirmar-corte').click(function(){
            $('#confirmCorte').modal('toggle');
          })

//termina js de corte
       
          $('.rb-agrupacion').prop('checked', false);

          $('input[type="radio"]').click(function(){
                  if($(this).attr("value")=="Agrupado"){
                      $("#div-codigo").show('slow');
                      $("#div-serie").hide('slow');
                  }else{
                    if($(this).attr("value")=="Unico"){
                      $("#div-serie").show('slow');
                      $("#div-codigo").hide('slow');
                  }
                  }         
              });

              $('.link-descarga').click(function(){
                  toastr.success('Ha comenzado la descarga', 'Descarga', {timeOut: 2000});	
                  console.log("click link");
              });

              $('#dropdown-ajustes a').click(function(){ 
                let id = $(this).attr('id');
                if(id != "link-ajuste"){
                  toastr.success('Ha comenzado la descarga', 'Descarga', {timeOut: 2000});
                }
              });


   
        </script>

        @endsection