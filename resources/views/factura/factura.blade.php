<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CG PREPARADOS | FACTURA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/now-ui-dashboard.css?v=1.3.0" rel="stylesheet" />
    <link href="{{ asset('assets') }}/demo/demo.css" rel="stylesheet" />
</head>
<style>
#borde {
    border-style: groove;
    border-radius: 5px;
    height: 200px;
}

#datos {
    border-style: groove;
    border-radius: 5px;
}
.white{
    color:white;
}
</style>
@php
function numletras($numero,$_moneda){
switch($_moneda)
{
    case 1:
         $_nommoneda='GUARANIES';
         break;
    case 2:
         $_nommoneda='DÓLARES';
         break;
    case 3:
         $_nommoneda='EUROS';
         break;
}

$tempnum = explode('.',$numero);
 
if ($tempnum[0] !== ""){
$numf = milmillon($tempnum[0]);
if ($numf == "UNO")
{
$numf = substr($numf, 0, -1);
}
 

$TextEnd= $_nommoneda.' ' .$numf.'.-';
}

return $TextEnd;
}

 
function unidad($numuero){
switch ($numuero)
{
case 9:
{
$numu = "NUEVE";
break;
}
case 8:
{
$numu = "OCHO";
break;
}
case 7:
{
$numu = "SIETE";
break;
}
case 6:
{
$numu = "SEIS";
break;
}
case 5:
{
$numu = "CINCO";
break;
}
case 4:
{
$numu = "CUATRO";
break;
}
case 3:
{
$numu = "TRES";
break;
}
case 2:
{
$numu = "DOS";
break;
}
case 1:
{
$numu = "UNO";
break;
}
case 0:
{
$numu = "";
break;
}
}
return $numu;
}
 
function decena($numdero){
 
if ($numdero >= 90 && $numdero <= 99)
{
$numd = "NOVENTA ";
if ($numdero > 90)
$numd = $numd."Y ".(unidad($numdero - 90));
}
else if ($numdero >= 80 && $numdero <= 89)
{
$numd = "OCHENTA ";
if ($numdero > 80)
$numd = $numd."Y ".(unidad($numdero - 80));
}
else if ($numdero >= 70 && $numdero <= 79)
{
$numd = "SETENTA ";
if ($numdero > 70)
$numd = $numd."Y ".(unidad($numdero - 70));
}
else if ($numdero >= 60 && $numdero <= 69)
{
$numd = "SESENTA ";
if ($numdero > 60)
$numd = $numd."Y ".(unidad($numdero - 60));
}
else if ($numdero >= 50 && $numdero <= 59)
{
$numd = "CINCUENTA ";
if ($numdero > 50)
$numd = $numd."Y ".(unidad($numdero - 50));
}
else if ($numdero >= 40 && $numdero <= 49)
{
$numd = "CUARENTA ";
if ($numdero > 40)
$numd = $numd."Y ".(unidad($numdero - 40));
}
else if ($numdero >= 30 && $numdero <= 39)
{
$numd = "TREINTA ";
if ($numdero > 30)
$numd = $numd."Y ".(unidad($numdero - 30));
}
else if ($numdero >= 20 && $numdero <= 29)
{
if ($numdero == 20)
$numd = "VEINTE ";
else
$numd = "VEINTI".(unidad($numdero - 20));
}
else if ($numdero >= 10 && $numdero <= 19)
{
switch ($numdero){
case 10:
{
$numd = "DIEZ ";
break;
}
case 11:
{
$numd = "ONCE ";
break;
}
case 12:
{
$numd = "DOCE ";
break;
}
case 13:
{
$numd = "TRECE ";
break;
}
case 14:
{
$numd = "CATORCE ";
break;
}
case 15:
{
$numd = "QUINCE ";
break;
}
case 16:
{
$numd = "DIECISEIS ";
break;
}
case 17:
{
$numd = "DIECISIETE ";
break;
}
case 18:
{
$numd = "DIECIOCHO ";
break;
}
case 19:
{
$numd = "DIECINUEVE ";
break;
}
}
}
else
$numd = unidad($numdero);
return $numd;
}
 
