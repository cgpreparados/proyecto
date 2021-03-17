@extends('layouts.app', [
'namePage' => 'Consultar Costos',
'class' => 'sidebar-mini',
'activePage' => 'consultar_costos',
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
                    <h4 class="card-title">Consultar Costos</h4>
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
                    <div class="col-md-6" style='float:left; display:inline-block'>
                        <label for="">Periodo:</label>
                        <input type="month" id="fecha_desde" name="" value="" class='form-control'>
                    </div>                    
                    <div class="col-md-6" style='float:left; display:inline-block'>
                        <button id="buscar_costos" class="btn btn-primary btn-round">Buscar</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id='tabla_impresion'>
                        </table>
                    </div>
                    <button id="export" class="btn btn-primary btn-round" style = "display:none; float:right" onclick="exportTableToExcel('tabla_impresion')"><i
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
    filename = filename?filename+'.xls':'excel_data_costos.xls';
    
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


//---------------------------- BUSCAR COSTOS  ---------------------------------//

$('#buscar_costos').on('click', function() {

    var periodo = $('#fecha_desde').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('buscar_costos')}}",
        data: {
            periodo:periodo
        },
        datatype: 'json',
        success: function(r) {
            var array = r.listado;
            if (array.code == 1) {
                document.getElementById('alert_orden').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            } else {
                var nuevaFila = "";
                var data_length = array.length;
                var total =r.total;
                total = String(total).replace(/(.)(?=(\d{3})+$)/g, '$1.');
                nuevaFila += " <thead class=' text-primary'><tr>";
                
                nuevaFila += "<th>Descripción</th>";
                nuevaFila += " <th>Costo</th>";

                nuevaFila += " </tr></thead>";

                nuevaFila += "<tbody>";

                for (var i = 0; i < data_length; i++) {

                    var descripcion = array[i].tipo_costo;
                    var costo = array[i].costo;
                    costo = String(costo).replace(/(.)(?=(\d{3})+$)/g, '$1.');

                    nuevaFila += "<tr>";
                    nuevaFila += "<td>" + descripcion + "</td>";
                    nuevaFila += "<td>" + costo + " </td>";
                    nuevaFila += "</tr>";


                }
                nuevaFila += "</tbody>";
                nuevaFila += "<tfoot>"
                nuevaFila += "<tr>"
                nuevaFila += "<td><b> Total</b></td>"
                nuevaFila += "<td><b>" + total + "</b></td>"
                nuevaFila += "</tr>"
                nuevaFila += "</tfoot>"
                $("#tabla_impresion").empty().append(nuevaFila);
                document.getElementById('export').style.display = 'block';
            }
        }
    });
    return false;

});
</script>
@endsection