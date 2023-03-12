@extends('layouts.app', [
'namePage' => 'Stock Lotes',
'class' => 'sidebar-mini',
'activePage' => 'stock_lotes',
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
                    <h4 class="card-title"> Stock Lotes</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                    <h5>Resumen</h5>
                        <table class="table" id='tabla_stock_lotes'>
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Descripcion</th>
                                    <th>Stock</th>
                                    <th>Unidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listado as $list)
                                    <tr id="ver_detalle">
                                        <td >{{$list['codigo']}}</td>
                                        <td>{{$list['descripcion']}}</td>
                                        <td>{{$list['cantidad']}}</td>
                                        <td>UN.</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5" id='lotes_detalle' style="display:none;float:left;">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Detalle</h4>
                    <h5 class=" text-primary" id="codigo_material_temp"></h5>
                    <h5 class=" text-primary" id="nombre_material_temp"></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id='tabla_detalle_lote'>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
//----------------------------MOSTRAR DETALLE DE LOTES---------------------------------//

$('#tabla_stock_lotes').on('click', '#ver_detalle', function() {

    $("#tabla_detalle_lote").empty();

var codigo = $(this).find('td').eq(0).text();
var nombre = $(this).find('td').eq(1).text();

$("#nombre_material_temp").text(nombre);
$("#codigo_material_temp").text(codigo);

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
    type: "POST",
    url: "{{route('detalle_lotes_stock')}}",
    data: {
        codigo: codigo
    },
    datatype: 'json',
    success: function(r) {
        document.getElementById('lotes_detalle').style.display = 'block';
        var array = r;
        var data_length = array.length;
        var codigo = '';
        var descripcion = '';
        var cantidad = 0;
        var unidad = '';
        var nuevaFila = "";

        nuevaFila += " <tr>";
        nuevaFila += " <th>Lote</th>";
        nuevaFila += " <th>Nro.</th>";
        nuevaFila += "<th>Fecha Elaboracion</th>";
        nuevaFila += "<th>Fecha Vencimiento</th>";
        nuevaFila += "<th>Cantidad</th>";
        nuevaFila += "<th>Unidad</th>";
        nuevaFila += " </tr>";

        for (var i = 0; i < data_length; i++) {
            produccion = array[i].produccion;
            contenedor = array[i].contenedor;
            fecha_elab = array[i].fecha_elab;
            vencimiento = array[i].vencimiento;
            cantidad = array[i].cantidad;
            unidad = array[i].unidad;


            nuevaFila += "<td>" + produccion + " </td>";
            nuevaFila += "<td>" + contenedor + " </td>";
            nuevaFila += "<td>" + fecha_elab + " </td>";
            nuevaFila += "<td>" + vencimiento + " </td>";
            nuevaFila += "<td>" + cantidad + " </td>";
            nuevaFila += "<td>" + unidad + " </td>";
            nuevaFila += "</tr>";

            $("#tabla_detalle_lote").empty().append(nuevaFila);

        }
    }
});
return false;
});
</script>
@endsection