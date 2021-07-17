<div class="sidebar" data-color="grey">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
-->
    <div class="logo">
        <a href="{{ route('home') }}" class="simple-text logo-mini">
            {{ __('CG') }}
        </a>
        <a href="{{ route('home') }}" class="simple-text logo-normal">
            {{ __('Preparados') }}
        </a>
    </div>
    <div class="sidebar-wrapper" id="sidebar-wrapper">
        <ul class="nav">
            <li class="@if ($activePage == 'home') active @endif">
                <a href="{{ route('home') }}">
                    <i class="now-ui-icons design_app"></i>
                    <p>{{ __('Inicio') }}</p>
                </a>
            </li>
            @if((auth()->user()->type) == 1 || (auth()->user()->type) == 2 )
            <li>
                <a data-toggle="collapse" href="#laravelExamples">
                    <i class="now-ui-icons ui-1_settings-gear-63"></i>
                    <p>
                        {{ __("Producción") }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="laravelExamples">
                    <ul class="nav">
                        <li class="@if ($activePage == 'nueva_orden') active @endif">
                            <a href="{{ route('nueva_orden') }}">
                                <i class="now-ui-icons ui-1_simple-add"></i>
                                <p> {{ __("Nueva Orden") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'orden_proceso') active @endif">
                            <a href="{{ route('orden_proceso') }}">
                                <i class="now-ui-icons design_bullet-list-67"></i>
                                <p> {{ __("Ordenes en Proceso") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'orden_impresiones') active @endif">
                            <a href="{{ route('orden_impresiones') }}">
                                <i class="now-ui-icons files_single-copy-04"></i>
                                <p> {{ __("Impresiones") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'rutas') active @endif">
                            <a href="{{ route('rutas') }}">
                                <i class="now-ui-icons location_map-big"></i>
                                <p> {{ __("Rutas") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'lotes') active @endif">
                            <a href="{{ route('lotes') }}">
                                <i class="now-ui-icons shopping_box"></i>
                                <p> {{ __("Lotes") }} </p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            @if((auth()->user()->type) == 1 || (auth()->user()->type) == 2 || (auth()->user()->type) == 3 || (auth()->user()->type) == 4 )
            <li>
                <a data-toggle="collapse" href="#cgStocks">
                    <i class="now-ui-icons design_app"></i>
                    <p>
                        {{ __("Stock") }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="cgStocks">
                    <ul class="nav">
                        <li class="@if ($activePage == 'stock_materiales') active @endif">
                            <a href="{{ route('stock_materiales') }}">
                                <i class="now-ui-icons design-2_ruler-pencil"></i>
                                <p> {{ __("Stock Materiales") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'nueva_orden') active @endif">
                            <a href="{{ route('stock_lotes') }}">
                                <i class="now-ui-icons files_box"></i>
                                <p> {{ __("Stock Lotes") }} </p>
                            </a>
                        </li>
                        @if((auth()->user()->type) == 4 || (auth()->user()->type) == 1)
                        <li>
                            <a data-toggle="collapse" href="#cgCarga">
                                <i class="now-ui-icons arrows-1_share-66"></i>
                                <p> {{ __("Cargar Inventario") }} <b class="caret"></b></p>
                            </a>
                            <div class="collapse" id="cgCarga">
                                <ul class="nav">
                                    <li class="@if ($activePage == 'inventario_materiales') active @endif">
                                        <a href="{{ route('inventario_materiales') }}">
                                            <i class="now-ui-icons design-2_ruler-pencil"></i>
                                            <p> {{ __("Inventario Materiales") }} </p>
                                        </a>
                                    </li>
                                    <li class="@if ($activePage == 'inventario_lotes') active @endif">
                                        <a href="{{ route('inventario_lotes') }}">
                                            <i class="now-ui-icons files_box"></i>
                                            <p> {{ __("Inventario Lotes") }} </p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="@if ($activePage == 'inventario_impresiones') active @endif">
                            <a href="{{ route('inventario_impresiones') }}">
                                <i class="now-ui-icons files_single-copy-04"></i>
                                <p> {{ __("Inventario Impresiones") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'movimiento_materiales') active @endif">
                            <a href="{{ route('movimiento_materiales') }}">
                                <i class="now-ui-icons arrows-1_refresh-69"></i>
                                <p> {{ __("Movimiento de Materiales") }} </p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if((auth()->user()->type) == 1 || (auth()->user()->type) == 2 || (auth()->user()->type) == 3 || (auth()->user()->type) == 4 )
            <li class="@if ($activePage == 'productos') active @endif">
                <a href="{{ route('productos') }}">
                    <i class="now-ui-icons shopping_tag-content"></i>
                    <p> {{ __("Productos") }} </p>
                </a>
            </li>
            @endif
            @if((auth()->user()->type) == 1 || (auth()->user()->type) == 3 || (auth()->user()->type) == 5 )
            <li>
                <a data-toggle="collapse" href="#cgCompras">
                    <i class="now-ui-icons shopping_cart-simple"></i>
                    <p>
                        {{ __("Compras") }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="cgCompras">
                    <ul class="nav">
                        <li class="@if ($activePage == 'nueva_compra') active @endif">
                            <a href="{{ route('nueva_compra') }}">
                                <i class="now-ui-icons shopping_basket"></i>
                                <p> {{ __("Nueva Compra") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'impresiones_compra') active @endif">
                            <a href="{{ route('impresiones_compra') }}">
                                <i class="now-ui-icons education_paper"></i>
                                <p> {{ __("Impresiones") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'proveedores') active @endif">
                            <a href="{{ route('proveedores') }}">
                                <i class="now-ui-icons shopping_shop"></i>
                                <p> {{ __("Proveedores") }} </p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            @if((auth()->user()->type) == 1 || (auth()->user()->type) == 4 )
            <li>
                <a data-toggle="collapse" href="#cgEnvios">
                    <i class="now-ui-icons shopping_delivery-fast"></i>
                    <p>
                        {{ __("Envios") }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="cgEnvios">
                    <ul class="nav">
                        <li class="@if ($activePage == 'nuevo_envio') active @endif">
                            <a href="{{ route('nuevo_envio') }}">
                                <i class="now-ui-icons transportation_bus-front-12"></i>
                                <p> {{ __("Nuevo Envio") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'envios_impresiones') active @endif">
                            <a href="{{ route('envios_impresiones') }}">
                                <i class="now-ui-icons education_paper"></i>
                                <p> {{ __("Impresiones") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'clientes') active @endif">
                            <a href="{{ route('clientes') }}">
                                <i class="now-ui-icons users_circle-08"></i>
                                <p> {{ __("Clientes") }} </p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            @if((auth()->user()->type) == 1 || (auth()->user()->type) == 5 )
            <li class="@if ($activePage == 'lista_facturas') active @endif">
                <a href="{{ route('lista_facturas') }}">
                    <i class="now-ui-icons ui-1_send"></i>
                    <p> {{ __("Facturas") }} </p>
                </a>
            </li>
            @endif
            @if((auth()->user()->type) == 1 || (auth()->user()->type) == 5 )
            <li>
                <a data-toggle="collapse" href="#cgCostos">
                    <i class="now-ui-icons business_money-coins"></i>
                    <p>
                        {{ __("Costos") }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="cgCostos">
                    <ul class="nav">
                        <li class="@if ($activePage == 'cargar_costos') active @endif">
                            <a href="{{ route('cargar_costos') }}">
                                <i class="now-ui-icons ui-2_settings-90"></i>
                                <p> {{ __("Cargar Costos") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'consultar_costos') active @endif">
                            <a href="{{ route('consultar_costos') }}">
                                <i class="now-ui-icons design_bullet-list-67"></i>
                                <p> {{ __("Ver Cargas del periodo") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'productos_costos') active @endif">
                            <a href="{{ route('productos_costos') }}">
                                <i class="now-ui-icons business_chart-bar-32"></i>
                                <p> {{ __("Costos por Producto") }} </p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            <!--li class="@if ($activePage == 'icons') active @endif">
                <a href="{{ route('page.index','icons') }}">
                    <i class="now-ui-icons education_atom"></i>
                    <p>{{ __('Icons') }}</p>
                </a>
            </li-->
            <!--li class="@if ($activePage == 'maps') active @endif">
                <a href="{{ route('page.index','maps') }}">
                    <i class="now-ui-icons location_map-big"></i>
                    <p>{{ __('Maps') }}</p>
                </a>
            </li>
            <li class=" @if ($activePage == 'notifications') active @endif">
                <a href="{{ route('page.index','notifications') }}">
                    <i class="now-ui-icons ui-1_bell-53"></i>
                    <p>{{ __('Notifications') }}</p>
                </a>
            </li>
            <li class=" @if ($activePage == 'table') active @endif">
                <a href="{{ route('page.index','table') }}">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>{{ __('Table List') }}</p>
                </a>
            </li>
            <li class="@if ($activePage == 'typography') active @endif">
                <a href="{{ route('page.index','typography') }}">
                    <i class="now-ui-icons text_caps-small"></i>
                    <p>{{ __('Typography') }}</p>
                </a>
            </li-->
            <!--li class = "">
        <a href="{{ route('page.index','upgrade') }}" class="bg-info">
          <i class="now-ui-icons arrows-1_cloud-download-93"></i>
          <p>{{ __('Upgrade to PRO') }}</p>
        </a>
      </li-->
        </ul>
    </div>
</div>