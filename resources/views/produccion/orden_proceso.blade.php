@extends('layouts.app', [
'namePage' => 'Ordenes en Proceso',
'class' => 'sidebar-mini',
'activePage' => 'orden_proceso',
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
                    <h4 class="card-title"> Ordenes en Proceso</h4>
                </div>

                <div class="card-body">

                    <div class="alert alert-success" id="notificacion_orden" style="display:none;">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_noti_orden'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Guardado - </b> Orden de Produccion Finalizada</span>
                    </div>
                    <div class="alert alert-danger" id="orden_anulada" style="display:none; ">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_orden_anulada'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Anulada - </b> Orden de Produccion Anulada</span>
                    </div>
                    <div class="alert alert-danger" id="alert_orden" style="display:none; ">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_alert_orden'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Error - </b>
                            <p id="texto_noti"></p>
                        </span>
                    </div>
                    <div class="col-md-3">
                        <label for="">Buscar Orden:</label>
                        <input type="" id="search_orden" name="" value="" class='form-control'>
                        <!--button id="buscar_orden" style="float:right" class="btn btn-primary btn-round">Buscar</button-->
                    </div>
                    <div class="table-responsive">
                        <table class="table" id='tabla_orden_temp'>
                            <thead class=" text-primary">
                                <th>
                                    Nro. Orden
                                </th>
                                <th>
                                    Fecha
                                </th>
                                <th>
                                    Usuario
                                </th>
                                <th>
                                    Estado
                                </th>
                                <th>
                                    Herramientas
                                </th>
                            </thead>
                            <tbody>
                                @foreach($listado as $lista)
                                <tr>
                                    <td>{{$lista->id_orden}}</td>
                                    <td>{{$lista->fecha_inicio}}</td>
                                    <td>{{$lista->usuario}}</td>
                                    <td>{{$lista->estado}}</td>
                                    <td>
                                        @if($lista->estado == "PROCESO")
                                            <a id='lotear' data-toggle="modal" data-target="#terminarOrdenModal"><i
                                                class='now-ui-icons files_box'></i> </a>
                                            <a id='eliminar'><i class='now-ui-icons ui-1_simple-remove'></i> </a>
                                        @endif                                        
                                        <a id='imprimir'><i class='now-ui-icons files_paper'></i> </a>
                                        <a id='terminar' data-toggle="modal" data-target="#terminarOrdenModalEstado"><i class='now-ui-icons ui-1_check'></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!---------------------------- MODAL TERMINAR ORDEN ---------------------------------//!-->
<div class="modal fade" id="terminarOrdenModal" tabindex="-1" role="dialog" aria-labelledby="terminarOrdenModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Lotear</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                        <div id="fecha_seleccion" style="display:block;">
                            <tr>
                                <input type="hidden" id="orden" name="">
                                <td>Fecha:</td>
                                <td><input type="date" id="fecha_fin" value="" class="form-control"></td>
                            </tr>
                        </div>
                        <div id="mensaje_espera" style="display:none;">
                            <p style="color:green">Generando Lotes. Favor Aguarde...</p>
                        </div>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelar_orden">Cerrar</button>
                <button type="button" class="btn btn-primary" id="terminar_orden">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!---------------------------- MODAL TERMINAR ORDEN ESTADO---------------------------------//!-->
<div class="modal fade" id="terminarOrdenModalEstado" tabindex="-1" role="dialog" aria-labelledby="terminarOrdenModalEstado"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Terminar Orden</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                        <div id="fecha_seleccion" style="display:block;">
                            <tr>
                                <input type="hidden" id="orden_estado" name="">
                                <td>Fecha:</td>
                                <td><input type="date" id="fecha_fin_estado" value="" class="form-control"></td>
                            </tr>
                        </div>
                        <div id="mensaje_espera_terminar" style="display:none;">
                            <p style="color:green">Terminando Orden. Favor Aguarde...</p>
                        </div>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelar_orden">Cerrar</button>
                <button type="button" class="btn btn-primary" id="terminar_orden_estado">Guardar</button>
            </div>
        </div>
    </div>
</div>
<script>
//----------------------------ELIMINAR PRODUCTOS DEL LISTADO TEMPORAL---------------------------------//

$('#tabla_orden_temp ').on('click', '#eliminar', function() {
    $(this).closest('tr').remove();
    $("#tabla_detalle_temp").empty();

    var ids = $(this).closest('tr').find('td').eq(0).text();
    var user = $('#usuario').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('anular_orden')}}",
        data: {
            id: ids,
            usuario: user
        },
        datatype: 'json',
        success: function(r) {

            var array = r;

            if (array.code == 0) {
                document.getElementById('orden_anulada').style.display = 'block';

            } else {
                document.getElementById('alert_orden').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            }
        }
    });
    return false;
});


//----------------------------CERRAR NOTIFICACION  ---------------------------------//

