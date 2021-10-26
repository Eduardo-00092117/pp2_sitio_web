<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ReportController extends Controller
{

    public function generar(Request $request){
        $fecha1 = $request->Input('fecha1');
        $fecha2 = $request->Input('fecha2');
        if ($fecha1 == '' && $fecha2 == '') {
            $fecha1 = '-';
            $fecha2 = '-';
            $historial = \DB::select('SELECT hv.nombre as nombre1, hv.precio, cp.nombre as nombre2, m.nombre as nombre3, hv.cantidad, hv.total, o.fecha FROM historial_venta hv, orden o, categoria_producto cp, marca m WHERE hv.idCategoria = cp.id AND hv.idMarca = m.id AND hv.idOrden = o.id');
        } else{
            $historial = \DB::select('SELECT hv.nombre as nombre1, hv.precio, cp.nombre as nombre2, m.nombre as nombre3, hv.cantidad, hv.total, o.fecha FROM historial_venta hv, orden o, categoria_producto cp, marca m WHERE hv.idCategoria = cp.id AND hv.idMarca = m.id AND hv.idOrden = o.id and o.fecha BETWEEN "'.$fecha1.'" and "'.$fecha2.'"');
        }
        $view = \View::make('reporte', compact('historial', 'fecha1', 'fecha2'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf -> loadHTML($view);
        return $pdf->stream($fecha1.'.pdf');
    }

    public function factura1(Request $request){
        $recibido = $request->Input('recibido');
        $vuelto = $request->Input('vuelto');
        $id = $request->Input('orden');
        $hoy = date('d-m-Y');
        $factura = \DB::select('SELECT o.id as idOrden, p.nombre as nombreProducto, p.precio, od.cantidad, od.total, c.id as idCliente, c.nombre as nombreCliente, c.apellido FROM orden_detalle od, orden o, producto p, cliente c WHERE od.orden_id = o.id and p.id = od.producto_id and o.idCliente = c.id and od.orden_id = '.$id.'');
        $view = \View::make('reporteFactura', compact('factura', 'recibido', 'vuelto', 'hoy'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf -> loadHTML($view);
        return $pdf->stream($id.'.pdf');
    }

    public function factura2(Request $request){
        $id = $request->Input('orden');
        $hoy = date('d-m-Y');
        $factura = \DB::select('SELECT o.id as idOrden, p.nombre as nombreProducto, p.precio, od.cantidad, od.total, c.id as idCliente, c.nombre as nombreCliente, c.apellido FROM orden_detalle od, orden o, producto p, cliente c WHERE od.orden_id = o.id and p.id = od.producto_id and o.idCliente = c.id and od.orden_id = '.$id.'');
        $view = \View::make('reporteFactura2', compact('factura', 'hoy'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf -> loadHTML($view);
        return $pdf->stream($id.'.pdf');
    }
}
