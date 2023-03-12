@extends('layouts.app', [
'namePage' => 'Movimiento Materiales',
'class' => 'sidebar-mini',
'activePage' => 'movimiento_materiales',
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
                        <span><b> Guardado - </b> Movimiento realizado con exito</span>
                    </div>
                    <div class="alert alert-warning" id="notificacion_varias" style="display:none">
                        <button type="button" aria-hidden="true" class="close">
                            <i class="now-ui-icons ui-1_simple-remove" id='cerrar_noti_varias'></i>
                        </button>
                        <span><b> Alerta - </b>
                            <p id="texto_noti"></p>
                        </span>
                    </div>
                    <h4 class="card-title"> Editar Movimientos</h4>
                    <div class="col-md-5" style="float:left; display:inline-block;">
                        <label for="">Material:</label>
                        <select id="add_mat_movimiento" class='form-control'>
                            <<option value="0">Seleccionar</option>
                            @foreach($elegir as $el)
                            <option value="{{$el->cod_material}}">{{$el->desc_material}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5" style="float:left; display:inline-block;">
                        <label for="">Movimiento:</label>
                        <select id="tipo_movimiento" class='form-control'>
                            <option value="ALTA">ALTA</option>
                            <option value="BAJA">BAJA</option>
                            <option value="Perdida">Perdida</option>

                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id='tabla_movimiento'>
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
                                    Observacion
                                </th>
                            </thead>
                        </table>
                    </div>
                    <div style='float:right;'>
                        <button id=guardar_movimiento class="btn btn-primary btn-round">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
//----------------------------AGREGAR PRODUCTOS AL LISTADO---------------------------------//

$('#add_mat_movimiento').change(function() {
    var nuevaFila = "";
    var descripcion = $("#add_mat_movimiento option:selected").text();
    var codigo = $("#add_mat_movimiento").val();
    var cantidad = 0;
    var unidad = "";
    var enviar = {
        "codigo": codigo
    };
    var observacion = "";
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
            nuevaFila += "<td contenteditable='true'" + observacion + " </td>";
            nuevaFila += "<td><center><a id='eliminar'>" +
                "<i class='now-ui-icons ui-1_simple-remove'></i>" + " </a></center></td>";
            nuevaFila += "</tr>";
            $("#tabla_movimiento").append(nuevaFila);
        }
    });
    return false;
});


//----------------------------ELIMINAR PRODUCTOS DEL LISTADO DE MOVIMIENTOS---------------------------------//

$('#tabla_movimiento').on('click', '#eliminar', function() {
    $(this).closest('tr').remove();
});


//----------------------------CERRAR NOTIFICACION  ---------------------------------//

$('#cerrar_noti_orden').on('click', function() {
    document.getElementById('notificacion_resultado').style.display = 'none';
});
$('#cerrar_noti_varias').on('click', function() {
    document.getElementById('notificacion_varias').style.display = 'none';
});

//---------------------------- GUARDAR MOVIMIENTO ---------------------------------//

$('#guardar_movimiento').on('click', function() {
    var filas = [];
    var fila;
    


    $('#tabla_movimiento tr').each(function() {

        var codigo = $(this).find('td').eq(0).text();
        var cantidad = $(this).find('td').eq(2).text();
        var observacion = $(this).find('td').eq(4).text();
        

        var fila = {
            codigo,
            cantidad,
            observacion
        };

        filas.push(fila);
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var user = $('#usuario').val(); 
    var tipos = $("#tipo_movimiento").val();
    alert(tipos);
    $.ajax({
        type: "POST",
        url: "{{route('guardar_movimiento')}}",
        data: {
            estado:tipos,
            valores: filas,
            user: user
            
        },
        datatype: 'json',
        success: function(r) {
            var array = r;
            if (array.code == 1) {
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            } else {
                var id= array.id;
                document.getElementById('notificacion_resultado').style.display = 'block';
                var url = '{{ route("imprimir_inventario_movimiento", ":id") }}';
                url = url.replace(':id', id);
                window.open(url, '_blank');
            }
        }
    });
    return false;
});
</script>
@endsection