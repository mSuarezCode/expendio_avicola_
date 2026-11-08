<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
//$routes->get('/', 'Home::index');

$routes->get('/', 'DashboardController::index');
$routes->get('/dashboard', 'DashboardController::index');
$routes->post('/pedidos/guardar', 'DashboardController::guardarPedido');

service('auth')->routes($routes);

    $routes->get('/precios/listar', 'PreciosController::listar');
    $routes->post('/precios/actualizar', 'DashboardController::actualizarPrecio');

$routes->group('precios', ['filter' => 'session'], function($routes) {
    //$routes->get('/', 'PreciosController::index');
    //$routes->post('actualizar', 'PreciosController::actualizarPrecio');
});



    $routes->get('/produccion/obtenerProveedores', 'ProduccionController::obtenerProveedores');
    $routes->get('/produccion/listarLotes', 'ProduccionController::listarLotes');
    $routes->post('/produccion/guardarLote', 'ProduccionController::guardarLote');
$routes->group('produccion', ['filter' => 'session'], function($routes) {
    //$routes->get('/', 'ProduccionController::index');
});

$routes->get('/produccion', 'DashboardController::index');
$routes->post('/produccion/guardar-lote', 'ProduccionController::guardarLote');
$routes->post('/produccion/guardar-proceso', 'ProduccionController::guardarProceso');

$routes->get('/proveedores', 'DashboardController::index');
$routes->post('/proveedores/guardar', 'DashboardController::guardarProveedor');
$routes->post('/proveedores/eliminar/(:num)', 'DashboardController::eliminarProveedor/$1');

$routes->get('/productos/json', 'DashboardController::getProductosJson');
$routes->get('/pedidos/pendientes-json', 'DashboardController::getPedidosPendientesJson');
$routes->post('/pedidos/actualizar-estado', 'DashboardController::actualizarEstadoPedido');
$routes->get('/pedidos/detalle-json/(:num)', 'DashboardController::getDetallePedidoJson/$1');
$routes->get('/pedidos/metricas-json', 'DashboardController::getResumenMetricasJson');
$routes->get('/pedidos/buscar-cliente', 'DashboardController::buscarClienteJson');

$routes->post('/catalogo/guardar', 'DashboardController::guardarProductoCatalogo');
$routes->post('/catalogo/actualizar', 'DashboardController::actualizarProductoCatalogo');
$routes->post('/catalogo/eliminar/(:num)', 'DashboardController::eliminarProductoCatalogo/$1');

$routes->post('/repartidores/guardar', 'DashboardController::guardarRepartidor');
$routes->post('/repartidores/eliminar/(:num)', 'DashboardController::eliminarRepartidor/$1');

$routes->get('/repartidores/json', 'DashboardController::getRepartidoresJson');

$routes->get('/reportes/datos-json', 'DashboardController::getReportesJson');

$routes->get('/reportes/fluctuacion-json', 'DashboardController::getFluctuacionReporteJson');

$routes->post('/produccion/guardar-despiece', 'DashboardController::guardarProcesoDespiece');

$routes->post('almacen/ajustar-stock', 'DashboardController::ajustarStock');

$routes->get('/clientes/json', 'DashboardController::getClientesJson');
$routes->post('/clientes/guardar', 'DashboardController::guardarClienteDirectorio');

// Rutas para el módulo de Cierre de Día y Gastos Operativos
$routes->post('/cierre/guardar-gasto', 'CierreController::guardarGasto');
$routes->get('/cierre/resumen-json', 'CierreController::resumenJson');
$routes->post('/cierre/procesar', 'CierreController::procesar');

$routes->post('/produccion/subir-comprobante', 'ProduccionController::subirComprobante');

$routes->post('/pedidos/verificar-transferencia', 'DashboardController::verificarTransferencia');

// ==========================================
// RUTAS PARA MÓDULO DE REPORTES Y ESTADÍSTICAS
// ==========================================
$routes->get('/reportes/datos-json', 'ReportesController::datosGeneralesJson');
$routes->get('/reportes/financieros-json', 'ReportesController::financierosJson');
$routes->get('/reportes/mermas-json', 'ReportesController::mermasJson');
$routes->get('/reportes/logistica-json', 'ReportesController::logisticaJson');
$routes->get('/reportes/historial-pedidos-json', 'ReportesController::historialPedidosJson');
$routes->get('/reportes/detalle-pedido-historico/(:num)', 'ReportesController::detallePedidoHistoricoJson/$1');

$routes->group('admin/permisos', ['filter' => 'group:superadmin'], function($routes) {
    $routes->get('/', 'PermisosController::index');
    $routes->post('asignar-grupo', 'PermisosController::asignarGrupo');
    $routes->post('gestionar-permiso', 'PermisosController::gestionarPermisoDirecto');
});

    $routes->get('/admin/permisos/usuarios-json', 'PermisosController::usuariosJson');

    $routes->post('/perfil/actualizar', 'DashboardController::actualizarPerfil');