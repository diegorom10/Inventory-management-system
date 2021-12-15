$(document).ready(function(){



    var tablatipo = $('#tabla-tipo').DataTable({

    processing:true,
    serverSide:true,
    ajax:{
        url: "/tipo",
    },

    columns:[
        {data: 'tipo',
        className: 'dt-body-center',
        name: 'hola'
        },

        {data: 'action', orderable:false,
        className: 'dt-body-center-acciones'}
    ],

    responsive: true,
    autoWidth: false,
    "language": {
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

    },

    createdRow: function(row, data, dataIndex){
            $('.dt-body-center1', row).attr('id', "hola");
            $('#hola', row).attr('colspan', "2");
            
            //$('#tipo').attr('colspan', "2");
            //$('td:eq(0)', row).attr('colspan', "2");
           //$('td:eq(1)', row).css('display', 'none');
           //this.api().cell($('td:eq(0)', row)).data('N/A');
    }

    });

});
