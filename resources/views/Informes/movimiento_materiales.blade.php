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
                    <h4 class="card-title">Movimiento Materiales</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-danger" id="alert_orden" style="display:none; ">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_alert_orden'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Error - </b>
                            <p id="texto_noti"></p>
                        </span>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Fecha Desde:</label>
                        <input type="date" id="fecha_desde" name="" value="" class='form-control'>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Fecha Hasta:</label>
                        <input type="date" id="fecha_hasta" name="" value="" class='form-control'>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <label for="">Material:</label>
                        <select id="material" name="" value="" class='form-control'>
                            @foreach($materiales as $m)
                            <option value="{{$m->cod_material}}">{{$m->desc_material}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <button id="buscar_movimiento" class="btn btn-primary btn-round">Consultar</button>
                    </div>
                    <div class="table-responsive">
                        <h4 style='display:none' id='titulo_compras'>Compras</h4>
                        <table class="table" id='tabla_compras'>
                        </table>
                        <h4 style='display:none' id='titulo_ordenes'>Ordenes de Produccion</h4>
                        <table class="table" id='tabla_ordenes'>
                        </table>
                        <h4 style='display:none' id='titulo_alta'>Alta de Stock</h4>
                        <table class="table" id='tabla_alta'>
                        </table>
                        <h4 style='display:none' id='titulo_baja'>Baja de Stock</h4>
                        <table class="table" id='tabla_baja'>
                        </table>
                        <h4 style='display:none' id='titulo_venta'>Venta</h4>
                        <table class="table" id='tabla_venta'>
                        </table>
                    </div>
                    <button id="export" class="btn btn-primary btn-round" style = "display:none; float:right" onclick="exportTableToExcel('tabla_compras')"><i
                                        class='now-ui-icons arrows-1_share-66'></i>Exportar Datos</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data_facturas.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
//----------------------------CERRAR NOTIFICACION  ---------------------------------//
$('#cerrar_alert_orden').on('click', function() {
    document.getElementById('alert_orden').style.display = 'none';
});
//---------------------------- IMPRIMIR ORDEN  ---------------------------------//

$('#tabla_impresion ').on('click', '#imprimir', function() {

    var id = $(this).closest('tr').find('td').eq(0).text(); 


    var url = "proyecto/public/factura/" + id + "/1";
    window.open(url, '_blank');


});

//---------------------------- BUSCAR ORDEN  ---------------------------------//

$('#buscar_movimiento').on('click', function() {
    var fecha_inicial = $('#fecha_desde').val();
    var fecha_fin = $('#fecha_hasta').val();
    var material = $('#material').val();

    $("#tabla_compras").empty();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('buscar_movimientos')}}",
        data: {
            fecha_inicio: fecha_inicial,
            fecha_fin: fecha_fin,
            material: material
        },
        datatype: 'json',
        success: function(r) {
            var array = r.compras;
                var nuevaFila = "";
                var data_length = array.length;
                // alert(data_length);
                
                nuevaFila += "<thead class=' text-primary'><tr>";
                nuevaFila += "<th>Fecha Compra</th>";
                nuevaFila += " <th>Cantidad</th>";
                nuevaFila += " <th>Unidad Medida</th>";
                nuevaFila += "<th>Precio Unitario</th>";
                nuevaFila += "<th>Total</th>";
                nuevaFila += "<th>Proveedor</th>";
                nuevaFila += "<th>Usuario</th>";

                nuevaFila += " </tr></thead>";

                nuevaFila += "<tbody>";
                var total_compras=0;
                var total_cantidad=0;
                for (var i = 0; i < data_length; i++) {

                    var fecha = array[i].fecha;
                    var usuario = array[i].usuario;

                    let current_datetime = new Date(fecha);

                    let formatted_date = current_datetime.getDate() + "/" + (current_datetime.getMonth() + 1) + "/" + current_datetime.getFullYear();
                    const precio = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].precio);

                    const cantidad = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(array[i].cantidad);

                    const total = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].cantidad * array[i].precio);

                    nuevaFila += "<tr>";
                    nuevaFila += "<td>" + formatted_date + " </td>";
                    nuevaFila += "<td>" + cantidad + "</td>";
                    nuevaFila += "<td>" + array[i].unidad + "</td>";
                    nuevaFila += "<td>" + precio + "</td>";
                    nuevaFila += "<td>" + total + "</td>";
                    nuevaFila += "<td>" + array[i].proveedor + "</td>";
                    nuevaFila += "<td>" + usuario + " </td>";

                    nuevaFila += "</tr>";
                    total_compras=total_compras+(array[i].cantidad * array[i].precio);
                    total_cantidad= parseFloat(total_cantidad) + parseFloat(array[i].cantidad);
                   // console.log(total_cantidad);
                }

                total_compras = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(total_compras);

                    total_cantidad = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(total_cantidad);

                nuevaFila += "</tbody>";
                nuevaFila += "<tfoot>"
                nuevaFila += "<tr>"
                nuevaFila += "<td colspan='1'><b> Total</b></td>"
                nuevaFila += "<td colspan='1'><b>"+ total_cantidad +"</b></td>"
                nuevaFila += "<td colspan='2'><b> </b></td>"
                nuevaFila += "<td colspan='1'><b>"+ total_compras +"</b></td>"
                nuevaFila += "<td colspan='3'><b></b></td>"
                nuevaFila += "</tr>"
                nuevaFila += "</tfoot>"
                $("#tabla_compras").empty().append(nuevaFila);
                document.getElementById('titulo_compras').style.display = 'block';
            //-----------------------------------------------------------------
                var array = r.ordenes;
                var nuevaFila = "";
                var data_length = array.length;
                // alert(data_length);
                
                nuevaFila += "<thead class=' text-primary'><tr>";
                nuevaFila += "<th>Fecha</th>";
                nuevaFila += " <th>Cantidad</th>";
                nuevaFila += " <th>Observacion</th>";
                nuevaFila += "<th>Usuario</th>";

                nuevaFila += " </tr></thead>";

                nuevaFila += "<tbody>";
                var total_ordenes=0;
                for (var i = 0; i < data_length; i++) {

                    var fecha = array[i].fecha;
                    var usuario = array[i].user;

                    let current_datetime = new Date(fecha);

                    let formatted_date = current_datetime.getDate() + "/" + (current_datetime.getMonth() + 1) + "/" + current_datetime.getFullYear();
                    const precio = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].precio);

                    const cantidad = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(array[i].cantidad);

                    const total = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].cantidad * array[i].precio);

                    nuevaFila += "<tr>";
                    nuevaFila += "<td>" + formatted_date + " </td>";
                    nuevaFila += "<td>" + cantidad + "</td>";
                    nuevaFila += "<td>" + array[i].observacion + "</td>";
                    nuevaFila += "<td>" + usuario + " </td>";
                    nuevaFila += "</tr>";
                    total_ordenes= parseFloat(total_ordenes) + parseFloat(array[i].cantidad);
                   // console.log(total_cantidad);
                }

                    total_ordenes = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(total_ordenes);

                nuevaFila += "</tbody>";
                nuevaFila += "<tfoot>"
                nuevaFila += "<tr>"
                nuevaFila += "<td colspan='1'><b> Total</b></td>"
                nuevaFila += "<td colspan='3'><b>"+ total_ordenes +"</b></td>"
                nuevaFila += "</tr>"
                nuevaFila += "</tfoot>"
                $("#tabla_ordenes").empty().append(nuevaFila);
                document.getElementById('titulo_ordenes').style.display = 'block';
                //-----------------------------------------------------------------
                var array = r.alta;
                var nuevaFila = "";
                var data_length = array.length;
                // alert(data_length);
                
                nuevaFila += "<thead class=' text-primary'><tr>";
                nuevaFila += "<th>Fecha</th>";
                nuevaFila += " <th>Cantidad</th>";
                nuevaFila += " <th>Observacion</th>";
                nuevaFila += "<th>Usuario</th>";

                nuevaFila += " </tr></thead>";

                nuevaFila += "<tbody>";
                var total_alta=0;
                for (var i = 0; i < data_length; i++) {

                    var fecha = array[i].fecha;
                    var usuario = array[i].user;

                    let current_datetime = new Date(fecha);

                    let formatted_date = current_datetime.getDate() + "/" + (current_datetime.getMonth() + 1) + "/" + current_datetime.getFullYear();
                    const precio = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].precio);

                    const cantidad = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(array[i].cantidad);

                    const total = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].cantidad * array[i].precio);

                    nuevaFila += "<tr>";
                    nuevaFila += "<td>" + formatted_date + " </td>";
                    nuevaFila += "<td>" + cantidad + "</td>";
                    nuevaFila += "<td>" + array[i].observacion + "</td>";
                    nuevaFila += "<td>" + usuario + " </td>";
                    nuevaFila += "</tr>";
                    total_alta= parseFloat(total_alta) + parseFloat(array[i].cantidad);
                   // console.log(total_cantidad);
                }

                    total_alta = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(total_alta);

                nuevaFila += "</tbody>";
                nuevaFila += "<tfoot>"
                nuevaFila += "<tr>"
                nuevaFila += "<td colspan='1'><b> Total</b></td>"
                nuevaFila += "<td colspan='3'><b>"+ total_alta +"</b></td>"
                nuevaFila += "</tr>"
                nuevaFila += "</tfoot>"
                $("#tabla_alta").empty().append(nuevaFila);
                document.getElementById('titulo_alta').style.display = 'block';
                //-----------------------------------------------------------------
                var array = r.baja;
                var nuevaFila = "";
                var data_length = array.length;
                // alert(data_length);
                
                nuevaFila += "<thead class=' text-primary'><tr>";
                nuevaFila += "<th>Fecha</th>";
                nuevaFila += " <th>Cantidad</th>";
                nuevaFila += " <th>Observacion</th>";
                nuevaFila += "<th>Usuario</th>";

                nuevaFila += " </tr></thead>";

                nuevaFila += "<tbody>";
                var total_baja=0;
                for (var i = 0; i < data_length; i++) {

                    var fecha = array[i].fecha;
                    var usuario = array[i].user;

                    let current_datetime = new Date(fecha);

                    let formatted_date = current_datetime.getDate() + "/" + (current_datetime.getMonth() + 1) + "/" + current_datetime.getFullYear();
                    const precio = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].precio);

                    const cantidad = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(array[i].cantidad);

                    const total = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].cantidad * array[i].precio);

                    nuevaFila += "<tr>";
                    nuevaFila += "<td>" + formatted_date + " </td>";
                    nuevaFila += "<td>" + cantidad + "</td>";
                    nuevaFila += "<td>" + array[i].observacion + "</td>";
                    nuevaFila += "<td>" + usuario + " </td>";
                    nuevaFila += "</tr>";
                    total_baja= parseFloat(total_baja) + parseFloat(array[i].cantidad);
                   // console.log(total_cantidad);
                }

                    total_baja = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(total_baja);

                nuevaFila += "</tbody>";
                nuevaFila += "<tfoot>"
                nuevaFila += "<tr>"
                nuevaFila += "<td colspan='1'><b> Total</b></td>"
                nuevaFila += "<td colspan='3'><b>"+ total_baja +"</b></td>"
                nuevaFila += "</tr>"
                nuevaFila += "</tfoot>"
                $("#tabla_baja").empty().append(nuevaFila);
                document.getElementById('titulo_baja').style.display = 'block';
            //-----------------------------------------------------------------
            var array = r.venta;
                var nuevaFila = "";
                var data_length = array.length;
                // alert(data_length);
                
                nuevaFila += "<thead class=' text-primary'><tr>";
                nuevaFila += "<th>Fecha</th>";
                nuevaFila += " <th>Cantidad</th>";
                nuevaFila += " <th>Observacion</th>";
                nuevaFila += "<th>Usuario</th>";

                nuevaFila += " </tr></thead>";

                nuevaFila += "<tbody>";
                var total_venta=0;
                for (var i = 0; i < data_length; i++) {

                    var fecha = array[i].fecha;
                    var usuario = array[i].user;

                    let current_datetime = new Date(fecha);

                    let formatted_date = current_datetime.getDate() + "/" + (current_datetime.getMonth() + 1) + "/" + current_datetime.getFullYear();
                    const precio = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].precio);

                    const cantidad = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(array[i].cantidad);

                    const total = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 0,
                    }).format(array[i].cantidad * array[i].precio);

                    nuevaFila += "<tr>";
                    nuevaFila += "<td>" + formatted_date + " </td>";
                    nuevaFila += "<td>" + cantidad + "</td>";
                    nuevaFila += "<td>" + array[i].observacion + "</td>";
                    nuevaFila += "<td>" + usuario + " </td>";
                    nuevaFila += "</tr>";
                    total_venta= parseFloat(total_venta) + parseFloat(array[i].cantidad);
                   // console.log(total_cantidad);
                }

                    total_venta = new Intl.NumberFormat("es-ES", {
                        maximumFractionDigits: 3,
                    }).format(total_venta);

                nuevaFila += "</tbody>";
                nuevaFila += "<tfoot>"
                nuevaFila += "<tr>"
                nuevaFila += "<td colspan='1'><b> Total</b></td>"
                nuevaFila += "<td colspan='3'><b>"+ total_venta +"</b></td>"
                nuevaFila += "</tr>"
                nuevaFila += "</tfoot>"
                $("#tabla_venta").empty().append(nuevaFila);
                document.getElementById('titulo_venta').style.display = 'block';
        }
    });
    return false;

});
</script>
@endsection