function centena($numc){
if ($numc >= 100)
{
if ($numc >= 900 && $numc <= 999)
{
$numce = "NOVECIENTOS ";
if ($numc > 900)
$numce = $numce.(decena($numc - 900));
}
else if ($numc >= 800 && $numc <= 899)
{
$numce = "OCHOCIENTOS ";
if ($numc > 800)
$numce = $numce.(decena($numc - 800));
}
else if ($numc >= 700 && $numc <= 799)
{
$numce = "SETECIENTOS ";
if ($numc > 700)
$numce = $numce.(decena($numc - 700));
}
else if ($numc >= 600 && $numc <= 699)
{
$numce = "SEISCIENTOS ";
if ($numc > 600)
$numce = $numce.(decena($numc - 600));
}
else if ($numc >= 500 && $numc <= 599)
{
$numce = "QUINIENTOS ";
if ($numc > 500)
$numce = $numce.(decena($numc - 500));
}
else if ($numc >= 400 && $numc <= 499)
{
$numce = "CUATROCIENTOS ";
if ($numc > 400)
$numce = $numce.(decena($numc - 400));
}
else if ($numc >= 300 && $numc <= 399)
{
$numce = "TRESCIENTOS ";
if ($numc > 300)
$numce = $numce.(decena($numc - 300));
}
else if ($numc >= 200 && $numc <= 299)
{
$numce = "DOSCIENTOS ";
if ($numc > 200)
$numce = $numce.(decena($numc - 200));
}
else if ($numc >= 100 && $numc <= 199)
{
if ($numc == 100)
$numce = "CIEN ";
else
$numce = "CIENTO ".(decena($numc - 100));
}
}
else
$numce = decena($numc);
 
return $numce;
}
 
function miles($nummero){
if ($nummero >= 1000 && $nummero < 2000){
$numm = "MIL ".(centena($nummero%1000));
}
if ($nummero >= 2000 && $nummero < 10000){
$numm = unidad(Floor($nummero/1000))." MIL ".(centena($nummero%1000));
}
if ($nummero < 1000)
$numm = centena($nummero);
 
return $numm;
}
 
function decmiles($numdmero){
if ($numdmero == 10000)
$numde = "DIEZ MIL";
if ($numdmero > 10000 && $numdmero < 20000){
$numde = decena(Floor($numdmero/1000))."MIL ".(centena($numdmero%1000));
}
if ($numdmero >= 20000 && $numdmero < 100000){
$numde = decena(Floor($numdmero/1000))." MIL ".(miles($numdmero%1000));
}
if ($numdmero < 10000)
$numde = miles($numdmero);
 
return $numde;
}
 
function cienmiles($numcmero){
if ($numcmero == 100000)
$num_letracm = "CIEN MIL";
if ($numcmero >= 100000 && $numcmero < 1000000){
$num_letracm = centena(Floor($numcmero/1000))." MIL ".(centena($numcmero%1000));
}
if ($numcmero < 100000)
$num_letracm = decmiles($numcmero);
return $num_letracm;
}
 
function millon($nummiero){
if ($nummiero >= 1000000 && $nummiero < 2000000){
$num_letramm = "UN MILLON ".(cienmiles($nummiero%1000000));
}
if ($nummiero >= 2000000 && $nummiero < 10000000){
$num_letramm = unidad(Floor($nummiero/1000000))." MILLONES ".(cienmiles($nummiero%1000000));
}
if ($nummiero < 1000000)
$num_letramm = cienmiles($nummiero);
 
return $num_letramm;
}
 
function decmillon($numerodm){
if ($numerodm == 10000000)
$num_letradmm = "DIEZ MILLONES";
if ($numerodm > 10000000 && $numerodm < 20000000){
$num_letradmm = decena(Floor($numerodm/1000000))."MILLONES ".(cienmiles($numerodm%1000000));
}
if ($numerodm >= 20000000 && $numerodm < 100000000){
$num_letradmm = decena(Floor($numerodm/1000000))." MILLONES ".(millon($numerodm%1000000));
}
if ($numerodm < 10000000)
$num_letradmm = millon($numerodm);
 
return $num_letradmm;
}
 
function cienmillon($numcmeros){
if ($numcmeros == 100000000)
$num_letracms = "CIEN MILLONES";
if ($numcmeros >= 100000000 && $numcmeros < 1000000000){
$num_letracms = centena(Floor($numcmeros/1000000))." MILLONES ".(millon($numcmeros%1000000));
}
if ($numcmeros < 100000000)
$num_letracms = decmillon($numcmeros);
return $num_letracms;
}
 
function milmillon($nummierod){
if ($nummierod >= 1000000000 && $nummierod < 2000000000){
$num_letrammd = "MIL ".(cienmillon($nummierod%1000000000));
}
if ($nummierod >= 2000000000 && $nummierod < 10000000000){
$num_letrammd = unidad(Floor($nummierod/1000000000))." MIL ".(cienmillon($nummierod%1000000000));
}
if ($nummierod < 1000000000)
$num_letrammd = cienmillon($nummierod);
 
return $num_letrammd;
}


