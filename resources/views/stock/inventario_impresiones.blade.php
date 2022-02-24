@extends('layouts.app', [
'namePage' => 'Inventario Impresiones',
'class' => 'sidebar-mini',
'activePage' => 'inventario_impresiones',
])

@section('content')

<script src="http://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="resources/js/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<meta name="csrf-token" content="{{csrf_token()}}">
<div class="panel-header panel-header-sm">
</div>
<input type="hidden" name="" id="usuario" value="{{ old('name', auth()->user()->name) }}">
<div class="content">
    <div class="row">
        <div class="col-md-12" style="display:inline-block;float:left;">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Inventario Impresiones</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-danger" id="alert_orden" style="display:none; ">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_alert_orden'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Error - </b>
                            <p id="texto_noti"></p>
                        </span>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Fecha Desde:</label>
                        <input type="date" id="fecha_desde" name="" value="" class='form-control'>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Fecha Hasta:</label>
                        <input type="date" id="fecha_hasta" name="" value="" class='form-control'>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Tipo:</label>
                        <select id="estado" name="" value="" class='form-control'>
                            <option value="Materiales">Inventario Materiales</option>
                            <option value="Lotes">Inventario Lotes</option>
                            <option value="Alta">Alta</option>
                            <option value="Baja">Baja</option>
                            <option value="Desperdicio">Desperdicio</option>
                        </select>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <button id="buscar_orden" class="btn btn-primary btn-round">Buscar</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id='tabla_impresion'>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
//----------------------------CERRAR NOTIFICACION  ---------------------------------//
$('#cerrar_alert_orden').on('click', function() {
    document.getElementById('alert_orden').style.display = 'none';
});
//---------------------------- IMPRIMIR ORDEN  ---------------------------------//

$('#tabla_impresion ').on('click', '#imprimir', function() {

    var fecha = $(this).closest('tr').find('td').eq(0).text();
    var tipo = $('#estado').val();

    if (tipo == 'Lotes') {
        var url = '{{ route("imprimir_inventario_lotes", ":id") }}';
        url = url.replace(':id', fecha);
        window.open(url, '_blank');
    } else if (tipo == 'Materiales') {
        var url = '{{ route("imprimir_inventario_materiales", ":id") }}';
        url = url.replace(':id', fecha);
        window.open(url, '_blank');
    } else {
        var id = $(this).closest('tr').find('td').eq(0).text();
        var url = '{{ route("imprimir_inventario_movimiento", ":id") }}';
        url = url.replace(':id', id);
        window.open(url, '_blank');

    }

});

//---------------------------- BUSCAR ORDEN  ---------------------------------//

$('#buscar_orden').on('click', function() {
    var fecha_inicial = $('#fecha_desde').val();
    var fecha_fin = $('#fecha_hasta').val();
    var estado = $('#estado').val();


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('buscar_inventario_impresiones')}}",
        data: {
            fecha_inicio: fecha_inicial,
            fecha_fin: fecha_fin,
            estado: estado
        },
        datatype: 'json',
        success: function(r) {
            var array = r;
            if (array.code == 1) {
                document.getElementById('alert_orden').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            } else {
                var nuevaFila = "";
                var data_length = array.length;
                // alert(data_length);

                nuevaFila += " <thead class=' text-primary'><tr>";
                if (estado == 'Alta' || estado == 'Baja') {
                    nuevaFila += "<th>Id</th>";
                }
                nuevaFila += " <th>Fecha</th>";
                nuevaFila += "<th>Usuario</th>";
                nuevaFila += "<th>Herramientas</th>";
                
                nuevaFila += " </tr></thead>";

                nuevaFila += "<tbody>";

                for (var i = 0; i < data_length; i++) {

                    var fecha = array[i].fecha;
                    var usuario = array[i].user;
                    //alert(fecha);
                    let current_datetime = new Date(fecha);
                    // alert(current_datetime);
                    let formatted_date = current_datetime.getFullYear() + "-" + (current_datetime
                        .getMonth() + 1) + "-" + current_datetime.getDate()
                    //alert(formatted_date);
                    nuevaFila += "<tr>";
                    if (estado == 'Alta' || estado == 'Baja') {
                        nuevaFila += "<td>"+array[i].id+"</td>";
                    }
                    nuevaFila += "<td>" + formatted_date + " </td>";
                    nuevaFila += "<td>" + usuario + " </td>";
                    nuevaFila +=
                        ' <td><a id="imprimir"><i class="now-ui-icons files_paper"></i> </a></td>';
                    
                    nuevaFila += "</tr>";


                }
                nuevaFila += "</tbody>";
                $("#tabla_impresion").empty().append(nuevaFila);
            }
        }
    });
    return false;

});
</script>
@endsection