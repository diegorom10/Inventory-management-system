@extends('layouts.plantilla')
@section('contenido')

<div class="container mt-4">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
        aria-selected="true">Catalogo</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
        aria-selected="false">Agregar herramienta</a>
    </li>
  </ul>
  <br>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

    <!-- <label>Herramienta:</label>
    <select name="tool" id="tool">

        <option value="1">Martillo</option>
        <option value="2">Desarmador</option>
        <option value="3">Tornillos</option>
        <option value="4349">Lijas</option>
        <option value="4350">Botas</option>
        <option value="4355">Soldadura</option>
        <option value="4356">Cables de Corriente</option>
        
    </select> -->

      <table id="tabla-catalogo" class="cell-border hover table table-dark" style="width:100%;">
      <colgroup>
                <col span="1" style="width: 55%;">
								<col span="1" style="width: 5%;">
								<col span="1" style="width: 5%;">
								<col span="1" style="width: 15%;">
								<col span="1" style="width: 20%;">
			</colgroup>
        <thead>
          <td class="td-table">Descripcion</td>
          <td class="td-table">Código</td>
          <td class="td-table">Serie</td>
          <td class="td-table">Categoria</td>
          <td class="td-table">Acciones</td>
        </thead>
      </table>

    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      <h3>Agregar nueva herramienta</h3>
      <form id="registro-herramienta" class="registro-herramienta">
        @csrf
        <div class="form-group">
          <label for="txtDescripcion">Descripcion</label>
          <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="1" required></textarea>
        </div>
        <div class="form-group">
          <label for="">Agrupación</label>
          <div class="custom-control custom-radio">
            <input type="radio" id="rbAgrupado" name="rbAgrupacion" value="Agrupado"
              class="custom-control-input rb-agrupacion" required>
            <label class="custom-control-label" for="rbAgrupado">Agrupado</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="rbUnico" name="rbAgrupacion" value="Unico"
              class="custom-control-input rb-agrupacion">
            <label class="custom-control-label" for="rbUnico">Unico</label>
          </div>
        </div>
        <div id="div-codigo">
          <div class="form-group">
            <label for="txtCodigo">Codigo</label>
            <input type="number" class="form-control" id="txtCodigo" name="txtCodigo">
            <small style="color:red;">*El codigo es para agrupados</small>
          </div>
        </div>
        <div id="div-serie">
          <div class="form-group">
            <label for="txtSerie">Serie</label>
            <input type="number" class="form-control" id="txtSerie" name="txtSerie">
            <small style="color:red;">*La serie es para objetos unicos</small>
          </div>
        </div>
        <div class="form-group">
          <select class="form-control form-select-lg" aria-label="Default select example" id="selCategoria"
            name="selCategoria" required>
            <option disabled selected value> -- Selecciona un tipo de herramienta --- </option>
            @foreach($tipos as $tipo)
            <option value={{$tipo->id}}>{{$tipo->tipo}}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar herramienta</button>
      </form>
    </div>

    <!-- Modal eliminar -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Confirmacion</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            ¿Desea eliminar el registro seleccionado?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="btnEliminar" name="btnEliminar" class="btn btn-primary">Eliminar</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal EDITAR -->
    <div class="modal fade" id="herramienta_edit_modal" data-backdrop="static" tabindex="-1" role="dialog"
      aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Editar Herramienta</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="herramienta_edit_form">
            <div class="modal-body">
              @csrf
              <input type="hidden" id="txtId2" name="txtId2">
              <div class="form-group">
                <label for="txtDescripcion2">Descripcion</label>
                <textarea class="form-control" id="txtDescripcion2" name="txtDescripcion2" rows="3"></textarea>
              </div>
              <div class="form-group" id="div-codigo2">
                <label for="txtCodigo2">Codigo</label>
                <input type="number" class="form-control" id="txtCodigo2" name="txtCodigo2"
                  aria-describedby="emailHelp">
              </div>
              <div class="form-group" id="div-serie2">
                <label for="txtSerie2">Serie</label>
                <input type="number" class="form-control" id="txtSerie2" name="txtSerie2" aria-describedby="emailHelp">
              </div>
              <div class="form-group">
                <select class="form-control form-select-lg" aria-label="Default select example" id="selCategoria2"
                  name="selCategoria2">
                  <option selected>categoria</option>
                  <option value="1">Pinzas</option>
                  <option value="2">Martillos</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
          </form>
          <!--form modal editar-->
        </div>
      </div>
    </div>
  </div>
  <!--div container-->

  <script type="text/javascript" src="js/catalogo/show.js"></script>
  <script type="text/javascript" src="js/catalogo/add.js"></script>
  <script type="text/javascript" src="js/catalogo/delete.js"></script>
  <script type="text/javascript" src="js/catalogo/edit.js"></script>
  <script type="text/javascript" src="js/catalogo/update.js"></script>

  <script>
    $('.rb-agrupacion').prop('checked', false);

  $('input[type="radio"]').click(function(){
  $("#txtCodigo").val($('#txtCodigo').prop("defaultValue"));
  $("#txtSerie").val($('#txtSerie').prop("defaultValue"));

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
    
    $(document).on('click','.descargar-kardex', function(){
      toastr.success('Descargando movimientos de esta herramienta', 'Descarga iniciada', {timeOut: 2000});	
    });
    

  </script>

  @endsection