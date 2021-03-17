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
   @foreach($listado as $list)
   <div style="width: 200px; margin: 0.5%;  border: 1px solid black; float: left;display: inline-block; box-sizing: border-box; line-height: 30%">
        <div style="margin-top: 10px; margin-left: 10px; box-sizing: border-box; float: left;display: inline-block; width: 100%;">
          <div style="width: 50%; float: left;display: inline-block;">
             <p style=" font-size: 100%;"><strong>N.C.: {{$list['lote']}}</strong></p>
          </div>
          <div style="width: 50%; float: left;display: inline-block;">
             <p style=" font-size: 80%;">L: {{$list['orden']}}</p>
          </div>
          <br><br>
       
       <p style=" font-size: 80%;"><strong> {{$list['descripcion']}}</strong>  </p>
        <p style=" font-size: 80%;"><strong>CANT.:</strong> {{$list['cantidad']}} UN.</p>
        <p style=" font-size: 80%;"><strong>ELAB.:</strong> {{$list['fecha_elab']}}  </p>
        <p style=" font-size: 80%;"><strong>VTO.:</strong> {{$list['fecha_vto']}}  </p>
        </div>
        
      </div>
   @endforeach
</body>
</html>