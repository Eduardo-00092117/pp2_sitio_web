<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de ventas</title>
    <style>
        table{
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th{
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even){
            background-color: #dddddd;
        }

        span{
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Reporte de ventas</h1>
    <p>Reporte generado entre las fechas: <span>{{$fecha1}}</span> y <span>{{$fecha2}}</span></p>
    <table>
        <tr>
            <th>Nombre del producto</th>
            <th>Precio</th>
            <th>Categoria/marca</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Fecha de la orden</th>
        </tr> 
        @foreach($historial AS $p)
            <tr>
                <td>{{$p->nombre1}}</td>
                <td>${{$p->precio}}</td>
                <td>{{$p->nombre2}} / {{$p->nombre3}}</td>
                <td>{{$p->cantidad}}</td>
                <td>${{$p->total}}</td>
                <td>{{$p->fecha}}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>