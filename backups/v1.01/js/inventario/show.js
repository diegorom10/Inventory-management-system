$(document).ready(function(){
    var tablaCatalogo = $('#tabla-inventario').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url: "/inventario",
    },
    columns:[
        {data: 'descripcion',
        className: 'dt-body-center'},
        {data: 'Cantidad original',
        className: 'dt-body-center'},
        {data: 'Cantidad fisica',
        className: 'dt-body-center'},
        {data: 'Cantidad comprometida',
        className: 'dt-body-center'},

    ],
    responsive: true,
    autoWidth: false,
    "language": {
        searchPlaceholder: "nombre | código | serie",
        "lengthMenu": "Mostrar _MENU_ registros por página",
        "zeroRecords": "Nada encontrado - Disculpa",
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
