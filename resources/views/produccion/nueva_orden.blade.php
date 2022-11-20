@extends('layouts.app', [
'namePage' => 'Nueva Orden',
'class' => 'sidebar-mini',
'activePage' => 'nueva_orden',
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
        <div class="col-md-7" style="display:inline-block;float:left;">
            <div class="card">
                <div class="card-header" style="margin-bottom:0px;padding-top:0px;">
                    <div class="alert alert-success" id="notificacion_orden" style="display:none">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_noti_orden'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Guardado - </b> Orden de Produccion creada con exito</span>
                    </div>
                    <div class="alert alert-warning" id="notificacion_varias" style="display:none">
                        <button type="button" aria-hidden="true" class="close">
                            <i class="now-ui-icons ui-1_simple-remove" id='cerrar_noti_varias'></i>
                        </button>
                        <span><b> Alerta - </b>
                            <p id="texto_noti"></p>
                        </span>
                    </div>
                    <h4 class="card-title"> Nueva Orden</h4>
                    <form method="post" id="add_material_form">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <table cellpadding="5">
                                    <tr>
                                        <td><label for="fecha">Fecha:</label></td>
                                        <td><input type="date" id="fecha_orden" class='form-control'></td>
                                    </tr>
                                    <tr>
                                        <td><label for="Material">Material:</label></td>
                                        <td><select class="form-control select2" name="id_material" id="id_material">
                                                <option value="0">Seleccionar</option>
                                                @foreach($materiales as $material)
                                                <option value="{{$material->cod_material}}">{{$material->desc_material}}
                                                </option>
                                                @endforeach
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><label for="cantidad">Cantidad:<label></td>
                                        <td><input type="numeric" id="cantidad" class="form-control" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                    </form>
                </div>
                <div class="card-body" style="margin-top:0px; padding-top:0px;">
                    <div class="table-responsive">
                        <table class="table" id='tabla_orden_temp'>
                            <thead class=" text-primary">
                                <th>
                                    Codigo
                                </th>
                                <th>
                                    Descripcion
                                </th>
                                <th>
                                    Cantidad
                                </th>
                                <th>
                                    Unidad
                                </th>
                                <th>
                                    Herramientas
                                </th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div style='float:right;'>
                        <button id=guardar_orden class="btn btn-primary btn-round">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5" style="display:inline-block;float:left;">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Detalle</h4>
                    <h5 class=" text-primary" id="codigo_material_temp"></h5>
                    <h5 class=" text-primary" id="nombre_material_temp"></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id='tabla_detalle_temp'>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
//----------------------------AGREGAR PRODUCTOS AL LISTADO TEMPORAL---------------------------------//

$('#cantidad').keypress(function(e) {
    if (e.which == 13) {
        event.preventDefault();
        var datos = $('#id_material').val();
        var form = $("#add_material_form").serialize();
        $.ajax({
            type: "POST",
            url: "{{route('nueva_orden_datos')}}",
            datatype: 'json',
            data: form,
            success: function(data) {
                var nuevaFila = "";

                var descripcion = data.datos[0].desc_material;
                var codigo = data.datos[0].cod_material;
                var cantidad = $("#cantidad").val();
                var fecha = $("#fecha").val();

                nuevaFila += "<tr id='ver_detalle'>";
                nuevaFila += "<td id='cod'>" + codigo + " </td>";
                nuevaFila += "<td>" + descripcion + " </td>";
                nuevaFila += "<td><center>" + cantidad + "</center> </td>";
                nuevaFila += "<td><center>" + "UN." + " </center></td>";
                nuevaFila += "<td><center><a id='eliminar'>" +
                    "<i class='now-ui-icons ui-1_simple-remove'></i>" + " </a></center></td>";
                nuevaFila += "</tr>";
                $("#tabla_orden_temp").append(nuevaFila);
            }
        });
        return false;
    }
});

//----------------------------ELIMINAR PRODUCTOS DEL LISTADO TEMPORAL---------------------------------//

$('#tabla_orden_temp tbody').on('click', '#eliminar', function() {
    $(this).closest('tr').remove();
    $("#tabla_detalle_temp").empty();
});

//----------------------------MOSTRAR DETALLE DE PRODUCTOS---------------------------------//

$('#tabla_orden_temp tbody').on('click', '#ver_detalle', function() {

    var codigo = $(this).find('td').eq(0).text();
    var nombre = $(this).find('td').eq(1).text();
    var cant = $(this).find('td').eq(2).text();
    $("#nombre_material_temp").text(nombre);
    $("#codigo_material_temp").text(codigo);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('detalle_orden')}}",
        data: {
            cod: codigo
        },
        datatype: 'json',
        success: function(r) {

            var array = r;
            var data_length = array.length;
            var codigo = '';
            var descripcion = '';
            var cantidad = 0;
            var unidad = '';
            var nuevaFila = "";

            nuevaFila += " <tr>";
            nuevaFila += " <th>Cod.</th>";
            nuevaFila += " <th>Material</th>";
            nuevaFila += "<th>Cantidad</th>";
            nuevaFila += "<th>Unidad</th>";
            nuevaFila += " </tr>";

            for (var i = 0; i < data_length; i++) {
                codigo = array[i].codigo;
                descripcion = array[i].descripcion;
                cantidad = array[i].cantidad;
                unidad = array[i].unidad;

                var cantfinal = (cantidad) * cant;
                nuevaFila += "<td>" + codigo + " </td>";
                nuevaFila += "<td>" + descripcion + " </td>";
                nuevaFila += "<td>" + cantfinal + " </td>";
                nuevaFila += "<td>" + unidad + " </td>";
                nuevaFila += "</tr>";

                $("#tabla_detalle_temp").empty().append(nuevaFila);

            }
        }
    });
    return false;
});
//----------------------------GUARDAR ORDEN---------------------------------//
$('#guardar_orden').on('click', function() {

    var filas = [];

    $('#tabla_orden_temp tr').each(function() {

        var codigo = $(this).find('td').eq(0).text();
        var cantidad = $(this).find('td').eq(2).text();

        fechas = $("#fecha_orden").val();

        var fila = {
            codigo,
            cantidad
        };

        filas.push(fila);
    });

    var user = $('#usuario').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('guardar_orden')}}",
        data: {
            valores: filas,
            fecha: fechas,
            usuario: user
        },
        datatype: 'json',
        success: function(r) {
            var array = r;

            if (array.code == 1) {
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            }
            if (array.code == 0) {
                document.getElementById('notificacion_orden').style.display = 'block';
                var url = '{{ route("orden_print", ":id") }}';
                url = url.replace(':id', array.id);
                window.open(url, '_blank');
                location.reload();
            }

        }
    });
    return false;
});

//----------------------------CERRAR NOTIFICACION  ---------------------------------//

$('#cerrar_noti_orden').on('click', function() {
    document.getElementById('notificacion_orden').style.display = 'none';
});
$('#cerrar_noti_varias').on('click', function() {
    document.getElementById('notificacion_varias').style.display = 'none';
});
</script>
@endsection