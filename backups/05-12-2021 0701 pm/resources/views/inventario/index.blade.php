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
      <button style="border: solid #000 1px" type="button" id="butonPrestamo" class="col-md-5 btn btn-lg m-2 boton-principales" data-backdrop="static" data-toggle="modal" data-target="#prestamoModal">Hacer un prestamo</button>
      <button style="border: solid #000 1px" type="button" id="buttonRegreso" class="col-md-5 btn btn-lg m-2 boton-principales" data-backdrop="static" data-toggle="modal" data-target="#regresoModal">Regresar un prestamo</button>
    </div>
    <div class="column">
      <button style="border: solid #000 1px" type="button" class="col-md-5 btn btn-lg m-2 boton-principales" data-backdrop="static" data-toggle="modal" data-target="#llegadaModal" id="buttonEntrada">Entrada de artículos</button>
      <button style="border: solid #000 1px" type="button" class="col-md-5 btn btn-lg m-2 boton-principales" data-backdrop="static" data-toggle="modal" data-target="#ajusteModal" id="buttonAjuste">Ajustes de artículos</button>
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




  </div>
    
  <section id="seccion_prestamos">
    @include('inventario.prestamoModal')
    @include('inventario.llegadaModal')
    @include('inventario.regresoModal')
    @include('inventario.ajusteModal')
    @include('inventario.pendientesModal')
  </section>
  <section id="seccion_cortes">
    @include('inventario.confirmCorteModal')
    @include('inventario.listarCortesModal')
  </section>


      <div id="backdrop"></div> <!--se oscurece con modal simulado-->
</div><!-- .container -->
        <script type="text/javascript" src="js/inventario/show.js"></script>
      
        <script>
            function descargaIniciada(){
              toastr.success('Ha comenzado la descarga', 'Descarga', {timeOut: 2000});	
            }

            $('.link-descarga').click(()=>{descargaIniciada()});

            $('#dropdown-ajustes a').click(function(){ 
              let id = $(this).attr('id');
              if(id != "link-ajuste") descargaIniciada();
            });
        </script>

        @endsection