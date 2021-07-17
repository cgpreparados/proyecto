@extends('layouts.app', [
'namePage' => 'Proveedores',
'class' => 'sidebar-mini',
'activePage' => 'proveedores',
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
                        <span><b> Guardado - </b> <p id="texto_noti"></p></span>
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
                    <h4 class="card-title"> Proveedores</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <button class="btn btn-default btn-round" data-toggle="modal" data-target="#modalNuevo"><i
                                class='now-ui-icons ui-1_simple-add'></i> Nuevo </button>
                        <table class="table" id='tabla_proveedor'>
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
                                @foreach ($proveedores as $pro)
                                <tr id='ver_detalle'>
                                    <td>{{$pro->id_proveedor}}</td>
                                    <td>{{$pro-> nombre_proveedor}}</td>
                                    <td>{{$pro->ruc_proveedor}}</td>
                                    <td>{{$pro->direccion_proveedor}}</td>
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
<!----------------------------MODAL NUEVO PROVEEDOR---------------------------------->
<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Nuevo Proveedor</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 80%;">
                    <tr>
                        <td><label>Razón Social:</label></td>
                        <td><input type="text" name="" id="new_desc_proveedor"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>RUC:</label></td>
                        <td><input type="text" name="" id="new_ruc_proveedor"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Dirección:</label></td>
                        <td><input type="text" name="" id="new_direccion_proveedor"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_proveedor">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!----------------------------MODAL EDITAR PROVEEDOR---------------------------------->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Editar Proveedor</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 80%;">
                    <tr style='display:none;'>
                        <td><label>Código:</label></td>
                        <td><input type="text" name="" id="codigo_proveedor"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Razon Social:</label></td>
                        <td><input type="text" name="" id="nombre_proveedor"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>RUC:</label></td>
                        <td><input type="text" name="" id="ruc_proveedor"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Direccion:</label></td>
                        <td><input type="text" name="" id="direccion_proveedor"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_edicion_proveedor">Guardar</button>
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
$('#tabla_proveedor').on('click', '#editar', function(){

  var codigo     =  $(this).closest('tr').find('td').eq(0).text();
  var nombre     =  $(this).closest('tr').find('td').eq(1).text();
  var ruc        =  $(this).closest('tr').find('td').eq(2).text();
  var direccion  =  $(this).closest('tr').find('td').eq(3).text();
 
  $('#codigo_proveedor').val(codigo);
  $('#nombre_proveedor').val(nombre);
  $('#ruc_proveedor').val(ruc);
  $('#direccion_proveedor').val(direccion);

});


//----------------------------GUARDAR NUEVO proveedor---------------------------------//

$('#guardar_proveedor').on('click', function() {

    var nombre = $('#new_desc_proveedor').val();
    var ruc = $('#new_ruc_proveedor').val();
    var direccion = $('#new_direccion_proveedor').val();

    //nombre = cambiarNombre(nombre);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('guardar_proveedor')}}",
        data: {
            nombre_proveedor: nombre,
            ruc_proveedor: ruc,
            direccion_proveedor: direccion
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

//----------------------------GUARDAR EDICION proveedor---------------------------------//

$('#guardar_edicion_proveedor').on('click', function() {

var codigo = $('#codigo_proveedor').val();
var nombre = $('#nombre_proveedor').val();
var ruc = $('#ruc_proveedor').val();
var direccion = $('#direccion_proveedor').val();



$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
    type: "POST",
    url: "{{route('guardar_edicion_proveedor')}}",
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

//----------------------------ELIMINAR proveedor---------------------------------//
$('#tabla_proveedor').on('click', '#eliminar', function(){

var codigo     =  $(this).closest('tr').find('td').eq(0).text();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
    type: "POST",
    url: "{{route('eliminar_proveedor')}}",
    data: {
        codigo: codigo
    },
    success: function(r) {
        var array = r;
        if (array.code == 0) {
            document.getElementById('notificacion_resultado').style.display = 'block';
            document.getElementById('texto_noti').innerHTML = array.msg;
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