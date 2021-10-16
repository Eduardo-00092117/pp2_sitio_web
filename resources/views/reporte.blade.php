<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    </style>
</head>
<body>
    <h2>Historial de venta</h2>
    <table>
        <tr>
            <th>Nombre del producto</th>
            <th>Precio</th>
            <th>Categoria</th>
            <th>Marca</th>
            <th>Cantidad</th>
            <th>Total</th>
        </tr> 
        @foreach($historial AS $p)
            <tr>
                <td>{{$p->nombre1}}</td>
                <td>{{$p->precio}}</td>
                <td>{{$p->nombre2}}</td>
                <td>{{$p->nombre3}}</td>
                <td>{{$p->cantidad}}</td>
                <td>{{$p->total}}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>