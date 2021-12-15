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
      <button style="border: solid #000 1px" type="button" id="butonPrestamo" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-backdrop="static" data-toggle="modal" data-target="#prestamoModal">Préstamo de Material</button>
      <button style="border: solid #000 1px" type="button" id="buttonRegreso" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-backdrop="static" data-toggle="modal" data-target="#regresoModal">Regreso de Material</button>
    </div>
    <div class="column">
      <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-backdrop="static" data-toggle="modal" data-target="#llegadaModal">Llegada de Material</button>
      <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-backdrop="static" data-toggle="modal" data-target="#retiradaModal">Retiro de Material</button>
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
      <td class="td-table">Codigo</td>
        <td class="td-table">Descripcion</td>
        <td class="td-table">Original</td>
        <td class="td-table">Disponible</td>
        <td class="td-table">Prestado</td>
      </thead>
    </table>

    <a href="{{Route('inventario.export')}}" class="btn btn-primary">Descargar Excel</a>

  </div>


  @include('inventario.prestamoModal')
  @include('inventario.llegadaModal')
  @include('inventario.regresoModal')



      <div id="backdrop"></div> <!--se oscurece con modal simulado-->
</div><!-- .container -->
        <script type="text/javascript" src="js/inventario/show.js"></script>
        <script type="text/javascript" src="js/catalogo/add.js"></script>
        <script type="text/javascript" src="js/catalogo/delete.js"></script>
        <script type="text/javascript" src="js/catalogo/edit.js"></script>
        <script type="text/javascript" src="js/catalogo/update.js"></script>
        <script>

        $(document).ready(function() {
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
              });
        </script>

        @endsection