@endphp
<body>
    <div class="wrapper">
        <section class="invoice">
            <div class="row" style="margin:10px;">
                <div class="col-6" id='borde'>
                    <div style="position=relative; text-align:center; margin:25px;">
                        <img src="{{ asset('assets') }}/img/logo-caro.png" style=" width: 70px;height: 70px;"></i>
                        <b>
                            <p>de Maria Carlota Gracia de Soerensen EIRL</p>
                        </b>
                        <p style="line-height: 34%;">Elaboración de otros productos alimenticios N.C.P.</p>
                        <p style="line-height: 34%;">Roma y Chile 1596</p>
                        <p style="line-height: 34%;">Telef: (0983)352-111 - Asunción - Paraguay</p>

                    </div>

                </div>
                <div class="col-1"></div>
                <div class="col-5" id='borde'>
                    <div style="position=relative; text-align:center; margin:25px;">
                        <p style="line-height: 34%;">Timbrado N° {{$timbrado}}</p>
                        <p style="line-height: 34%;">Fecha Inicio Vigencia: {{$fecha_inicio}}</p>
                        <p style="line-height: 34%;">Fecha Fin Vigencia: {{$fecha_fin}}</p>
                        <b>
                            <p style="line-height: 34%;">RUC: 80111418-7</p>
                            <h3 style="line-height: 34%;">FACTURA</h3>
                        </b>
                        <h4 style="line-height: 34%;">N° 001-001-{{$factura}}</h4>
                    </div>
                </div>
                <div class="col-12" id='datos' style="margin-top:25px;">
                    <div style="position=relative; margin:25px;">
                        <div class="col-6" style="display: inline-block; float:left;">
                            <p><b>Fecha de Emisión:</b> {{$fecha}}</p>
                            <p><b>Nombre o Razón Social:</b> {{$cliente}}</p>
                            <p><b>RUC:</b> {{$ruc}}</p>
                            <p><b>Dirección:</b>{{$direccion}}</p>
                        </div>
                        <div class="col-6" style="display: inline-block; float:left;">
                            <p><b>Condiciones de venta:</b> CONTADO ( ) CRÉDITO ( X )</p>
                            <p><b>Teléfono:</b></p>
                            <p><b>Nota de Remisión N°:</b> {{$envio}}</p>
                        </div>

                    </div>
                </div>
                <div class="col-12" id='datos' style="margin-top:25px; height:980px;">
                    <div class="table-responsive">
                        <table class="table ">
                            <tr style="text-align:center">
                                <th rowspan="2">CANTIDAD</th>
                                <th rowspan="2">CLASE DE MERCADERIA Y/O SERVICIOS</th>
                                <th rowspan="2">PRECIO UNITARIO</th>
                                <th colspan="3">VALOR DE VENTAS</th>
                            </tr>
                            <tr style="text-align:center">
                                <th>EXENTAS</th>
                                <th>5%</th>
                                <th>10%</th>
                            </tr>
                           
                           @php
                           $cant=0;
                            @endphp
                            @foreach($total as $tot)
                            @php $cant = $cant+1; @endphp
                            <tr>
                                <td style = "text-align:center;">{{number_format($tot->cantidad, 0,',','.')}}</td>
                                <td>{{$tot->descripcion}}</td>
                                <td style = "text-align:center;">{{number_format($tot->precio, 0,',','.')}}</td>
                                <td style = "text-align:center;"></td>
                                <td style = "text-align:center;"></td>
                                <td style = "text-align:center;">{{ number_format($tot->total, 0,',','.')}}</td>
                            </tr>
                            @endforeach
                            @php $cant = 15-$cant;@endphp
                            @for($i=0;$i<=$cant;$i++)
                            <tr>
                                <td style = "text-align:center;" class="white">.</td>
                                <td></td>
                                <td style = "text-align:center;"></td>
                                <td style = "text-align:center;"></td>
                                <td style = "text-align:center;"></td>
                                <td style = "text-align:center;"></td>
                            </tr>
                            @endfor
                            <tr>
                                <td colspan="3">SUB TOTAL</td>
                                <td></td>
                                <td></td>
                                <td style = "text-align:center;">{{number_format($subtotal, 0,',','.')}}.-</td>
                            </tr>
                            <tr>
                            @php $monto_letras=numletras($subtotal,1); 
                                $iva = $subtotal/11;     
                                $iva = number_format($iva, 0,',','.');   
                                if($tipo == 1){
                                    $textoFactura = 'ORIGINAL: CLIENTE';
                                }else{
                                    $textoFactura = 'DUPLICADO: ARCHIVO TRIBUTARIO';
                                }                       
                            @endphp
                                <td colspan="5">TOTAL A PAGAR: {{$monto_letras}} </td>
                                <td style = "text-align:center;">{{number_format($subtotal, 0,',','.')}}.-</td>
                            </tr>
                            <tr>
                                <td colspan="2">LIQUIDACION DE I.V.A. (5%) .-</td>
                                <td colspan="2">(10%) {{$iva}} .-</td>
                                <td colspan="2">TOTAL IVA: {{$iva}}.-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-12" style="float:left; text-align:right">
                <br>
                    <p style="line-height: 20%; font-size:70%;">{{$textoFactura}}</p>
                </div>
            </div>

        
        </section>
    </div>
</body>

</html>