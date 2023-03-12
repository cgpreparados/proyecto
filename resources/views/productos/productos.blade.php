@extends('layouts.app', [
'namePage' => 'Productos',
'class' => 'sidebar-mini',
'activePage' => 'productos',
])

@section('content')

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
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
                        <span><b> Guardado - </b> Producto creado con exito</span>
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
                    <h4 class="card-title"> Productos</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <button class="btn btn-default btn-round" data-toggle="modal" data-target="#modalNuevo"><i
                                class='now-ui-icons ui-1_simple-add'></i> Nuevo </button>
                        <table class="table" id='tabla_productos'>
                            <thead class=" text-primary">
                                <th>
                                    Codigo
                                </th>
                                <th>
                                    Descripcion
                                </th>
                                <th>
                                    Unidad
                                </th>
                                <th>
                                    Dias Vencimiento
                                </th>
                                <th>
                                    Tipo Material
                                </th>
                                <th>
                                    Herr.
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($materiales as $material)

                                @if($material->activo == 0)
                                <tr id='ver_detalle' style="color:red">
                                    <td>{{$material->cod_material}}</td>
                                    <td>{{$material->desc_material}}</td>
                                    <td>{{$material->unidad_material}}</td>
                                    <td>{{$material->dias_vencimiento}}</td>
                                    @if($material->tipo_material == 1)
                                    <td>Materia Prima</td>
                                    @elseif($material->tipo_material == 2)
                                    <td>Producto Base</td>
                                    @elseif($material->tipo_material == 3)
                                    <td>Producto Terminado</td>
                                    @endif
                                    <td>
                                        <a id='eliminar'><i class='now-ui-icons ui-1_simple-remove'></i> </a>
                                        <a id='habilitar'><i class='now-ui-icons ui-1_check'></i> </a>
                                        <a id='editar'><i class='now-ui-icons gestures_tap-01'></i> </a>
                                    </td>
                                </tr>
                                @else
                                <tr id='ver_detalle'>
                                    <td>{{$material->cod_material}}</td>
                                    <td>{{$material->desc_material}}</td>
                                    <td>{{$material->unidad_material}}</td>
                                    <td>{{$material->dias_vencimiento}}</td>
                                    @if($material->tipo_material == 1)
                                    <td>Materia Prima</td>
                                    @elseif($material->tipo_material == 2)
                                    <td>Producto Base</td>
                                    @elseif($material->tipo_material == 3)
                                    <td>Producto Terminado</td>
                                    @endif
                                    
                                    <td>
                                        <a id='eliminar'><i class='now-ui-icons ui-1_simple-remove'></i> </a>
                                        <a id='habilitar'><i class='now-ui-icons ui-1_check'></i> </a>
                                        <a id='editar' data-toggle="modal" data-target="#modalEditar"><i class='now-ui-icons gestures_tap-01'></i> </a>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------------------------MODAL NUEVO PRODUCTO---------------------------------->
