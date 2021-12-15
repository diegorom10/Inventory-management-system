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
      <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-backdrop="static" data-toggle="modal" data-target="#ajusteModal">Ajustes de artículos</button>
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
      <a href="{{Route('export.inventario')}}" style="background-color: #38A143; border:none; padding: 7px;" class="link-descarga btn btn-primary mx-2" title="Descargar el estado del inventario">Descargar Excel</a>

      <div class="dropdown" id="dropdown-ajustes">
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

        $(document).ready(function() {

          function descargaIniciada(){
            toastr.success('Ha comenzado la descarga', 'Descarga', {timeOut: 2000});	
          }

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


        });
        </script>

        @endsection