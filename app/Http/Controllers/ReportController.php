<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ReportController extends Controller
{

    public function generar(Request $request){
        $historial = \DB::select('SELECT hv.nombre as nombre1, hv.precio, cp.nombre as nombre2, m.nombre as nombre3, hv.cantidad, hv.total FROM historial_venta hv, orden_detalle od, categoria_producto cp, marca m
        WHERE hv.idCategoria = cp.id AND hv.idMarca = m.id AND hv.idOrden = od.id ');
        $prueba = $request->Input('prueba1');
        $view = \View::make('reporte', compact('historial'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf -> loadHTML($view);
        return $pdf->stream($prueba.'.pdf');
    }
}
