@extends('layouts.plantilla')

 @section('contenido')


 {{-- PENDIENTE: ESTILOS AL BOTON PARA QUE SE ALINIE CON MOVIL RESPONSIVE --}}
 <style> 
  @media (max-width: 576px) {
    button {
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .ltipo {
     display: block;
     text-align: center;
     margin: 10px;
    }
  }

  @media (max-width: 576px) {
    .itipo {
     display: block;
     margin-right: auto;
     margin-left: auto;
    }
  }

  @media (max-width: 576px) {
    .botontipo {
     width: 100%;
     margin-top: 10px; 
    }
  }

  @media (max-width: 576px) {
    .centerbutton {
     margin-left: auto;
     margin-right: auto;
    }
  }
</style>

<div class="mt-4 container">
  <div class="">
    <div class="p-4">
      <h3 class="text-center">Agregar nuevo Tipo de Entrada</h3>
    <form id="registro-entradas">
      @csrf
      <div class="nuevotipo">
        <label class="ltipo" style="margin-top: 2%;" for="txtTipo">Nueva Entrada</label>
      <div class="form-inline">
          <input style="width: 80%" type="text" class="form-control itipo" id="txtEntrada" name="txtEntrada" required>
          <div class="centerbutton">
            <button style="margin-left: 10px;" type="submit" class="botontipo btn btn-primary">Registrar Entrada</button>

          </div>
        
      </div>
    </form>

      </div>
      

 <!-- <div class="mt-2 container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" 
                aria-controls="home" aria-selected="true">Tipos de Entrada</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" 
                aria-controls="profile" aria-selected="false">Agregar Nuevo Tipo de Entrada</a>
            </li>

        </ul> -->

        <!-- <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <table id="tabla-entradas" class="cell-border hover">
                <thead>

                  <td class="td-table">Entrada</td>
                  <td class="td-table">Acciones</td>
                </thead>

              </table>
            </div> -->

            <!-- <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <h3>Agregar nuevo Tipo de Entrada</h3>
              <form id="registro-entradas">
                @csrf
                <div class="form-group">
                    <label for="txtEntrada">Nueva Entrada</label>
                    <textarea class="form-control" id="txtEntrada" name="txtEntrada" rows="1" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Entrada</button>
              </form>
            </div> -->

            <!-- Modal eliminar -->
            <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnEliminar" name="btnEliminar" class="btn btn-primary">Eliminar</button>
                  </div>
                </div>
              </div>
            </div>

        </div>

    <hr class="pb-2">
      <div class="mt-4 mb-4">
        <table id="tabla-entradas" class="cell-border hover">
          <thead>
          <td class="td-table">Entrada</td>
          <td class="td-table">Acciones</td>
          </thead>
        </table>
      </div>

</div>

<script type="text/javascript" src="js/entrances/show.js"></script>
<script type="text/javascript" src="js/entrances/add.js"></script>
<script type="text/javascript" src="js/entrances/delete.js"></script>

@endsection