{{--
    Sidenav — menú lateral principal del sistema.

    Construido sobre la estructura de Adminto (side-nav / sub-menu /
    collapse), trasplantando los 10 módulos y las rutas ya existentes
    en sidebar2.blade.php (versión AdminLTE de click-pyme).

    Se quitó el bloque de usuario (sidenav-user) que el demo original
    de Adminto trae aquí — los datos de usuario y logout ya viven en
    el topbar (layouts/partials/topbar.blade.php), evitando duplicarlos
    en dos lugares distintos.
--}}
<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="logo">
        <span class="logo-light">
            <span class="logo-lg"><img src="/img/logo_ims_color.png" alt="todopyme"></span>
            <span class="logo-sm"><img src="/img/logo_ims_color.png" alt="todopyme"></span>
        </span>
        <span class="logo-dark">
            <span class="logo-lg"><img src="/img/logo_ims_color.png" alt="todopyme"></span>
            <span class="logo-sm"><img src="/img/logo_ims_color.png" alt="todopyme"></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="ri-circle-line align-middle"></i>
    </button>

    <!-- Sidebar Menu Toggle Button -->
    <button class="sidenav-toggle-button">
        <i class="ri-menu-5-line fs-20"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="ti ti-x align-middle"></i>
    </button>

    <div data-simplebar>

        <!--- Sidenav Menu -->
        <ul class="side-nav">

            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-dashboard-line"></i></span>
                    <span class="menu-text"> Panel de control </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarVentasPedi1" aria-expanded="false" aria-controls="sidebarVentasPedi1" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-shopping-bag-3-line"></i></span>
                    <span class="menu-text"> Ventas / Pedidos </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarVentasPedi1">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('ventas.pedidos') }}" class="side-nav-link">
                            <span class="menu-text">Pedidos y Cotizaciones</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('ventas.pos') }}" class="side-nav-link">
                            <span class="menu-text">Facturación POS</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('ventas.facturacion') }}" class="side-nav-link">
                            <span class="menu-text">Facturación / NDE</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('ventas.devoluciones') }}" class="side-nav-link">
                            <span class="menu-text">Devolución / NCE</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('ventas.informe-z') }}" class="side-nav-link">
                            <span class="menu-text">Informe Z</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('ventas.inf-documentos') }}" class="side-nav-link">
                            <span class="menu-text">Informe de Documentos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('ventas.inf-productos') }}" class="side-nav-link">
                            <span class="menu-text">Informe de Productos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('clientes.index') }}" class="side-nav-link">
                            <span class="menu-text">Clientes</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('ventas.historial-clientes') }}" class="side-nav-link">
                            <span class="menu-text">Historial de Clientes</span>
                        </a>
                    </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCompras2" aria-expanded="false" aria-controls="sidebarCompras2" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-shopping-cart-2-line"></i></span>
                    <span class="menu-text"> Compras </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCompras2">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('compras.ordenes') }}" class="side-nav-link">
                            <span class="menu-text">Órdenes de Compra</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('compras.compras') }}" class="side-nav-link">
                            <span class="menu-text">Compras</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('compras.devoluciones') }}" class="side-nav-link">
                            <span class="menu-text">Devolución Compras</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('compras.inf-documentos') }}" class="side-nav-link">
                            <span class="menu-text">Informe de Documentos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('compras.creacion-proveedores') }}" class="side-nav-link">
                            <span class="menu-text">Creación de Proveedores</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('compras.historial-proveedores') }}" class="side-nav-link">
                            <span class="menu-text">Historial de Proveedores</span>
                        </a>
                    </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarInventario3" aria-expanded="false" aria-controls="sidebarInventario3" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-archive-2-line"></i></span>
                    <span class="menu-text"> Inventarios </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarInventario3">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('inventarios.entrada-salida') }}" class="side-nav-link">
                            <span class="menu-text">Entrada / Salida</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('inventarios.traslados') }}" class="side-nav-link">
                            <span class="menu-text">Traslados Entre Bodegas</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('inventarios.inf-documentos') }}" class="side-nav-link">
                            <span class="menu-text">Informe de Documentos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarInventario4" aria-expanded="false" aria-controls="sidebarInventario4" class="side-nav-link">
                            <span class="menu-text"> Informes Inventarios </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarInventario4">
                            <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('inventarios.inf-rotativo') }}" class="side-nav-link">
                                    <span class="menu-text">Informe Rotativo</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('inventarios.inf-inventarios') }}" class="side-nav-link">
                                    <span class="menu-text">Informe de Inventarios</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('inventarios.inf-kardex') }}" class="side-nav-link">
                                    <span class="menu-text">Informe de Kardex</span>
                                </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarParmetrosP5" aria-expanded="false" aria-controls="sidebarParmetrosP5" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-briefcase-4-line"></i></span>
                    <span class="menu-text"> Parámetros Productos </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarParmetrosP5">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('productos.creacion') }}" class="side-nav-link">
                            <span class="menu-text">Creación de Productos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarParmetrosP6" aria-expanded="false" aria-controls="sidebarParmetrosP6" class="side-nav-link">
                            <span class="menu-text"> Clasificación Productos </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarParmetrosP6">
                            <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('productos.tipo-productos') }}" class="side-nav-link">
                                    <span class="menu-text">Tipo de Productos</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('productos.grupos') }}" class="side-nav-link">
                                    <span class="menu-text">Grupos</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('productos.subgrupos') }}" class="side-nav-link">
                                    <span class="menu-text">Subgrupos</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('productos.lineas') }}" class="side-nav-link">
                                    <span class="menu-text">Líneas</span>
                                </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('productos.tipos-listas') }}" class="side-nav-link">
                            <span class="menu-text">Tipos de Listas</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('productos.consulta-precios') }}" class="side-nav-link">
                            <span class="menu-text">Consulta Lista Precios</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('productos.asignar-precios') }}" class="side-nav-link">
                            <span class="menu-text">Asignar Precios</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('productos.asignar-costos') }}" class="side-nav-link">
                            <span class="menu-text">Asignar Costos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('productos.recodificacion') }}" class="side-nav-link">
                            <span class="menu-text">Recodificación</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('productos.exportar') }}" class="side-nav-link">
                            <span class="menu-text">Exportar Productos</span>
                        </a>
                    </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCuentaspor7" aria-expanded="false" aria-controls="sidebarCuentaspor7" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-wallet-3-line"></i></span>
                    <span class="menu-text"> Cuentas por Cobrar </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCuentaspor7">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('cxc.recibos-caja') }}" class="side-nav-link">
                            <span class="menu-text">Recibo de Caja</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxc.notas-debito') }}" class="side-nav-link">
                            <span class="menu-text">Notas Débito Clientes</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxc.notas-credito') }}" class="side-nav-link">
                            <span class="menu-text">Notas Crédito Clientes</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxc.conceptos') }}" class="side-nav-link">
                            <span class="menu-text">Conceptos RC-Notas</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxc.inf-documentos') }}" class="side-nav-link">
                            <span class="menu-text">Informe de Documentos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxc.inf-cartera') }}" class="side-nav-link">
                            <span class="menu-text">Informe de Cartera</span>
                        </a>
                    </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCuentaspor8" aria-expanded="false" aria-controls="sidebarCuentaspor8" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-bank-card-line"></i></span>
                    <span class="menu-text"> Cuentas por Pagar </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCuentaspor8">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('cxp.comprobante-egreso') }}" class="side-nav-link">
                            <span class="menu-text">Comprobante de Egreso</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxp.notas-debito') }}" class="side-nav-link">
                            <span class="menu-text">Notas Débito Proveedores</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxp.notas-credito') }}" class="side-nav-link">
                            <span class="menu-text">Notas Crédito Proveedores</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxp.conceptos') }}" class="side-nav-link">
                            <span class="menu-text">Conceptos CE-Notas</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxp.inf-documentos') }}" class="side-nav-link">
                            <span class="menu-text">Informe de Documentos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('cxp.inf-cuentas-pagar') }}" class="side-nav-link">
                            <span class="menu-text">Informe Cuentas por Pagar</span>
                        </a>
                    </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarMovimiento9" aria-expanded="false" aria-controls="sidebarMovimiento9" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-money-dollar-circle-line"></i></span>
                    <span class="menu-text"> Movimiento de Gastos </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarMovimiento9">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('gastos.registro') }}" class="side-nav-link">
                            <span class="menu-text">Registro de Gastos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('gastos.inf-gastos') }}" class="side-nav-link">
                            <span class="menu-text">Informe de Gastos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('gastos.cuadre-caja') }}" class="side-nav-link">
                            <span class="menu-text">Cuadre de Caja</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('gastos.consulta-cuadre') }}" class="side-nav-link">
                            <span class="menu-text">Consulta Cuadre de Caja</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarMovimiento10" aria-expanded="false" aria-controls="sidebarMovimiento10" class="side-nav-link">
                            <span class="menu-text"> Parametrización </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarMovimiento10">
                            <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('gastos.creacion-gastos') }}" class="side-nav-link">
                                    <span class="menu-text">Creación de Gastos</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('gastos.clasificacion-gastos') }}" class="side-nav-link">
                                    <span class="menu-text">Clasificación Gastos</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('gastos.asociacion-sedes') }}" class="side-nav-link">
                                    <span class="menu-text">Asociación Gastos-Sedes</span>
                                </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarContabilid11" aria-expanded="false" aria-controls="sidebarContabilid11" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-bar-chart-2-line"></i></span>
                    <span class="menu-text"> Contabilidad </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarContabilid11">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('contabilidad.comprobantes') }}" class="side-nav-link">
                            <span class="menu-text">Registro de Comprobantes</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarContabilid12" aria-expanded="false" aria-controls="sidebarContabilid12" class="side-nav-link">
                            <span class="menu-text"> Parámetros </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarContabilid12">
                            <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.puc') }}" class="side-nav-link">
                                    <span class="menu-text">Registro PUC</span>
                                </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarContabilid13" aria-expanded="false" aria-controls="sidebarContabilid13" class="side-nav-link">
                            <span class="menu-text"> Informes Básicos </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarContabilid13">
                            <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.inf-balances') }}" class="side-nav-link">
                                    <span class="menu-text">Balances y Est. Financieros</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.inf-libro-auxiliar') }}" class="side-nav-link">
                                    <span class="menu-text">Libro Auxiliar</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.inf-libros-oficiales') }}" class="side-nav-link">
                                    <span class="menu-text">Libros Oficiales</span>
                                </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarContabilid14" aria-expanded="false" aria-controls="sidebarContabilid14" class="side-nav-link">
                            <span class="menu-text"> Informes Complementarios </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarContabilid14">
                            <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.inf-docing') }}" class="side-nav-link">
                                    <span class="menu-text">Documentos Ingresados</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.inf-aux-terceros') }}" class="side-nav-link">
                                    <span class="menu-text">Libro Auxiliar Terceros</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.inf-detalle-terceros') }}" class="side-nav-link">
                                    <span class="menu-text">Detalle de Terceros</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.inf-certificados') }}" class="side-nav-link">
                                    <span class="menu-text">Certificados de Retención</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.medios-magneticos') }}" class="side-nav-link">
                                    <span class="menu-text">Medios Magnéticos</span>
                                </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarContabilid15" aria-expanded="false" aria-controls="sidebarContabilid15" class="side-nav-link">
                            <span class="menu-text"> Utilidades Contables </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarContabilid15">
                            <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.actualiza-acumulados') }}" class="side-nav-link">
                                    <span class="menu-text">Actualización Acumulados</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.traslado-cuentas') }}" class="side-nav-link">
                                    <span class="menu-text">Traslado de Cuentas</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.copia-comprobantes') }}" class="side-nav-link">
                                    <span class="menu-text">Copia de Comprobantes</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.numeracion-libros') }}" class="side-nav-link">
                                    <span class="menu-text">Numeración de Libros</span>
                                </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarContabilid16" aria-expanded="false" aria-controls="sidebarContabilid16" class="side-nav-link">
                            <span class="menu-text"> Procesos Especiales </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarContabilid16">
                            <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.traslado-saldos') }}" class="side-nav-link">
                                    <span class="menu-text">Traslado de Saldos</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('contabilidad.cierre-anual') }}" class="side-nav-link">
                                    <span class="menu-text">Cierre Fin de Año</span>
                                </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('contabilidad.terceros') }}" class="side-nav-link">
                            <span class="menu-text">Creación de Tercero</span>
                        </a>
                    </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarNmina17" aria-expanded="false" aria-controls="sidebarNmina17" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-team-line"></i></span>
                    <span class="menu-text"> Nómina </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarNmina17">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('nomina.ficha-empleados') }}" class="side-nav-link">
                            <span class="menu-text">Ficha de Empleados</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('nomina.contratos') }}" class="side-nav-link">
                            <span class="menu-text">Contratos</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('nomina.comprobantes-pago') }}" class="side-nav-link">
                            <span class="menu-text">Comprobantes de Pago</span>
                        </a>
                    </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarRecepcinFa18" aria-expanded="false" aria-controls="sidebarRecepcinFa18" class="side-nav-link">
                    <span class="menu-icon"><i class="ri-file-text-line"></i></span>
                    <span class="menu-text"> Recepción Facturas </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarRecepcinFa18">
                    <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('radian.recepcion') }}" class="side-nav-link">
                            <span class="menu-text">Recepción de Facturas</span>
                        </a>
                    </li>
                    </ul>
                </div>
            </li>
        </ul>
        <!-- End Sidenav Menu -->

        <div class="clearfix"></div>

    </div>
</div>
<!-- Sidenav Menu End -->