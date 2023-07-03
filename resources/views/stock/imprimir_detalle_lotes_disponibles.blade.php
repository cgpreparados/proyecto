<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CARO ICB | LOTES DISPONIBLES </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />

</head>
<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
    table{
        border: 1px solid black;
       
    }

    thead {
  background-color: #333;
  color: white;
}
tbody tr:nth-child(odd) {
  background-color: #fff;
}

tbody tr:nth-child(even) {
  background-color: #eee;
}
</style>
<body>
    <table width="100%">
        <tr>
            <td>
            <center><img src="{{ asset('assets') }}/img/logo_caro_vector.png" style=" width: 60px;height: 60px;"></i></center>
            </td>
            <td>
                <center><b>Inventario de Lotes.</b></center>
            </td>
        </tr>
    </table>
    <div class="wrapper">
        <section class="invoice">
            <br>
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <p>
                        <strong>Fecha:</strong> {{$fecha}}
                    </p>
                    <p>
                        <strong>Realizado por:</strong>___________________________________
                    </p>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table" width="100%">
                        <thead>
                            <tr align="center">
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Lote</th>
                                <th>Nro.</th>
                                <th>Fecha Elaboración</th>
                                <th>Fecha Vencimiento</th>
                                <th>Orden de Producción</th>
                                <th>Existe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listado as $deta)
                            <tr align="center">
                                <td>{{$deta->codigo}}</td>
                                <td>{{$deta->descripcion}}</td>
                                <td>{{$deta->lote}}</td>
                                <td>{{$deta->nro}}</td>
                                <td>{{$deta->fechal}}</td>
                                <td>{{$deta->fechav}}</td>
                                <td>{{$deta->op}}</td>
                                <td></td>
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
                </div>
            </div>
        </section>
    </div>
</body>

</html>