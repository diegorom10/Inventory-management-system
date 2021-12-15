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
      <!-- <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" id="btn_prestamo">Prestamo de material</button> -->
      <!-- <button style="border: solid #000 1px" type="button" class="btn2 col-md-5 btn btn-warning btn-lg m-2" id="btn_regreso">Regreso de material</button> -->
      <!-- <a data-toggle="modal" href="#regresoModal" class="btn btn-primary">Launch modal</a> -->
      <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-toggle="modal" data-target="#prestamoModal">Préstamo de Material</button>
      <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-toggle="modal" data-target="#regresoModal">Regreso de Material</button>
    </div>
    <div class="column">
      <!-- <button style="border: solid #000 1px" type="button" class="btn3 col-md-5 btn btn-warning btn-lg m-2" id="btn_llegada">Llegada de material</button> -->
      <!-- <button style="border: solid #000 1px" type="button" class="btn4 col-md-5 btn btn-warning btn-lg m-2" id="btn_retirada">Retirada de material</button> -->
      <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-toggle="modal" data-target="#llegadaModal">Llegada de Material</button>
      <button style="border: solid #000 1px" type="button" class="btn1 col-md-5 btn btn-warning btn-lg m-2" data-toggle="modal" data-target="#retiradaModal">Retiro de Material</button>
    </div>
  </div>
  <!--hacer_movimiento-->


  <div class="contenedor" style="margin-bottom: 25px">
    <table id="tabla-inventario" class="cell-border hover table table-dark">
      <thead>
        <td class="td-table">Descripcion</td>
        <td class="td-table">Cantidad original</td>
        <td class="td-table">Cantidad disponible</td>
        <td class="td-table">Cantidad comprometida</td>
      </thead>
    </table>
  </div>


    <!-- EJEMPLO DE MODAL 2 -->
    <div class="modal fade" id="prestamoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    <!-- EJEMPLO MODAL 2 -->
    <div class="modal fade" id="regresoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>


    <!-- EJEMPLO MODAL 3 -->
    <div class="modal fade" id="llegadaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>


    <!-- EJEMPLO MODAL 4 -->
    <div class="modal fade" id="retiradaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal PRESTAMO DE MATERIAL -->
    <!-- <div class="modal fade" id="prestamo_modal" data-backdrop="static" tabindex="-1" role="dialog"
      aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Hacer un prestamo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="prestamo_form" class="m-4">
            <div class="form-row">
              <select name='herramientas[]' multiple size=6>
                <option value='english'>Herramienta 1</option>
                <option value='maths'>Herramienta 2</option>
                <option value='computer'>Herramienta 3</option>
                <option value='physics'>Herramienta 4</option>
                <option value='chemistry'>Herramienta 5</option>
                <option value='hindi'>Herramienta 6</option>
              </select>
            </div>
            <div class="form-group">
              <label for="inputAddress">Address</label>
              <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
            </div>
            <div class="form-group">
              <label for="inputAddress2">Address 2</label>
              <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputCity">City</label>
                <input type="text" class="form-control" id="inputCity">
              </div>
              <div class="form-group col-md-4">
                <label for="inputState">State</label>
                <select id="inputState" class="form-control">
                  <option selected>Choose...</option>
                  <option>...</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="inputZip">Zip</label>
                <input type="text" class="form-control" id="inputZip">
              </div>
            </div>
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="gridCheck">
                <label class="form-check-label" for="gridCheck">
                  Check me out
                </label>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
          </form>.prestamo-form -->
        <!-- </div>
      </div> -->
      <!--modal prestamo-->


    


</div><!-- .container -->




        <script type="text/javascript" src="js/inventario/show.js"></script>
        <script type="text/javascript" src="js/catalogo/add.js"></script>
        <script type="text/javascript" src="js/catalogo/delete.js"></script>
        <script type="text/javascript" src="js/catalogo/edit.js"></script>
        <script type="text/javascript" src="js/catalogo/update.js"></script>
        <script>
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
        </script>

        <script>
          $(document).on('click','#btn_prestamo', function(){
          $('#prestamo_modal').modal('toggle');
        }); 
        </script>

        @endsection