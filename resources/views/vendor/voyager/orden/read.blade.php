@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> {{ __('voyager::generic.viewing') }} {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} &nbsp;

        @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.edit') }}</span>
            </a>
        @endcan
        @can('delete', $dataTypeContent)
            @if($isSoftDeleted)
                <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                </a>
            @else
                <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
                </a>
            @endif
        @endcan
        @can('browse', $dataTypeContent)
        <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="btn btn-warning">
            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.return_to_list') }}</span>
        </a>
        @endcan
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <!-- form start -->
                    @foreach($dataType->readRows as $row)
                        @php
                        if ($dataTypeContent->{$row->field.'_read'}) {
                            $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_read'};
                        }
                        @endphp
                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title">{{ $row->getTranslatedAttribute('display_name') }}</h3>
                        </div>

                        <div class="panel-body" style="padding-top:0;">
                            @if (isset($row->details->view))
                                @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => 'read', 'view' => 'read', 'options' => $row->details])
                            @elseif($row->type == "image")
                                <img class="img-responsive"
                                     src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field}) }}">
                            @elseif($row->type == 'multiple_images')
                                @if(json_decode($dataTypeContent->{$row->field}))
                                    @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                        <img class="img-responsive"
                                             src="{{ filter_var($file, FILTER_VALIDATE_URL) ? $file : Voyager::image($file) }}">
                                    @endforeach
                                @else
                                    <img class="img-responsive"
                                         src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field}) }}">
                                @endif
                            @elseif($row->type == 'relationship')
                                 @include('voyager::formfields.relationship', ['view' => 'read', 'options' => $row->details])
                            @elseif($row->type == 'select_dropdown' && property_exists($row->details, 'options') &&
                                    !empty($row->details->options->{$dataTypeContent->{$row->field}})
                            )
                                <?php echo $row->details->options->{$dataTypeContent->{$row->field}};?>
                            @elseif($row->type == 'select_multiple')
                                @if(property_exists($row->details, 'relationship'))

                                    @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                        {{ $item->{$row->field}  }}
                                    @endforeach

                                @elseif(property_exists($row->details, 'options'))
                                    @if (!empty(json_decode($dataTypeContent->{$row->field})))
                                        @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                            @if (@$row->details->options->{$item})
                                                {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                            @endif
                                        @endforeach
                                    @else
                                        {{ __('voyager::generic.none') }}
                                    @endif
                                @endif
                            @elseif($row->type == 'date' || $row->type == 'timestamp')
                                @if ( property_exists($row->details, 'format') && !is_null($dataTypeContent->{$row->field}) )
                                    {{ \Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($row->details->format) }}
                                @else
                                    {{ $dataTypeContent->{$row->field} }}
                                @endif
                            @elseif($row->type == 'checkbox')
                                @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                    @if($dataTypeContent->{$row->field})
                                    <span class="label label-info">{{ $row->details->on }}</span>
                                    @else
                                    <span class="label label-primary">{{ $row->details->off }}</span>
                                    @endif
                                @else
                                {{ $dataTypeContent->{$row->field} }}
                                @endif
                            @elseif($row->type == 'color')
                                <span class="badge badge-lg" style="background-color: {{ $dataTypeContent->{$row->field} }}">{{ $dataTypeContent->{$row->field} }}</span>
                            @elseif($row->type == 'coordinates')
                                @include('voyager::partials.coordinates')
                            @elseif($row->type == 'rich_text_box')
                                @include('voyager::multilingual.input-hidden-bread-read')
                                {!! $dataTypeContent->{$row->field} !!}
                            @elseif($row->type == 'file')
                                @if(json_decode($dataTypeContent->{$row->field}))
                                    @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                        <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}">
                                            {{ $file->original_name ?: '' }}
                                        </a>
                                        <br/>
                                    @endforeach
                                @else
                                    <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($row->field) ?: '' }}">
                                        {{ __('voyager::generic.download') }}
                                    </a>
                                @endif
                            @else
                                @include('voyager::multilingual.input-hidden-bread-read')
                                <p>{{ $dataTypeContent->{$row->field} }}</p>
                            @endif
                        </div><!-- panel-body -->
                        @if(!$loop->last)
                            <hr style="margin:0;">
                        @endif
                    @endforeach
                    <div style='padding: 30px;'>
                        <h1 style='text-align: center;'>Detalle de la orden</h1>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Precio Unitario</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $cont2 = 0;
                            @endphp
                            @foreach($producto as $pos)
                                <tr>
                                    <th scope="row">{{$pos->nombre}}</th>
                                    <td>{{$pos->descripcion}}</td>
                                    <td>${{$pos->pivot->costo_unitario}}</td>
                                    <td>{{$pos->pivot->cantidad}}</td>
                                    <td>${{$pos->pivot->total}}</td>
                                    @php
                                        $cont2 = $pos->pivot->total+$cont2;
                                    @endphp
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table style="margin-right: 7%; display: flex; flex-direction: row; justify-content: flex-end" >
                            <tr>
                                <td style="text-align: right; font-weight: bold;padding-right: 10px">Total a pagar: </td>
                                <td>$ <span id="cont">{{$cont2}}</span></td>
                            </tr>
                        </table>
                        <style>
                            #efectivo, #tarjeta{
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                            }
                            #tarjeta{
                                display: none;
                            }
                        </style>
                        <div>
                            <h1 style='text-align:center; display:block;'>M??todo de pago</h1>
                            <br>
                            <div style='text-align:center;'>
                                <input type="Radio" name="colorin" id='prueba1' checked> Efectivo 
                                <input type="Radio" name="colorin" id='prueba2' style='margin-left: 20px;'> Tarjeta de credito/debito
                            </div>
                            <br>
                            <div id='efectivo'>
                                <form action="/factura1" method="get" target="_blank">
                                    <div class='row'>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">Recibido</span>
                                            <input type="text" name='recibido' id='recibido' class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                        <br>
                                        <div>
                                            <label for="">Vuelto</label>
                                            <p id='vuelto' name='vuelto'>$0 </p>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input style='display:none; float: right;' type="text" id='vuelto2' name='vuelto' class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                        <div class="input-group mb-3">
                                            <input style='display:none; float: right;' value='{{$id}}' type="text" id='orden' name='orden' class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                        <br>
                                        <input class="btn btn-success" type="submit" value="Generar factura">
                                    </div>
                                </form>
                            </div>
                            <div id='tarjeta'>
                                <form action="/factura2" method="get" target="_blank">
                                    <div class='row'>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">Nombre de la tarjeta</span>
                                            <input type="text" id='nombre' name='nombre' class="form-control" placeholder='Eduardo L??pez' aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                        <br>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">N??mero de tarjeta</span>
                                            <input type="text" id='numero' name='numero' class="form-control" placeholder='XXXX-XXXX-XXXX-XXXX' aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                        <br>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">Fecha de expiraci??n</span>
                                            <input type="text" id='fecha' name='fecha' class="form-control" placeholder='dd/yy' aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                        <br>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">Codigo</span>
                                            <input type="text" id='codigo' name='pin' class="form-control" placeholder='345' aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                        <div class="input-group mb-3">
                                            <input style='display:none; float: right;' value='{{$id}}' type="text" id='orden' name='orden' class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                        <br>
                                        <input class="btn btn-success" type="submit" value="Generar factura">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script>
            var entrada = document.getElementById('recibido');
            entrada.addEventListener('keyup',botones);
            var salida = document.getElementById('vuelto');
            var salida2 = document.getElementById('vuelto2');
            var cancelar = document.getElementById('cont').textContent;
            var cont = 0;
            function botones () {
                var valor = document.getElementById("recibido").value;
                if(valor.trim() == ''){
                    salida.innerHTML='$0';
                } else{
                    var tot = parseFloat(valor) - parseFloat(cancelar);
                    salida.innerHTML='$'+tot;
                    salida2.value = tot;
                }
            }
            var check = document.getElementById('prueba1');
            check.addEventListener('click',cambiaColor);
            var check2 = document.getElementById('prueba2');
            check2.addEventListener('click',cambiaColor);
            function cambiaColor() { 
                if(document.getElementById('prueba1').checked){
                    document.getElementById('efectivo').style.display = "flex";
                    document.getElementById('tarjeta').style.display = "none";
                    document.getElementById('nombre').value = '';
                    document.getElementById('numero').value = '';
                    document.getElementById('fecha').value = '';
                    document.getElementById('codigo').value = '';
                } else if(document.getElementById('prueba2').checked){
                    document.getElementById('efectivo').style.display = "none";
                    document.getElementById('recibido').value = '';
                    salida.innerHTML='$0';
                    document.getElementById('tarjeta').style.display = "flex";
                }
            } 
        </script>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('voyager::generic.delete_confirm') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    @if ($isModelTranslatable)
        <script>
            $(document).ready(function () {
                $('.side-body').multilingual();
            });
        </script>
    @endif
    <script>
        var deleteFormAction;
        $('.delete').on('click', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {
                // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });

    </script>
@stop
