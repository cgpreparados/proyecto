@extends('layouts.app', [
'namePage' => 'Lotes',
'class' => 'sidebar-mini',
'activePage' => 'lotes',
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
                <div class="card-header">
                    <div class="alert alert-success" id="success_lote" style="display:none">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_success_lote'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Guardado - </b> Movimiento generado con exito.</span>
                    </div>
                    <div class="alert alert-warning" id="alert_lote" style="display:none">
                        <button type="button" aria-hidden="true" class="close">
                            <i class="now-ui-icons ui-1_simple-remove" id='cerrar_alert_lote'></i>
                        </button>
                        <span><b> Alerta - </b>
                            <p id="texto_alert"></p>
                        </span>
                    </div>
                    <h4 class="card-title">Lotes</h4>
                </div>
                <div class="card-body">
                    <div class="col-md-5" style='float:left; display:inline-block'>
                        <label for="">Nro. Lote:</label>
                        <input type="" id="search_lote" name="" value="" class='form-control'>
                    </div>
                    <div class="col-md-2" style='float:left; display:inline-block'>
                        <button id="buscar_lote" class="btn btn-primary btn-round">Buscar</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id='tabla_lotes'>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5" id='lotes_detalle' style="display:none;float:left;">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Detalle</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id='tabla_detalle_lotes'>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
//----------------------------MOSTRAR DETALLE DE LOTES---------------------------------//

$('#tabla_lotes ').on('click', '#ver_detalle', function() {

    var id = $(this).find('td').eq(0).text();


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('detalle_lotes')}}",
        data: {
            id: id
        },
        datatype: 'json',
        success: function(r) {
            document.getElementById('lotes_detalle').style.display = 'block';
            var array = r
            var data_length = array.length;
            var codigo = '';
            var descripcion = '';
            var cantidad = 0;
            var unidad = '';
            var nuevaFila = "";

            nuevaFila += " <tr >";
            nuevaFila += " <th>Fecha</th>";
            nuevaFila += " <th>Operacion</th>";
            nuevaFila += " </tr>";

            for (var i = 0; i < data_length; i++) {

                fecha = array[i].fecha;
                operacion = array[i].lote_operacion;


                nuevaFila += "<td>" + fecha + " </td>";
                nuevaFila += "<td>" + operacion + " </td>";
                nuevaFila += "</tr>";

                $("#tabla_detalle_lotes ").empty().append(nuevaFila);

            }
        }
    });
    return false;
});


//----------------------------CERRAR NOTIFICACION  ---------------------------------//

$('#cerrar_success_lote').on('click', function() {
    document.getElementById('success_lote').style.display = 'none';
});
$('#cerrar_alert_lote').on('click', function() {
    document.getElementById('alert_lote').style.display = 'none';
});

//---------------------------- BUSCAR LOTE ---------------------------------//

$('#buscar_lote').on('click', function() {
    
    var lote = $('#search_lote').val();
   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('buscar_lote')}}",
        data: {
            lote: lote    
        },
        
        datatype: 'json',
        success: function(r) {
            var array = r;
            var nuevaFila = "";

            var data_length = array.length;

            nuevaFila += " <thead class=' text-primary'><tr>";
            nuevaFila += " <th style='display:none'>Id Lote</th>";
            nuevaFila += " <th>Nro. Lote</th>";
            nuevaFila += " <th>Fecha Elab.</th>";
            nuevaFila += "<th>Producto</th>";
            nuevaFila += "<th>cantidad</th>";
            nuevaFila += "<th>Herramientas</th>";
            nuevaFila += " </tr></thead>";

            for (var i = 0; i < data_length; i++) {

                var id = array[i].id;
                var lote = array[i].lote;
                var fecha = array[i].fecha_elab;
                var material = array[i].descripcion;
                var cantidad = array[i].cantidad;

                nuevaFila += "<tbody><tr id='ver_detalle'>";
                nuevaFila += "<td style='display:none'>" + id + " </td>";
                nuevaFila += "<td>" + lote + " </td>";
                nuevaFila += "<td>" + fecha + " </td>";
                nuevaFila += "<td>" + material + "</td>";
                nuevaFila += "<td><center>" + cantidad + "</center> </td>";
                nuevaFila +=
                    ' <td><a id="alta"><i class="now-ui-icons arrows-1_cloud-upload-94"></i> </a><a id="baja"><i class="now-ui-icons arrows-1_cloud-download-93"></i> </a></td>';
                nuevaFila += "</tr></tbody>";

                $("#tabla_lotes").empty().append(nuevaFila);
            }
        }
    });
    return false;
});

