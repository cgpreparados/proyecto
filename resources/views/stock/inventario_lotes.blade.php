@extends('layouts.app', [
'namePage' => 'Inventario Lotes',
'class' => 'sidebar-mini',
'activePage' => 'inventario_lotes',
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
                    <h4 class="card-title"> Inventario Lotes</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" id="notificacion_carga" style="display:none">
                        <button type="button" aria-hidden="true" class="close" id='cerrar_noti_carga'>
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </button>
                        <span><b> Guardado - </b> Inventario guardado con exito</span>
                    </div>
                    <div class="alert alert-warning" id="notificacion_varias" style="display:none">
                        <button type="button" aria-hidden="true" class="close">
                            <i class="now-ui-icons ui-1_simple-remove" id='cerrar_noti_varias'></i>
                        </button>
                        <span><b> Alerta - </b>
                            <p id="texto_noti"></p>
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id='tabla_inv_lotes'>
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
                                <tr>
                                    <td>{{$list->cod_material}}</td>
                                    <td>{{$list->desc_material}}</td>
                                    <td contentEditable="true"></td>
                                    <td>{{$list->unidad_material}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div style='float:right;'>
                            <button id="guardar_inventario" class="btn btn-primary btn-round">GuardarInventario</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
//---------------------------- GUARDAR INVENTARIO  ---------------------------------//
$('#guardar_inventario').on('click', function() {
    var filas = [];
    var fila;

    $('#tabla_inv_lotes tr').each(function() {

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
    var usuario = $('#usuario').val();
    $.ajax({
        type: "POST",
        url: "{{route('guardar_inventario_lotes')}}",
        data: {
            valores: filas,
            usuario: usuario
        },
        datatype: 'json',
        success: function(r) {
           var array = r;
            if (array.code == 1) {
                document.getElementById('notificacion_varias').style.display = 'block';
                document.getElementById('texto_noti').innerHTML = array.msg;

            } else {
                document.getElementById('notificacion_carga').style.display = 'block';
                fecha = array.fecha;
                var url = '{{ route("imprimir_inventario_lotes", ":id") }}';
                url = url.replace(':id', fecha);
                window.open(url, '_blank');
            }
        }
    });
    return false;
});

//----------------------------CERRAR NOTIFICACION  ---------------------------------//

$('#cerrar_noti_carga').on('click', function() {
    document.getElementById('notificacion_carga').style.display = 'none';
});
$('#cerrar_noti_varias').on('click', function() {
    document.getElementById('notificacion_varias').style.display = 'none';
});
</script>
@endsection