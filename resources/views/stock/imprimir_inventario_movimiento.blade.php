<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CG PREPARADOS | MOVIMIENTO DE INVENTARIO </title>
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
                <div class="col-6">
                    <h2 style="font-size: 20px;">
                        <img src="{{ asset('assets') }}/img/logo-caro.png" style=" width: 80px;height: 90px;"></i>
                        Movimiento de Materiales - {{$operacion}}

                        
                    </h2>
                </div>
                <div class="col-6" style="float:right; padding-top:2.5%; padding-left:40%">
                <b><p style="font-size:20px;">Nro.: {{$id}}</p> </b>
                </div>
            </div>
            
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <address>
                        <strong>Realizado por: </strong>{{$user}}
                        
                    </address>
                    <address>
                        <strong>Fecha: </strong>{{$fecha}}
                        
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
                                <th>Observacion</th>                                
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($listado as $deta)
                            <tr>
                                <td>{{$deta->codigo}}</td>
                                <td>{{$deta->descripcion}}</td>
                                <td>{{$deta->cantidad}}</td>                                
                                <td>{{$deta->unidad}}</td>
                                <td>{{$deta->observacion}}</td>
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
                        <strong>VERIFICADO POR:</strong>___________________________________ 
                    </p>
                    <p class="lead" style="font-size: 15px;">Movimiento #{{$id}}</p>
                </div>
            </div>
        </section>
    </div>
</body>
</html>