$('#cerrar_orden_anulada').on('click', function() {
    document.getElementById('orden_anulada').style.display = 'none';
});
$('#cerrar_noti_orden').on('click', function() {
    document.getElementById('notificacion_orden').style.display = 'none';
});
$('#cerrar_alert_orden').on('click', function() {
    document.getElementById('alert_orden').style.display = 'none';
});

//---------------------------- TERMINAR ORDEN  ---------------------------------//

$('#tabla_orden_temp ').on('click', '#lotear', function() {

    var id = $(this).closest('tr').find('td').eq(0).text();
    $('#orden').val(id);
    var id = $(this).closest('tr').data('id');
    $('#terminarOrdenModal').data('orden', id);

});

$('#terminar_orden').on('click', function() {
    document.getElementById('terminar_orden').disabled = true;
    document.getElementById('cancelar_orden').disabled = true;
    document.getElementById('mensaje_espera').style.display = 'block';
    
    var fechas = $('#fecha_fin').val();
    var ids = $('#orden').val();
    var user = $('#usuario').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('terminar_orden')}}",
        data: {
            id: ids,
            fecha: fechas,
            usuario: user
        },
        datatype: 'json',
        success: function(r) {

            $('#terminarOrdenModal').modal('hide');
            var array = r;

            if (array.code == 0) {
                document.getElementById('notificacion_orden').style.display = 'block';
                var url = '{{ route("imprimir_etiquetas", ":id") }}';
                url = url.replace(':id', ids);
                window.open(url, '_blank');
                // url = '{{ route("imprimir_etiquetas", ":id") }}';
                // url = url.replace(':id', ids);
                // window.open(url, '_blank');
                location.reload();
            } else {
                document.getElementById('alert_orden').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            }
        }
    });
    return false;
});

//---------------------------- IMPRIMIR ORDEN  ---------------------------------//

$('#tabla_orden_temp ').on('click', '#imprimir', function() {

    var id = $(this).closest('tr').find('td').eq(0).text();

    var url = '{{ route("orden_print", ":id") }}';
    url = url.replace(':id', id);
    window.open(url, '_blank');

});

//---------------------------- BUSCAR ORDEN  ---------------------------------//

$('#search_orden').keypress(function(e) {
    if (e.which == 13) {
        event.preventDefault();
        var ids = $('#search_orden').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "{{route('buscar_orden')}}",
            data: {
                id: ids,
            },
            datatype: 'json',
            success: function(r) {
                var array = r;
                var nuevaFila = "";

                var data_length = array.length;

                nuevaFila += " <thead class=' text-primary'><tr>";
                nuevaFila += " <th>Nro. Orden</th>";
                nuevaFila += " <th>Fecha</th>";
                nuevaFila += "<th>Usuario</th>";
                nuevaFila += "<th>Herramientas</th>";
                nuevaFila += " </tr></thead>";

                for (var i = 0; i < data_length; i++) {

                    var id = array[i].id_orden;
                    var fecha = array[i].fecha_inicio;
                    var usuario = array[i].usuario;

                    nuevaFila += "<tbody><tr>";
                    nuevaFila += "<td>" + id + " </td>";
                    nuevaFila += "<td>" + fecha + " </td>";
                    nuevaFila += "<td><center>" + usuario + "</center> </td>";
                    nuevaFila +=
                        '<td><a id="terminar" data-toggle="modal" data-target="#terminarOrdenModal"><i class="now-ui-icons ui-1_check"></i> </a>' +
                        ' <a id="eliminar"><i class="now-ui-icons ui-1_simple-remove"></i> </a>' +
                        ' <a id="imprimir"><i class="now-ui-icons files_paper"></i> </a></td>';
                    nuevaFila += "</tr></tbody>";

                    $("#tabla_orden_temp").empty().append(nuevaFila);
                }

            }
        });
        return false;
    }
});

//---------------------------- TERMINAR ORDEN ESTADO ---------------------------------//

$('#tabla_orden_temp ').on('click', '#terminar', function() {

var id = $(this).closest('tr').find('td').eq(0).text();
$('#orden_estado').val(id);
var id = $(this).closest('tr').data('id');
$('#terminarOrdenEstadoModal').data('orden_estado', id);

});

$('#terminar_orden_estado').on('click', function() {
 document.getElementById('terminar_orden_estado').disabled = true;
// document.getElementById('cancelar_orden').disabled = true;
 document.getElementById('mensaje_espera_terminar').style.display = 'block';

var fechas = $('#fecha_fin_estado').val();
var ids = $('#orden_estado').val();
var user = $('#usuario').val();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
    type: "POST",
    url: "{{route('terminar_orden_estado')}}",
    data: {
        id: ids,
        fecha: fechas,
        usuario: user
    },
    datatype: 'json',
    success: function(r) {

        $('#terminarOrdenModalEstado').modal('hide');
        var array = r;

        if (array.code == 0) {
            document.getElementById('notificacion_orden').style.display = 'block';
            //location.reload();
        } else {
            document.getElementById('alert_orden').style.display = 'block';
            document.getElementById('texto_noti').innerHTML = array.msg;
        }
    }
});
return false;
});
</script>
@endsection