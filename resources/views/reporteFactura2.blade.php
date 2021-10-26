<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
        body{
            font-family: arial, sans-serif;
        }

        td, th{
            font-family: arial, sans-serif;
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th{
            text-align: center;
        }

        #detalle{
            width: 100%;
        }

        #detalle tr:nth-child(even){
            background-color: #dddddd;
        }

        span{
            font-weight: bold;
        }

        #encabezado table{
            float: right;
            margin-top: -190px;
        }

        #encabezado div:nth-child(1){
            width: 60%;
        }

        #encabezado th{
            background-color: #dddddd;
        }

        h1{
            font-size: 2.5em;
        }

        #total{
            float: right;
            margin-right: 40px;
        }

        #total th{
            text-align: right;
        }

        #total td{
            text-align: left;
        }

        #total th, #total td{
            border: 0px;
        }

    </style>
</head>
<body>
    <div id='encabezado'>
        <div>
            <h1>Factura</h1>
            <h2>Wilfredo Barraza</h2>
            <p>Dirección: Cumbres de Cuscatlan, urbanizacion, Pje 6 casa #10</p>
            <p>Telefono: 2519 2529</p>
        </div>
        <div>
            <table>
                <tr>
                    <th>Numero de factura</th>
                    <th>Fecha</th>
                </tr>
                <tr>
                    <td>{{$factura[0]->idOrden}}</td>
                    <td>{{$hoy}}</td>
                </tr>
                <tr>
                    <th>Numero de cliente</th>
                    <th>Pago</th>
                </tr>
                <tr>
                    <td>{{$factura[0]->idCliente}}</td>
                    <td>Tarjeta</td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <p><span>Nombre del cliente:</span> {{$factura[0]->nombreCliente}} {{$factura[0]->apellido}}</p>
    <table id='detalle'>
        <tr>
            <th>Nombre del producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Total</th>
        </tr> 
        @php
            $cont2 = 0;
        @endphp
        @foreach($factura AS $p)
            <tr>
                <td>{{$p->nombreProducto}}</td>
                <td>${{$p->precio}}</td>
                <td>{{$p->cantidad}}</td>
                <td>{{$p->total}}</td>
                @php
                    $cont2 = $cont2 + $p->total;
                @endphp
            </tr>
        @endforeach
    </table>
    <br>
    <table id='total'>
        <tr>
            <th>Subtotal: </th>
            <td>${{$cont2}}</td>
        </tr>
    </table>
</body>
</html>