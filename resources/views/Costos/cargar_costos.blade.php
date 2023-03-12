@extends('layouts.app', [
'namePage' => 'Cargar Costos',
'class' => 'sidebar-mini',
'activePage' => 'cargar_costos',
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
                    <div class="alert alert-success" id="notificacion_compra" style="display:none">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_noti_compra'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Éxito! - </b> Costo Registrado</span>
                    </div>
                    <div class="alert alert-warning" id="notificacion_varias" style="display:none">
                        <button type="button" aria-hidden="true" class="close">
                            <i class="now-ui-icons ui-1_simple-remove" id='cerrar_noti_varias'></i>
                        </button>
                        <span><b> Alerta - </b>
                            <p id="texto_noti"></p>
                        </span>
                    </div>
                    <h4 class="card-title"> Cargar Costos</h4>
                </div>

                <div class="card-body">
                    <div class="col-md-12" style='float:left; display:inline-block'>
                        <label for="">Periodo:</label>
                        <input type="month" id="fecha" name="" value="" class='form-control'>
                    </div><br>
                    <div class="col-md-12" style='float:left; display:inline-block'>
                        <label for="">Tipo Costos:</label>
                        <select id="tipo_costo" name="" value="" class='form-control'>
                            <option value="Electricidad">Electricidad</option>
                            <option value="Agua">Agua</option>
                            <option value="Alquiler">Alquiler</option>
                            <option value="MOD">Mano de Obra</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div><br>
                    <div class="col-md-12" style='float:left; display:inline-block'>
                        <label for="">Precio:</label>
                        <input type="" id="precio" name="" value="" class='form-control'>
                    </div><br>
                    <div style="float:right;">
                        <button class="btn btn-primary btn-round" id="guardar_costo"><i
                                class='now-ui-icons arrows-1_share-66'></i> Guardar </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

//----------------------------GUARDAR COMPRA---------------------------------//
$('#guardar_costo').on('click', function() {

    var fecha = $('#fecha').val();
    var tipo_costo = $('#tipo_costo').val();
    var precio = $('#precio').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('guardar_costos')}}",
        data: {
            tipo_costo: tipo_costo,
            precio: precio,
            fecha: fecha
        },
        datatype: 'json',
        success: function(r) {
            var array = r;
            if (array.code == 1) {
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;
            } else {
                document.getElementById('notificacion_compra').style.display = 'block';
                location.reload();
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