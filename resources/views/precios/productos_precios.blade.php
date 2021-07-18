@extends('layouts.app', [
'namePage' => 'Precios',
'class' => 'sidebar-mini',
'activePage' => 'precios',
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
                    <h4 class="card-title"> Precios</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <button class="btn btn-default btn-round" data-toggle="modal" data-target="#modalNuevo"><i
                                class='now-ui-icons ui-1_simple-add'></i> Nuevo </button>
                        <table class="table" id='tabla_precios'>
                            <thead class=" text-primary">
                                <th>
                                    Codigo
                                </th>
                                <th>
                                    Descripcion
                                </th>
                                <th>
                                    Precio
                                </th>
                                <th>
                                    Herr.
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($productos as $pro)
                                <tr id='ver_detalle'>
                                    <td>{{$pro->id_precio}}</td>
                                    <td>{{$pro-> desc_material}}</td>
                                    <td>{{$pro->precio_unitario}}</td>
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
<!----------------------------MODAL NUEVO PRECIO---------------------------------->
<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Agregar Producto</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 80%;">
                    <tr>
                        <td><label>Producto:</label></td>
                        <td><select id="cod_material" name="" value="" class='form-control'>
                            @foreach($materiales as $mat)
                            <option value="{{$mat->cod_material}}">{{$mat->desc_material}}</option>
                            @endforeach
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Precio:</label></td>
                        <td><input type="text" name="" id="new_precio"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_precio">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!----------------------------MODAL EDITAR PRECIO---------------------------------->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Editar Precio</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 80%;">
                    <tr style='display:none;'>
                        <td><label>Código:</label></td>
                        <td><input type="text" name="" id="edit_cod_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Descripcion:</label></td>
                        <td><input type="text" name="" id="edit_desc_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Precio:</label></td>
                        <td><input type="text" name="" id="edit_precio_unitario"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_edicion_precio">Guardar</button>
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
$('#tabla_precios').on('click', '#editar', function(){

  var codigo     =  $(this).closest('tr').find('td').eq(0).text();
  var nombre     =  $(this).closest('tr').find('td').eq(1).text();
  var precio        =  $(this).closest('tr').find('td').eq(2).text();
 
  $('#edit_cod_material').val(codigo);
  $('#edit_desc_material').val(nombre);
  $('#rdit_precio_unitario').val(precio);

});


//----------------------------GUARDAR NUEVO PRECIO---------------------------------//

$('#guardar_precio').on('click', function() {

    var codigo = $('#cod_material').val();
    var precio = $('#new_precio').val();

    //nombre = cambiarNombre(nombre);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('guardar_precio')}}",
        data: {
            cod_material: codigo,
            precio: precio
        },
        success: function(r) {
            var array = r; 
            if (array.code == 0) {
                $('#modalNuevo').modal('hide');
                document.getElementById('notificacion_resultado').style.display = 'block';
                 location.reload();
            } else {
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            }


        }
    });

});

//----------------------------GUARDAR EDICION PRECIO---------------------------------//

$('#guardar_edicion_precio').on('click', function() {

var codigo = $('#edit_cod_material').val();
var precio = $('#edit_precio_unitario').val();



$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
    type: "POST",
    url: "{{route('guardar_edicion_precio')}}",
    data: {
        codigo: codigo,
        precio: precio
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

//----------------------------ELIMINAR precio---------------------------------//
$('#tabla_precios').on('click', '#eliminar', function(){

var codigo     =  $(this).closest('tr').find('td').eq(0).text();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
    type: "POST",
    url: "{{route('eliminar_precio')}}",
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