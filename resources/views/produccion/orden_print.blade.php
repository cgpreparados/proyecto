<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CARO | ORDEN PRODUCCION </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/now-ui-dashboard.css?v=1.3.0" rel="stylesheet" />
    <link href="{{ asset('assets') }}/demo/demo.css" rel="stylesheet" />


</head>
<style>
.titulo-tabla {
    text-decoration: none;
    background-color: none;
    color: black;
    border-bottom: 1px solid black;
}
</style>

<body style="max-width:60%;">
    <div class="wrapper">
        <section class="invoice">
            <div class="row">
                <div class="col-6">
                    <h2 style="font-size: 20px;">
                        <img src="{{ asset('assets') }}/img/logo_caro_vector.png" style=" width: 80px;height: 90px;"></i>
                        Orden de Producción

                        
                    </h2>
                </div>
                <div class="col-6" style="float:right; padding-top:2.5%; padding-left:40%">
                <b><p style="font-size:20px;">Nro.: {{$id}}</p> </b>  
                </div>
            </div>
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <address>
                        @foreach($datos as $dato)
                        <strong>REALIZADO POR: </strong>{{$dato['user']}}
                        @endforeach
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    <address>
                        <strong> FECHA: </strong>
                        @foreach($datos as $dato) {{$dato['fecha']}} @endforeach
                    </address>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table">
                        <thead>

                        </thead>
                        <tbody>
                            @foreach($detalle as $deta)
                            <tr style=" border-bottom: 2px solid black;">
                                <th>{{$deta['codigo']}}</th>
                                <th>{{$deta['descripcion']}}</th>
                                <th>{{$deta['cantidad']}} {{$deta['unidad']}}</th>
                            </tr>
                            @foreach($deta['listado'] as $list)
                            <tr style="color:black">
                                <td>{{$list['codigo']}}</td>
                                <td>{{$list['descripcion']}}</td>
                                <td>{{$list['cantidad']}} {{$list['unidad']}}</td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-6">
                <br><br>
                <div class="table-responsive">
                    <strong>TOTAL A ENTREGAR: </strong>
                    <table class="table table-striped">
                        
                            <tr style="color:black"><strong>
                                    <th>Codigo</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Unidad</th>
                                </strong></tr>
                        
                        <tbody>
                            @foreach($totales as $total)
                            <tr>
                                <td>{{$total['codigo']}}</td>
                                <td>{{$total['descripcion']}}</td>
                                <td>{{$total['total']}}</td>
                                <td>{{$total['unidad']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <br>
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        <strong>PREPARADO POR:</strong>___________________________________ <strong>RECIBIDO
                            POR:</strong>___________________________________
                    </p>
                    <p class="lead" style="font-size: 15px;">Orden de Producción #{{$id}}</p>
                </div>
            </div>
        </section>
    </div>
</body>

</html>