@extends('layouts.plantilla')
@section('contenido')

 <style> 
   /* @media (max-width: 576px) {
     button {
       width: 100%;
     }
   } */

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
      <!-- <h3 class="text-center letra-tipo"><i class="fa fa-pencil" aria-hidden="true"></i>Agregar Nuevo Tipo de Herramienta</h3> -->
    <form id="registro-tipo">
      @csrf
      <div class="nuevoTipo">
        <!-- <label class="ltipo" style="margin-top: 2%;" for="txtTipo">Nuevo Tipo</label> -->
      <div class="form-inline">
          <input style="width: 85%" type="text" class="form-control itipo" id="txtTipo" name="txtTipo" placeholder="Agrega un nuevo tipo de herramienta" required>
          <div>
            <button style="margin-left: 10px; width:100%" type="submit" class="botontipo btn btn-primary">Registrar tipo</button>
          </div>
      </div>
      
    </form>
      </div>
      

    <!-- Modal eliminar -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><strong>¿Deseas eliminar esta categoría?</strong></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Las herramientas que pertenezcan a este tipo de herramienta dejarán de tener categoria.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="btnEliminar" name="btnEliminar" class="btn btn-primary">Eliminar</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal Editar -->
    <div class="modal fade" id="confirmModaleditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar tipo de herramienta</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="tipoherramienta_edit_form">
          <div class="modal-body">
            <input type="hidden" id="tipoid" name="tipoid">
            <div class="form-group">
              <label for="editartipo">Tipo de herramienta</label>
              <textarea class="form-control" id="editartipo" name="editartipo" rows="3"></textarea>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="btnEditar" name="btnEditar" class="btn btn-primary">Guardar</button>
          </div>
        </form>
        </div>
      </div>
  
     </div>

    

  </div>
  </div>

    </div>
    
<hr class="pb-2">
    <div class="mt-4 mb-4"> 
      <table id="tabla-tipo" class="cell-border hover" style="width:100%;">
      <colgroup>
                <col span="1" style="width: 80%;">
								<col span="1" style="width: 20%;">
			</colgroup>
        <thead>
          <td class="td-table" id="tipo" colspan="1">Tipo de herramienta</td>
          <td class="td-table">Acciones</td>
        </thead>
      </table>
    </div>


</div> {{-- Fin Container --}}

<script type="text/javascript" src="js/tool_type/show.js"></script>
<script type="text/javascript" src="js/tool_type/add.js"></script>
<script type="text/javascript" src="js/tool_type/delete.js"></script>
<script type="text/javascript" src="js/tool_type/edit.js"></script>

 @endsection