$('#search_lote').keypress(function(e) {
    if (e.which == 13) {
        event.preventDefault();
        
        var lote = $('#search_lote').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "{{route('buscar_lote')}}",
            data: {
                lote: lote
            },
            datatype: 'json',
            success: function(r) {
                var array = r;
                var nuevaFila = "";

                var data_length = array.length;

                nuevaFila += " <thead class=' text-primary'><tr>";
                nuevaFila += " <th style='display:none'>Id Lote</th>";
                nuevaFila += " <th>Nro. Lote</th>";
                nuevaFila += " <th>Fecha Elab.</th>";
                nuevaFila += "<th>Producto</th>";
                nuevaFila += "<th>cantidad</th>";
                nuevaFila += "<th>Herramientas</th>";
                nuevaFila += " </tr></thead>";

                for (var i = 0; i < data_length; i++) {

                    var id = array[i].id;
                    var lote = array[i].lote;
                    var fecha = array[i].fecha_elab;
                    var material = array[i].descripcion;
                    var cantidad = array[i].cantidad;

                    nuevaFila += "<tbody><tr id='ver_detalle'>";
                    nuevaFila += "<td style='display:none'>" + id + " </td>";
                    nuevaFila += "<td>" + lote + " </td>";
                    nuevaFila += "<td>" + fecha + " </td>";
                    nuevaFila += "<td>" + material + "</td>";
                    nuevaFila += "<td><center>" + cantidad + "</center> </td>";
                    nuevaFila +=
                        ' <td><a id="alta"><i class="now-ui-icons arrows-1_cloud-upload-94"></i> </a><a id="baja"><i class="now-ui-icons arrows-1_cloud-download-93"></i> </a></td>';
                    nuevaFila += "</tr></tbody>";

                    $("#tabla_lotes").empty().append(nuevaFila);
                }
            }
        });
        return false;
    }

});

//---------------------------- ALTA LOTE ---------------------------------//

$('#tabla_lotes').on('click', '#alta', function() {

    var id = $(this).closest('tr').find('td').eq(0).text();
    var user = $('#usuario').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('modificar_lotes')}}",
        data: {
            lote: id,
            action: 1,
            user: user
        },
        datatype: 'json',
        success: function(r) {
            var array = r;

            if(array.code == 0){
                document.getElementById('success_lote').style.display = 'block';
            }else{
                document.getElementById('alert_lote').style.display = 'block';
                document.getElementById('texto_alert').innerHTML = array.msg;
            }
        }
    });
    return false;
});
//---------------------------- BAJA LOTE ---------------------------------//

$('#tabla_lotes').on('click', '#baja', function() {

var id = $(this).closest('tr').find('td').eq(0).text();
var user = $('#usuario').val();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajax({
    type: "POST",
    url: "{{route('modificar_lotes')}}",
    data: {
        lote: id,
        action: 0,
        user: user
    },
    datatype: 'json',
    success: function(r) {
        var array = r;

        if(array.code == 0){
            document.getElementById('success_lote').style.display = 'block';
        }else{
            document.getElementById('alert_lote').style.display = 'block';
            document.getElementById('texto_alert').innerHTML = array.msg;
        }
    }
});
return false;
});
</script>
@endsection