<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Nuevo Producto</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 80%;">
                    <tr>
                        <td><label>Código:</label></td>
                        <td><input type="text" name="" id="new_codigo_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Descripción:</label></td>
                        <td><input type="text" name="" id="new_desc_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Unidad de Medida:</label></td>
                        <td><input type="text" name="" id="new_unidad_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Dias de Vencimiento:</label></td>
                        <td><input type="text" name="" id="new_dias_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Tipo:</label></td>
                        <td><select id="new_tipo_material"
                                style="height: 32px;width: 100%; background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444; line-height: 22px;">
                                <option value="1">Materia Prima</option>
                                <option value="2">Producto Base</option>
                                <option value="3">Producto Terminado</option>
                            </select></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_producto">Guardar</button>
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
                        <td><input type="text" name="" id="codigo_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Descripción:</label></td>
                        <td><input type="text" name="" id="desc_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Unidad de Medida:</label></td>
                        <td><input type="text" name="" id="unidad_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Dias de Vencimiento:</label></td>
                        <td><input type="text" name="" id="dias_material"
                                style="background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444;line-height: 22px;height: 32px;width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Tipo:</label></td>
                        <td><select id="tipo_material"
                                style="height: 32px;width: 100%; background-color: #ffff;border: 1px solid #aaa;border-radius: 4px;outline: 0;color: #444; line-height: 22px;">
                                <option value="1">Materia Prima</option>
                                <option value="2">Producto Base</option>
                                <option value="3">Producto Terminado</option>
                            </select></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_edicion_producto">Guardar</button>
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
$('#tabla_productos').on('click', '#editar', function(){

  var codigo =  $(this).closest('tr').find('td').eq(0).text();
  var nombre =  $(this).closest('tr').find('td').eq(1).text();
  var unidad =  $(this).closest('tr').find('td').eq(2).text();
  var dias   =  $(this).closest('tr').find('td').eq(3).text();
  var tipo   =  $(this).closest('tr').find('td').eq(4).text();

  if(tipo == "Producto Base"){
      tipo = 2;
  }else if(tipo == "Producto Terminado"){
      tipo = 3;
  }else if(tipo == "Materia Prima"){
      tipo = 1;
  }
  $('#codigo_material').val(codigo);
  $('#codigo_material_stay').val(codigo);
  $('#desc_material').val(nombre);
  $('#unidad_material').val(unidad);
  $('#dias_material').val(dias);
  $('#tipo_material').val(tipo);

});
//----------------------------ELIMINAR PRODUCTOS ---------------------------------//

$('#tabla_productos').on('click', '#eliminar', function() {

    var codigo = $(this).closest('tr').find('td').eq(0).text();
    var fila = $(this).closest('tr');
   // alert(codigo);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('inhabilitar_productos')}}",
        data: {
            codigo: codigo
        },
        success: function(r) {
            var array = r;
            if (array.code == 0) {
                fila.css('color', 'Red');
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = 'Producto inhabilitado';
            } else {
                document.getElementById('notificacion_error').style.display = 'block';
                document.getElementById('texto_error').innerHTML = 'array.msg';
            }


        }
    });

});

//----------------------------HABILITAR PRODUCTOS ---------------------------------//

$('#tabla_productos').on('click', '#habilitar', function() {

    var codigo = $(this).closest('tr').find('td').eq(0).text();
    var fila = $(this).closest('tr');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('habilitar_productos')}}",
        data: {
            codigo: codigo
        },
        success: function(r) {
            var array = r;
            if (array.code == 0) {
                fila.css('color', 'black');
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = 'Producto habilitado';
            } else {
                document.getElementById('notificacion_error').style.display = 'block';
                document.getElementById('texto_error').innerHTML = 'array.msg';
            }


        }
    });

});

//----------------------------GUARDAR NUEVO MATERIAL---------------------------------//

$('#guardar_producto').on('click', function() {

    var codigo = $('#new_codigo_material').val();
    var nombre = $('#new_desc_material').val();
    var unidad = $('#new_unidad_material').val();
    var dias = $('#new_dias_material').val();
    var tipo = $('#new_tipo_material').val();

    //nombre = cambiarNombre(nombre);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('guardar_productos')}}",
        data: {
            codigo: codigo,
            nombre: nombre,
            unidad: unidad,
            dias: dias,
            tipo: tipo
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

//----------------------------GUARDAR EDICION MATERIAL---------------------------------//

$('#guardar_edicion_producto').on('click', function() {

var codigo = $('#codigo_material').val();
var nombre = $('#desc_material').val();
var unidad = $('#unidad_material').val();
var dias = $('#dias_material').val();
var tipo = $('#tipo_material').val();

//nombre = cambiarNombre(nombre);

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
    type: "POST",
    url: "{{route('guardar_edicion_productos')}}",
    data: {
        codigo: codigo,
        nombre: nombre,
        unidad: unidad,
        dias: dias,
        tipo: tipo
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