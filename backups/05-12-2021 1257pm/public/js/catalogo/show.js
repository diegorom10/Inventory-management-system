//si no funciona falta ruta local
$(document).ready(function(){

    var tablaCatalogo = $('#tabla-catalogo').DataTable({
    processing:true,
    order:  [0, "asc"], //ordenar por descripcion a-z
    serverSide:true,
    ajax:{
        url: "/catalogo",
    },
    columns:[
        {data: 'descripcion',
        className: 'dt-body-center'},
        {data: 'codigo',
        className: 'dt-body-center'},
        {data: 'numserie',
        className: 'dt-body-center'},
        {data: 'tipo',
        className: 'dt-body-center'},
        {data: 'action', orderable:false,
        className: 'dt-body-center'}
    ],
    responsive: true,
    autoWidth: false,
    "language": {
        searchPlaceholder: "Descripcion | código | serie",
        "lengthMenu": "Mostrar _MENU_ registros por página",
        "zeroRecords": "Nada encontrado",
        "info": "Mostrando la página _PAGE_ de _PAGES_",
        "infoEmpty": "Sin registros disponibles",
        "infoFiltered": "(filtrado de _MAX_ registros totales)",
        'search': 'Buscar',
        'paginate': {
            'next': 'Siguiente',
            'previous': 'Anterior',
        }
    }
    });

    
});
