@extends('layouts.app', [
'namePage' => 'Clientes',
'class' => 'sidebar-mini',
'activePage' => 'clientes',
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
                    <div class="alert alert-success" id="notificacion_resultado" style="display:none">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_noti_orden'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Guardado - </b> Cliente agregado con exito</span>
                    </div>
                    <div class="alert alert-warning" id="notificacion_varias" style="display:none">
                        <button type="button" aria-hidden="true" class="close">
                            <i class="now-ui-icons ui-1_simple-remove" id='cerrar_noti_varias'></i>
                        </button>
                        <span><b> Alerta - </b>
                            <p id="texto_noti"></p>
                        </span>
                    </div>
                    <div class="alert alert-danger" id="notificacion_error" style="display:none">
                        <button type="button" aria-hidden="true" class="close">
                            <i class="now-ui-icons ui-1_simple-remove" id='cerrar_noti_error'></i>
                        </button>
                        <span><b> Error - </b>
                            <p id="texto_error"></p>
                        </span>
                    </div>
                    <h4 class="card-title"> Clientes</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <button class="btn btn-default btn-round" data-toggle="modal" data-target="#modalNuevo"><i
                                class='now-ui-icons ui-1_simple-add'></i> Nuevo </button>
                        <table class="table" id='tabla_clientes'>
                            <thead class=" text-primary">
                                <th>
                                    Codigo
                                </th>
                                <th>
                                    Descripcion
                                </th>
                                <th>
                                    Ruc
                                </th>
                                <th>
                                    Dirección
                                </th>
                                <th>
                                    Herr.
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($clientes as $cli)
                                <tr id='ver_detalle'>
                                    <td>{{$cli->id_cliente}}</td>
                                    <td>{{$cli-> nombre_cliente}}</td>
                                    <td>{{$cli->ruc_cliente}}</td>
                                    <td>{{$cli->direccion_cliente}}</td>
                                    <td>
                                        <a id='eliminar'><i class='now-ui-icons ui-1_simple-remove'></i> </a>
                                        <a id='editar' data-toggle="modal" data-target="#modalEditar"><i class='now-ui-icons gestures_tap-01'></i> </a>
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
<!----------------------------MODAL NUEVO CLIENTE---------------------------------->
<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Nuevo Cliente</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 80%;">
                    <tr>
                        <td><label>Razón Social:</label></td>
                        <td><input type="text" name="" id="new_desc_cliente"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>RUC:</label></td>
                        <td><input type="text" name="" id="new_ruc_cliente"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Dirección:</label></td>
                        <td><input type="text" name="" id="new_direccion_cliente"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_cliente">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!----------------------------MODAL EDITAR PRODUCTO---------------------------------->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Editar Producto</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 80%;">
                    <tr>
                        <td><label>Código:</label></td>
                        <td><input type="text" name="" id="codigo_cliente"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Descripción:</label></td>
                        <td><input type="text" name="" id="nombre_cliente"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Unidad de Medida:</label></td>
                        <td><input type="text" name="" id="ruc_cliente"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Dias de Vencimiento:</label></td>
                        <td><input type="text" name="" id="direccion_cliente"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_edicion_cliente">Guardar</button>
            </div>
        </div>
    </div>
</div>
<script>
//----------------------------FUNCION PARA TEXTO---------------------------------//

function cambiarNombre(nombre) {
    let regex = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;
    return regex.exec(nombre)[0];

}
//----------------------------SET DATOS EDICION---------------------------------//
$('#tabla_clientes').on('click', '#editar', function(){

  var codigo     =  $(this).closest('tr').find('td').eq(0).text();
  var nombre     =  $(this).closest('tr').find('td').eq(1).text();
  var ruc        =  $(this).closest('tr').find('td').eq(2).text();
  var direccion  =  $(this).closest('tr').find('td').eq(3).text();
 
  $('#codigo_cliente').val(codigo);
  $('#nombre_cliente').val(nombre);
  $('#ruc_cliente').val(ruc);
  $('#direccion').val(direccion);

});


//----------------------------GUARDAR NUEVO CLIENTE---------------------------------//

$('#guardar_cliente').on('click', function() {

    var nombre = $('#new_desc_cliente').val();
    var ruc = $('#new_ruc_cliente').val();
    var direccion = $('#new_direccion_cliente').val();

    //nombre = cambiarNombre(nombre);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('guardar_cliente')}}",
        data: {
            nombre_cliente: nombre,
            ruc_cliente: ruc,
            direccion_cliente: direccion
        },
        success: function(r) {
            var array = r;
            if (array.code == 0) {
                $('#modalNuevo').modal('hide');
                document.getElementById('notificacion_resultado').style.display = 'block';
                // location.reload();
            } else {
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            }


        }
    });

});

//----------------------------GUARDAR EDICION CLIENTE---------------------------------//

$('#guardar_edicion_cliente').on('click', function() {

var codigo = $('#codigo_cliente').val();
var nombre = $('#nombre_cliente').val();
var ruc = $('#ruc_cliente').val();
var direccion = $('#direccion_cliente').val();



$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
    type: "POST",
    url: "{{route('guardar_edicion_cliente')}}",
    data: {
        codigo: codigo,
        nombre: nombre,
        ruc: ruc,
        direccion: direccion
    },
    success: function(r) {
        var array = r;
        if (array.code == 0) {
            $('#modalEditar').modal('hide');
            //document.getElementById('notificacion_resultado').style.display = 'block';
            location.reload();
        } else {
            document.getElementById('notificacion_varias').style.display = 'block';
            document.getElementById('texto_noti').innerHTML = array.msg;
        }


    }
});

});



//----------------------------CERRAR NOTIFICACION  ---------------------------------//

$('#cerrar_noti_orden').on('click', function() {
    document.getElementById('notificacion_orden').style.display = 'none';
});
$('#cerrar_noti_varias').on('click', function() {
    document.getElementById('notificacion_varias').style.display = 'none';
});
$('#cerrar_noti_error').on('click', function() {
    document.getElementById('notificacion_error').style.display = 'none';
});


</script>
@endsection