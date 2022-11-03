<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CG PREPARADOS | ENVIO </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/now-ui-dashboard.css?v=1.3.0" rel="stylesheet" />
    <link href="{{ asset('assets') }}/demo/demo.css" rel="stylesheet" />
    <style>
    #borde {
        border-style: groove;
        border-radius: 5px;
        height: 200px;
    }

    #datos {
        border-style: groove;
        border-radius: 5px;
        height: 120px;
    }

    .white {
        color: white;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <section class="invoice">
            <div class="row" style="margin:10px;">
                <div id='borde' style="margin-right:20px; width:55%;float:left; display:inline-block;">
                    <div class="col-5" style="display:inline-block; float:left; padding-top:30px; ">
                        <img src="{{ asset('assets') }}/img/logo_caro_vector.png"
                            style=" width: 120px;height:130px; padding-top:10px;">
                    </div>
                    <div class="col-7" style="float:left; display:inline-block; text-align:right; padding-top:35px;">

                        <b>
                            <p style="line-height: 34%; size:5px;"> Maria Carlota Gracia de Soerensen EIRL</p>
                        </b>
                        <p>ELABORACIÓN DE OTROS PRODUCTOS ALIMENTICIOS N.C.P.</p>
                        <br>
                        <p style="line-height: 34%;">Roma y Chile N° 1596</p>
                        <p style="line-height: 34%;">Cel.: (0983)352-111</p>
                        <p style="line-height: 34%;">Asunción - Paraguay</p>

                    </div>

                </div>
                <div style=" width:1%; float:left; display:inline-block; "></div>

                <div id='borde' style=" width:42%; float:left; display:inline-block; ">
                    <div style=" text-align:center; padding:25px; ">
                        <b>
                            <p>RUC: 80111418-7</p>
                        </b>
                        <b>
                            <h3>NOTA DE REMISIÓN</h3>
                        </b>
                        <h4>N° {{$id}}</h4>
                    </div>
                </div>
                <div class="col-12" id='datos' style="margin-top:20px">
                    <div class="col-6" style="display: inline-block; float:left; padding-top:7px;">
                        <p><b>Fecha:</b> @php echo date("d-m-Y", strtotime($fecha)); @endphp</p>
                        <p><b>Nombre o Razón Social:</b> {{$destino}}</p>
                        <p><b>Dirección:</b>{{$direccion}}</p>
                    </div>
                    <div class="col-6" style="display: inline-block; float:left; padding-top:7px;">
                        <p><b>Realizado por:</b> {{$user}}</p>
                    </div>

                </div>
            </div>


            <div class="col-12">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Nro</th>
                                <th>Lote</th>
                                <th>Fecha Elab.</th>
                                <th>Fecha Venc.</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalle as $deta)
                            <tr>
                                <td>{{$deta->codigo}}</td>
                                <td>{{$deta->descripcion}}</td>
                                <td>{{$deta->nro}}</td>
                                <td>{{$deta->op}}</td>
                                <td>{{$deta->fecha}}</td>
                                <td>{{$deta->vencimiento}}</td>
                                <td>{{$deta->cantidad}}</td>
                                <td>{{$deta->unidad}}</td>

                            </tr>
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
                            @foreach($total as $tot)
                            <tr>
                                <td>{{$tot->codigo}}</td>
                                <td>{{$tot->descripcion}}</td>
                                <td>{{$tot->cantidad}}</td>
                                <td>{{$tot->unidad}}</td>
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
                        <strong>CHOFER:</strong>___________________________________ <br>
                        <strong>ENVIO:</strong>___________________________________ <br>
                        <strong>RECIBIO:</strong>___________________________________ <br>
                        <strong>HORA RECIBIDO:</strong>___________________________________ <br>
                    </p>
                    <p class="lead" style="font-size: 15px;">Nota de Envio #{{$id}}</p>
                </div>
            </div>
        </section>
    </div>
</body>

</html>