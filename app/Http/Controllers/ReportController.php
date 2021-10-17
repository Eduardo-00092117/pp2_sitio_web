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
}
