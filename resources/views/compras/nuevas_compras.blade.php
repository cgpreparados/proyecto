@extends('layouts.app', [
'namePage' => 'Nueva Compra',
'class' => 'sidebar-mini',
'activePage' => 'nuevas_compras',
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
                    <div class="alert alert-success" id="notificacion_compra" style="display:none">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_noti_compra'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Guardado - </b> Compra Registrada</span>
                    </div>
                    <div class="alert alert-warning" id="notificacion_varias" style="display:none">
                        <button type="button" aria-hidden="true" class="close">
                            <i class="now-ui-icons ui-1_simple-remove" id='cerrar_noti_varias'></i>
                        </button>
                        <span><b> Alerta - </b>
                            <p id="texto_noti"></p>
                        </span>
                    </div>
                    <h4 class="card-title"> Registrar Compra</h4>
                </div>
                <div class="card-header">
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Fecha:</label>
                        <input type="date" id="fecha" name="" value="" class='form-control'>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Factura Nro.:</label>
                        <input type="" id="factura" name="" value="" class='form-control'>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Tipo Factura:</label>
                        <select id="tipo_factura" name="" value="" class='form-control'>
                            <option value="CONTADO">Contado</option>
                            <option value="CREDITO">Credito</option>
                        </select>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Proveedor:</label>
                        <select id="ruc_proveedor" name="" value="" class='form-control'>
                            @foreach($proveedor as $pro)
                            <option value="{{$pro->id_proveedor}}">{{$pro->nombre_proveedor}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Valor Factura:</label>
                        <input type="" id="valor_factura" name="" value="" class='form-control'>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <hr>
                        <h5>Detalles</h5>
                        <div class="col-md-6" style="display:inline-block;float:left;">
                            <label for="">Materiales:</label>
                            <select id="material" class='form-control'>
                                <option value="0">Seleccionar</option>
                                @foreach($materiales as $el)
                                <option value="{{$el->cod_material}}">{{$el->desc_material}}</option>
                                @endforeach
                            </select>
                            <label for="">Cantidad:</label>
                            <input type="text" name="" id="cantidad_compra" class='form-control'>
                            <label for="">Precio Unitario:</label>
                            <input type="text" name="" id="precio_unitario" class='form-control'>
                            <div style="float:right;">
                                <button class="btn btn-default btn-round" id="agregar_compra"><i
                                        class='now-ui-icons ui-1_simple-add'></i> Agregar </button>
                            </div>
                        </div>
                        <div class="col-md-6" style="display:inline-block;float:left;">
                            <table class="table" id='tabla_compras'>
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
                                        Precio
                                    </th>
                                    <th>
                                        Herr.
                                    </th>

                                </thead>
                            </table>
                            <div style="float:right;">
                                <button class="btn btn-primary btn-round" id="guardar_compra"><i
                                        class='now-ui-icons arrows-1_share-66'></i> Guardar </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
//----------------------------AGREGAR MATERIALES A LA COMPRA---------------------------------//

$('#agregar_compra').on('click', function() {


    var codigo = $('#material').val();
    var descripcion = $("#material option:selected").text();
    var cantidad = $('#cantidad_compra').val();
    var unitario = $('#precio_unitario').val();

    var nuevaFila = "";

    nuevaFila += "<tr id='ver_detalle'>";

    nuevaFila += "<td id='cod'>" + codigo + " </td>";
    nuevaFila += "<td>" + descripcion + " </td>";
    nuevaFila += "<td contenteditable='true'>" + cantidad + " </td>";
    nuevaFila += "<td contenteditable='true'>" + unitario + " </td>";
    nuevaFila += "<td><a id='eliminar_item_compra'><i class='now-ui-icons ui-1_simple-remove'></i> </a></td>";
    nuevaFila += "</tr>";

    $("#tabla_compras").append(nuevaFila);
});

//----------------------------ELIMINAR MATERIALES DEL LISTADO DE COMPRAS---------------------------------//

$('#tabla_compras').on('click', '#eliminar_item_compra', function() {
    $(this).closest('tr').remove();
});


//----------------------------GUARDAR COMPRA---------------------------------//
$('#guardar_compra').on('click', function() {

    var filas = [];
    var fila;
    var fecha = $('#fecha').val();
    var factura = $('#factura').val();
    var tipo = $('#tipo_factura').val();
    var ruc = $('#ruc_proveedor').val();
    

    $('#tabla_compras tr').each(function() {

        var codigo = $(this).find('td').eq(0).text();
        var cantidad = $(this).find('td').eq(2).text();
        var precio = $(this).find('td').eq(3).text();



        var fila = {
            codigo,
            cantidad,
            precio
        };

        filas.push(fila);
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var user = $('#usuario').val();
    var total = $('#valor_factura').val();

    $.ajax({
        type: "POST",
        url: "{{route('guardar_compra')}}",
        data: {
            valores: filas,
            user:user,
            fecha:fecha,
            factura: factura,
            tipo:tipo,
            ruc:ruc,
            total:total
        },
        datatype: 'json',
        success: function(r) {
            var array = r;
            if (array.code == 1) {
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            } else {
                document.getElementById('notificacion_compra').style.display = 'block';
                var id = array.id;
                var url = '{{ route("imprimir_compra", ":id") }}';
                url = url.replace(':id', id);
                window.open(url, '_blank');
            }
        } 
    });
    return false;
});

//----------------------------CERRAR NOTIFICACION  ---------------------------------//

$('#cerrar_noti_compra').on('click', function() {
    document.getElementById('notificacion_compra').style.display = 'none';
});
$('#cerrar_noti_varias').on('click', function() {
    document.getElementById('notificacion_varias').style.display = 'none';
});
</script>
@endsection