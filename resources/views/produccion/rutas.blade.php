@extends('layouts.app', [
'namePage' => 'Rutas',
'class' => 'sidebar-mini',
'activePage' => 'rutas',
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
        <div class="col-md-7" style="display:inline-block;float:left;">
            <div class="card">
                <div class="card-header">
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
                    <h4 class="card-title"> Rutas</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id='tabla_rutas'>
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
                            </thead>
                            <tbody>
                                @foreach ($materiales as $material)
                                <tr id='ver_detalle'>
                                    <td>{{$material->cod_material}}</td>
                                    <td>{{$material->desc_material}}</td>
                                    <td>{{$material->unidad_material}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5" id='rutas_detalle' style="display:none;float:left;">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Detalle</h4>
                    <h5 class=" text-primary" id="codigo_material_temp"></h5>
                    <h5 class=" text-primary" id="nombre_material_temp"></h5>
                    <p>Resultado:</p>
                    <p id="nombre_resultado" style="color:grey"></p>
                    <p style="color:grey" id="codigo_resultado"></p>
                    <select id="add_mat_ruta" class='form-control'>
                        @foreach($elegir as $el)
                        <option value="{{$el->cod_material}}">{{$el->desc_material}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="alert alert-success" id="notificacion_resultado" style="display:none">
                    <button type="button" aria-hidden="true" class="close" id='cerrar_noti_resultado'>
                        <i class="now-ui-icons ui-1_simple-remove"></i>
                    </button>
                    <span><b> Guardado - </b> Asignado con exito</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id='tabla_detalle_rutas'>

                        </table>
                    </div>
                    <div style='float:left;'>
                        <button class="btn btn-default btn-round" data-toggle="modal"
                            data-target="#modalResultado">Resultado</button>
                    </div>
                    <div style='float:right;'>
                        <button id=guardar_formula class="btn btn-primary btn-round">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------------------------MODAL RESULTADOS---------------------------------->
<div class="modal fade" id="modalResultado" tabindex="-1" role="dialog" aria-labelledby="modalResultado"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="card-title" id="exampleModalLongTitle">Seleccionar Producto Resultado</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <select id="material_resultado" class="form-control">
                    @foreach($resultado as $result)
                    <option value="{{$result->cod_material}}">{{$result->desc_material}}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardar_resultado">Guardar</button>
            </div>
        </div>
    </div>
</div>
<script>
//----------------------------AGREGAR PRODUCTOS A LA RUTA---------------------------------//

$('#add_mat_ruta').change(function() {
    var nuevaFila = "";
    var descripcion = $("#add_mat_ruta option:selected").text();
    var codigo = $("#add_mat_ruta").val();
    var cantidad = 0;
    var unidad = "";
    var enviar = {
        "codigo": codigo
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('detalle_rutas_add')}}",
        data: {
            codigo: codigo
        },
        success: function(r) {
            //alert(r);
            unidad = r;
            nuevaFila += "<tr>";
            nuevaFila += "<td >" + codigo + " </td>";
            nuevaFila += "<td>" + descripcion + " </td>";
            nuevaFila += "<td contenteditable='true'>" + cantidad + " </td>";
            nuevaFila += "<td>" + unidad + " </td>";
            nuevaFila += "<td><center><a id='eliminar'>" +
                "<i class='now-ui-icons ui-1_simple-remove'></i>" + " </a></center></td>";
            nuevaFila += "</tr>";

            $("#tabla_detalle_rutas").append(nuevaFila);
        }
    });
    return false;
});

//----------------------------ELIMINAR PRODUCTOS DEL LISTADO RUTAS---------------------------------//

$('#tabla_detalle_rutas').on('click', '#eliminar', function() {
    $(this).closest('tr').remove();
    //  $("#tabla_detalle_rutas").empty();
});

//----------------------------MOSTRAR DETALLE DE PRODUCTOS---------------------------------//

$('#tabla_rutas').on('click', '#ver_detalle', function() {

    var codigo = $(this).find('td').eq(0).text();
    var nombre = $(this).find('td').eq(1).text();

    $("#nombre_material_temp").text(nombre);
    $("#codigo_material_temp").text(codigo);
    $("#tabla_detalle_rutas").empty();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('detalle_rutas')}}",
        data: {
            codigo: codigo
        },
        datatype: 'json',
        success: function(r) {
            document.getElementById('rutas_detalle').style.display = 'block';
            var array = r.detalles;
            var material = r.material;
            var data_length = array.length;
            var codigo = '';
            var descripcion = '';
            var cantidad = 0;
            var unidad = '';
            var nuevaFila = "";
            if (material == '-') {
                $("#nombre_resultado").text(' ');
                $("#codigo_resultado").text(' ');
            } else {
                var material_resultado = material[0].desc_material;
                var cod_resultado = material[0].cod_material;
                $("#nombre_resultado").text(material_resultado);
                $("#codigo_resultado").text(cod_resultado);
            }

            nuevaFila += " <tr>";
            nuevaFila += " <th>Cod.</th>";
            nuevaFila += " <th>Material</th>";
            nuevaFila += "<th>Cantidad</th>";
            nuevaFila += "<th>Unidad</th>";
            nuevaFila += "<th>Herr.</th>";
            nuevaFila += " </tr>";

            for (var i = 0; i < data_length; i++) {
                codigo = array[i].codigo_material;
                descripcion = array[i].desc_material;
                cantidad = array[i].cantidad;
                unidad = array[i].unidad_material;


                nuevaFila += "<td>" + codigo + " </td>";
                nuevaFila += "<td>" + descripcion + " </td>";
                nuevaFila += "<td contenteditable='true'>" + cantidad + " </td>";
                nuevaFila += "<td>" + unidad + " </td>";
                nuevaFila += "<td><center><a id='eliminar'>" +
                    "<i class='now-ui-icons ui-1_simple-remove'></i>" + " </a></center></td>";
                nuevaFila += "</tr>";

                $("#tabla_detalle_rutas").empty().append(nuevaFila);

            }
        }
    });
    return false;
});
//----------------------------GUARDAR RESULTADO---------------------------------//
$('#guardar_resultado').on('click', function() {

    var codigo = $('#codigo_material_temp').text();
    var codigo_terminado = $('#material_resultado').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('guardar_resultado')}}",
        data: {
            codigo: codigo,
            codigo_resultado: codigo_terminado
        },
        datatype: 'json',
        success: function(r) {
            var array = r;

            if (array.code == 1) {
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            } else {
                $('#modalResultado').modal('hide');
                document.getElementById('notificacion_resultado').style.display = 'block';
                material_resultado = array[0].desc_material;
                cod_resultado = array[0].cod_material;
                $("#nombre_resultado").text(material_resultado);
                $("#codigo_resultado").text(cod_resultado);
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
$('#cerrar_noti_resultado').on('click', function() {
    document.getElementById('notificacion_resultado').style.display = 'none';
});

//---------------------------- GUARDAR FORMULA ---------------------------------//

$('#guardar_formula').on('click', function() {
    var filas = [];
    var fila;
    var codigo_terminado = $('#codigo_resultado').text();


    $('#tabla_detalle_rutas tr').each(function() {

        var codigo = $(this).find('td').eq(0).text();
        var cantidad = $(this).find('td').eq(2).text();



        var fila = {
            codigo,
            cantidad
        };

        filas.push(fila);
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('guardar_formula')}}",
        data: {
            valores: filas,
            codigo: codigo_terminado
        },
        datatype: 'json',
        success: function(r) {
            var array = r;
            if (array.code == 1) {
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            } else {
                document.getElementById('notificacion_resultado').style.display = 'block';
            }
        }
    });
    return false;
});
</script>
@endsection