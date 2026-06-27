{{--
    Layout principal para pantallas internas del sistema
    (dashboard, clientes, facturación, y todos los módulos).

    Extiende layouts.vertical de Adminto (sidebar + topbar),
    con el sidebar en modo "hover": colapsado por defecto,
    se expande al pasar el mouse sobre él.

    Las vistas reales (dashboard, clientes, etc.) deben usar
    @section('page-content') — NO 'content', porque ese nombre
    ya lo usa este archivo para llenar el yield de layouts.vertical.
--}}
@extends('layouts.vertical', ['title' => $title ?? 'todopyme'])

@section('html_attribute')
lang="es" data-sidenav-size="sm-hover"
@endsection

@section('content')
    @yield('page-content')
@endsection