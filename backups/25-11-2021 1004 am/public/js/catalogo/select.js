$(document).ready(function(){

  // Obtener listado de tipos al momento de hacer focus en select
  $("#selCategoria").focus(function(){
    var options = `<option disabled selected value> --- Selecciona una categoria --- </option>
                    <option value='null'>Sin categoria</option>`;
          $.ajax({
              url:   'catalogo/fetchCategorias',
              type:  'GET',
               success:  function (data) 
               {
                $('#selCategoria').html("");
                   data.forEach(tipo => {
                    options += `<option value='${tipo.id}'>${tipo.tipo}</option>`;
                   });
                  $('#selCategoria').html(options);         
               },
          })
        
  })


  $("#selCategoria2").focus(function(){
    var options = `<option disabled selected value> --- Selecciona una categoria --- </option>
                    <option value='null'>Sin categoria</option>`;
          $.ajax({
              url:   'catalogo/fetchCategorias',
              type:  'GET',
               success:  function (data) 
               {
                $('#selCategoria2').html("");
                   data.forEach(tipo => {
                    options += `<option value='${tipo.id}'>${tipo.tipo}</option>`;
                   });
                  $('#selCategoria2').html(options);         
               },
          })
        
  })
})