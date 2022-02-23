<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CG PREPARADOS | ORDEN PRODUCCION </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/now-ui-dashboard.css?v=1.3.0" rel="stylesheet" />
    <link href="{{ asset('assets') }}/demo/demo.css" rel="stylesheet" />
</head>
<body>
    <div class="wrapper">
        <section class="invoice">
            <div class="row">
                <div class="col-12">
                    <h2 style="font-size: 20px;">
                        <img src="{{ asset('assets') }}/img/logo-orden.png" style=" width: 50px;height: 50px;"></i>
                        Orden de Producción.

                        <small class="float-right" style="font-size: 20px;">ORDEN NRO: {{$id}} </small>
                    </h2>
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
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalle as $deta)
                            <tr style='background-color:#00004d; color:#fff;'>
                                <td>{{$deta['codigo']}}</td>
                                <td>{{$deta['descripcion']}}</td>
                                <td>{{$deta['cantidad']}}</td>
                                <td>{{$deta['unidad']}}</td>
                            </tr>
                            @foreach($deta['listado'] as $list)
                            <tr>
                                <td>{{$list['codigo']}}</td>
                                <td>{{$list['descripcion']}}</td>
                                <td>{{$list['cantidad']}}</td>
                                <td>{{$list['unidad']}}</td>
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
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
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