<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expendio Avícola - Panel Operativo (Mockup)</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Navbar Principal -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-store text-warning me-2"></i> Expendio Avícola UX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="cambiarVista('produccion')"><i class="fa-solid fa-feather me-1"></i> Producción / Lotes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('precios')"><i class="fa-solid fa-tags me-1"></i> Precios Diarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('pedidos')"><i class="fa-solid fa-receipt me-1"></i> Pedidos (Mostrador)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('reportes', this)"><i class="fa-solid fa-chart-line me-1"></i> Reportes y Análisis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('proveedores', this)"><i class="fa-solid fa-truck-field me-1"></i> Proveedores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('procesamiento', this)"><i class="fa-solid fa-scale-balanced me-1"></i> Procesamiento y Mermas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">

        <!-- VISTA 1: PRODUCCIÓN Y LOTES -->
        <div id="vista-produccion" class="vista-secccion">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-feather text-success me-2"></i> Recepción y Entrada de Lotes</h2>
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoLote">
                    <i class="fa-solid fa-plus me-1"></i> Registrar Nuevo Lote
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th># Lote</th>
                                    <th>Proveedor</th>
                                    <th>Tipo Ave</th>
                                    <th>Piezas</th>
                                    <th>Peso Total (Kg)</th>
                                    <th>Costo x Pza</th>
                                    <th>Fecha</th>
                                    <th>Comprobante</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaLotesMock">
                                <?php if (!empty($lotes)): ?>
                                    <?php foreach ($lotes as $l): ?>
                                        <tr>
                                            <td><span class="badge bg-secondary">#<?= $l['id'] ?></span></td>
                                            <td class="fw-bold">Proveedor ID #<?= $l['proveedor_id'] ?></td>
                                            <td><span class="badge bg-<?= $l['tipo_ave'] === 'pollo' ? 'warning text-dark' : 'danger' ?>"><?= strtoupper($l['tipo_ave']) ?></span></td>
                                            <td><?= $l['piezas'] ?> aves</td>
                                            <td><?= number_format($l['peso'], 2) ?> kg</td>
                                            <td class="text-success fw-bold">$<?= number_format($l['costo_unitario'], 2) ?></td>
                                            <td><?= $l['fecha_recepcion'] ?></td>
                                            <td class="text-center">
                                                <?php if (!empty($l['comprobante'])): ?>
                                                    <a href="<?= base_url('uploads/lotes/' . $l['comprobante']) ?>" target="_blank" class="badge bg-success text-decoration-none">
                                                        <i class="fa-solid fa-file-pdf me-1"></i> Ver Archivo
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Sin archivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <!-- AQUÍ ESTABA EL ERROR: Faltaban las etiquetas <td> -->
                                            <td><?= esc($l['observaciones']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="9" class="text-center text-muted">No hay lotes registrados en la base de datos.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISTA 2: PRECIOS DIARIOS -->
        <div id="vista-precios" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-tags text-warning me-2"></i> Control de Precios Diarios</h2>
                <span class="badge bg-primary fs-6">Fecha: Hoy</span>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th>Unidad</th>
                                    <th>Precio Actual</th>
                                    <th>Nuevo Precio</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaPreciosMock">
                                <!-- Datos inyectados por JS local -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISTA 3: PEDIDOS -->
        <div id="vista-pedidos" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-receipt text-primary me-2"></i> Pedidos, Mostrador y Reparto</h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalArmarViaje" onclick="cargarPedidosParaViaje()">
                        <i class="fa-solid fa-route me-1"></i> Agrupar y Crear Viaje de Reparto
                    </button>
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoPedido">
                        <i class="fa-solid fa-cart-plus me-1"></i> Registrar Nuevo Pedido
                    </button>
                </div>
            </div>

            <!-- Filtros por Estado -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card bg-warning text-dark border-0 shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold">Pendientes / Producción</h6>
                                <h3 class="mb-0 fw-bold" id="cntPendientes">1</h3>
                            </div>
                            <i class="fa-solid fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white border-0 shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold">En Camino (Reparto)</h6>
                                <h3 class="mb-0 fw-bold" id="cntCamino">1</h3>
                            </div>
                            <i class="fa-solid fa-motorcycle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white border-0 shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold">Entregados Hoy</h6>
                                <h3 class="mb-0 fw-bold" id="cntEntregados">1</h3>
                            </div>
                            <i class="fa-solid fa-circle-check fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-dark text-white border-0 shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold">Venta Total Hoy</h6>
                                <h3 class="mb-0 fw-bold" id="totalVentaHoy">$790.00</h3>
                            </div>
                            <i class="fa-solid fa-dollar-sign fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Pedidos -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th># Folio</th>
                                    <th>Cliente / Teléfono</th>
                                    <th>Tipo Entrega</th>
                                    <th>Dirección / Colonia</th>
                                    <th>Repartidor</th>
                                    <th>Total ($)</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaPedidosMock">
                                <!-- Carga vía JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estilos para impresión directa vía Miniprinter Térmica -->
        <style>
            @media print {
                body * {
                    visibility: hidden !important;
                }
                #ticketTermico, #ticketTermico * {
                    visibility: visible !important;
                }
                #ticketTermico {
                    position: absolute !important;
                    left: 0 !important;
                    top: 0 !important;
                    width: 80mm !important; /* Ajuste automático para impresora de tickets */
                    padding: 0 !important;
                    margin: 0 !important;
                    border: none !important;
                    box-shadow: none !important;
                }
            }
        </style>

        <!-- VISTA 4: REPORTES Y ESTADÍSTICAS -->
        <div id="vista-reportes" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-chart-pie text-danger me-2"></i> Reportes y Estadísticas de Negocio</h2>
                <div class="d-flex gap-2">
                    <input type="date" class="form-control form-control-sm" id="repFechaInicio" value="2026-10-01">
                    <input type="date" class="form-control form-control-sm" id="repFechaFin" value="2026-10-19">
                    <button class="btn btn-sm btn-dark" onclick="filtrarReportes()"><i class="fa-solid fa-filter me-1"></i> Filtrar</button>
                </div>
            </div>

            <!-- Pestañas internas de Reportes -->
            <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark" id="tab-fluctuacion" data-bs-toggle="tab" data-bs-target="#content-fluctuacion" type="button">
                        <i class="fa-solid fa-arrow-trend-up text-success me-1"></i> Fluctuación Compra vs Venta
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="tab-merma" data-bs-toggle="tab" data-bs-target="#content-merma" type="button">
                        <i class="fa-solid fa-scale-balanced text-warning me-1"></i> Volumen de Merma y Rendimiento
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="tab-rutas" data-bs-toggle="tab" data-bs-target="#content-rutas" type="button">
                        <i class="fa-solid fa-map-location-dot text-primary me-1"></i> Zonas para Campañas (Rutas)
                    </button>
                </li>
            </ul>

            <div class="tab-content card shadow-sm border-0 p-4 bg-white" id="reportTabsContent">
                
                <!-- CONTENIDO 1: FLUCTUACIÓN -->
                <div class="tab-pane fade show active" id="content-fluctuacion" role="tabpanel">
                    <h5 class="fw-bold mb-3">Historial de Costos de Adquisición vs Precios de Salida</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto / Lote</th>
                                    <th>Costo Promedio Compra</th>
                                    <th>Precio Venta Actual</th>
                                    <th>Margen Estimado</th>
                                    <th>Tendencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">Pollo Entero (Lote #1)</td>
                                    <td>$90.00 / pza</td>
                                    <td>$150.00 / pza</td>
                                    <td class="text-success fw-bold">+$60.00 (40%)</td>
                                    <td><i class="fa-solid fa-caret-up text-success fs-5"></i> Estable</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Gallina (Lote #2)</td>
                                    <td>$150.00 / pza</td>
                                    <td>$230.00 / pza</td>
                                    <td class="text-success fw-bold">+$80.00 (34%)</td>
                                    <td><i class="fa-solid fa-caret-up text-success fs-5"></i> Alza</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CONTENIDO 2: MERMA Y RENDIMIENTO -->
                <div class="tab-pane fade" id="content-merma" role="tabpanel">
                    <h5 class="fw-bold mb-3">Análisis de Rendimiento de Materia Prima en Procesamiento</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <small class="text-muted fw-bold">Peso Total Procesado</small>
                                <h4 class="text-dark fw-bold mb-0">115.50 Kg</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <small class="text-muted fw-bold">Producto Útil Obtenido</small>
                                <h4 class="text-success fw-bold mb-0">98.20 Kg (85%)</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <small class="text-muted fw-bold">Merma Total (Vísceras/Pluma/Deshidratación)</small>
                                <h4 class="text-danger fw-bold mb-0">17.30 Kg (15%)</h4>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted"><i class="fa-solid fa-lightbulb text-warning me-1"></i> El sistema calculará estos porcentajes automáticamente cada vez que el área de procesamiento registre el pesaje posterior al corte de los lotes.</p>
                </div>

                <!-- CONTENIDO 3: ZONAS PARA CAMPAÑAS -->
                <div class="tab-pane fade" id="content-rutas" role="tabpanel">
                    <h5 class="fw-bold mb-3">Concentrado de Pedidos por Colonia (Oportunidad de Expansión)</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Colonia / Sector</th>
                                    <th>Total Pedidos del Mes</th>
                                    <th>Volumen de Venta</th>
                                    <th>Estrategia Recomendada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold"><i class="fa-solid fa-location-dot text-danger me-2"></i> Col. Centro</td>
                                    <td>45 pedidos</td>
                                    <td>$14,500.00</td>
                                    <td><span class="badge bg-success">Zona Fuerte - Mantener presencia</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="fa-solid fa-location-dot text-warning me-2"></i> Col. Atasta</td>
                                    <td>18 pedidos</td>
                                    <td>$5,200.00</td>
                                    <td><span class="badge bg-primary">Zona de Oportunidad - Enviar Volantes</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="fa-solid fa-location-dot text-secondary me-2"></i> Col. Gaviotas</td>
                                    <td>4 pedidos</td>
                                    <td>$1,100.00</td>
                                    <td><span class="badge bg-warning text-dark">Baja Cobertura - Campaña en Redes</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- VISTA 5: PROVEEDORES -->
        <div id="vista-proveedores" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-truck-field text-secondary me-2"></i> Catálogo de Proveedores</h2>
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor">
                    <i class="fa-solid fa-user-plus me-1"></i> Registrar Proveedor
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th># ID</th>
                                    <th>Empresa / Granja</th>
                                    <th>Contacto Principal</th>
                                    <th>Teléfono</th>
                                    <th>Fecha de Registro</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaProveedoresMock">
                                <?php if (!empty($proveedores)): ?>
                                    <?php foreach ($proveedores as $p): ?>
                                        <tr>
                                            <td><span class="badge bg-secondary">#<?= $p['id'] ?></span></td>
                                            <td class="fw-bold"><?= esc($p['nombre_empresa']) ?></td>
                                            <td><?= esc($p['contacto'] ?? '-') ?></td>
                                            <td><i class="fa-solid fa-phone text-success me-1"></i><?= esc($p['telefono']) ?></td>
                                            <td><?= $p['created_at'] ?></td>
                                            <td class="text-center">
                                                <a href="<?= base_url('/proveedores/eliminar/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Deseas eliminar este proveedor?');">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center text-muted">No hay proveedores registrados.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISTA 6: PROCESAMIENTO Y MERMAS -->
        <div id="vista-procesamiento" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="fa-solid fa-scale-balanced text-warning me-2"></i> Transformación y Rendimiento de Lotes</h3>
                <button class="btn btn-warning shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalProcesarLote">
                    <i class="fa-solid fa-gears me-1"></i> Registrar Procesamiento de Lote
                </button>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th># Proceso</th>
                                    <th>Lote Origen</th>
                                    <th>Peso Vivo Inicial</th>
                                    <th>Peso Útil Obtenido</th>
                                    <th>Merma (Kg / %)</th>
                                    <th>Fecha de Proceso</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($procesos)): ?>
                                    <?php foreach ($procesos as $p): ?>
                                        <tr>
                                            <td><span class="badge bg-secondary">#PR-<?= $p['id'] ?></span></td>
                                            <td class="fw-bold">Lote #<?= $p['lote_id'] ?> (<?= esc($p['nombre_empresa']) ?>)</td>
                                            <td><?= number_format($p['peso_inicial'], 2) ?> kg</td>
                                            <td class="text-success fw-bold"><?= number_format($p['peso_util'], 2) ?> kg</td>
                                            <td class="text-danger fw-bold"><?= number_format($p['merma_kg'], 2) ?> kg (<?= number_format($p['merma_porc'], 1) ?>%)</td>
                                            <td><?= $p['fecha_proceso'] ?></td>
                                            <td class="text-center"><span class="badge bg-success">Procesado</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center text-muted">No hay procesos registrados.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Modal Registrar Nuevo Pedido -->
    <div class="modal fade" id="modalNuevoPedido" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-phone-volume me-2"></i> Capturar Pedido (Llamada / WhatsApp)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formMockPedido">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre del Cliente</label>
                                <input type="text" class="form-control" id="pedCliente" placeholder="Ej: Juan Pérez" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Teléfono (WhatsApp)</label>
                                <input type="text" class="form-control" id="pedTelefono" placeholder="Ej: 9931234567" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tipo de Entrega</label>
                                <select class="form-select" id="pedTipoEntrega" onchange="toggleDireccion(this.value)">
                                    <option value="domicilio">Entrega a Domicilio</option>
                                    <option value="tienda">Recolección en Tienda</option>
                                </select>
                            </div>
                            <div class="col-md-4" id="divColonia">
                                <label class="form-label fw-bold">Colonia / Sector</label>
                                <input type="text" class="form-control" id="pedColonia" placeholder="Ej: Centro / Atasta">
                            </div>
                            <div class="col-12" id="divDireccion">
                                <label class="form-label fw-bold">Dirección Completa</label>
                                <input type="text" class="form-control" id="pedDireccion" placeholder="Calle, número exterior/interior y referencias">
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-basket-shopping me-2"></i> Detalle de Productos</h6>
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-md-5">
                                <select class="form-select" id="selProducto">
                                    <option value="Pollo Entero|150.00|piezas">Pollo Entero ($150.00/pza)</option>
                                    <option value="Gallina|230.00|piezas">Gallina ($230.00/pza)</option>
                                    <option value="Alitas Adobadas|130.00|kilos">Alitas Adobadas ($130.00/kg)</option>
                                    <option value="Milanesa Empanizada|150.00|kilos">Milanesa Empanizada ($150.00/kg)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.1" class="form-control" id="cantProducto" value="1" placeholder="Cant / Kg">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-outline-success w-100" onclick="agregarItemPedido()">
                                    <i class="fa-solid fa-plus me-1"></i> Añadir Producto
                                </button>
                            </div>
                        </div>

                        <table class="table table-sm mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio U.</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyDetallePedido">
                                <tr><td colspan="4" class="text-center text-muted fs-7">No se han añadido productos.</td></tr>
                            </tbody>
                        </table>

                        <div class="text-end fw-bold fs-5 text-success mt-2">
                            Total: $<span id="lblTotalPedido">0.00</span>
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" onclick="window.modoImpresion = true;">
                                    <i class="fa-solid fa-print me-1"></i> Guardar e Imprimir Ticket
                                </button>
                                <button type="submit" class="btn btn-success" onclick="window.modoImpresion = false;">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Solo Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Previsualización de Ticket Térmico -->
    <div class="modal fade" id="modalTicketPrint" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white py-2">
                    <h6 class="modal-title mb-0"><i class="fa-solid fa-receipt me-1"></i> Ticket de Venta / Comanda</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light p-3">
                    <!-- Formato impreso físico (Térmico 80mm/58mm) -->
                    <div id="ticketTermico" class="bg-white p-3 border shadow-sm font-monospace text-dark" style="font-size: 12px; width: 100%;">
                        <div class="text-center mb-2">
                            <h6 class="fw-bold mb-0 text-uppercase">EXPENDIO AVÍCOLA</h6>
                            <small class="d-block">Venta y Procesado de Aves</small>
                            <small class="d-block">Tel/WA: 993-123-4567</small>
                            <hr class="my-1 border-dashed">
                            <span class="fw-bold" id="tickFolio">FOLIO: #104</span><br>
                            <small id="tickFecha">20/10/2026 07:00 PM</small>
                        </div>

                        <div class="mb-2">
                            <strong>Cliente:</strong> <span id="tickCliente">Juan Pérez</span><br>
                            <strong>Tel:</strong> <span id="tickTel">9931234567</span><br>
                            <strong>Tipo:</strong> <span id="tickTipo">DOMICILIO</span><br>
                            <small id="tickDir">Calle 5 de Mayo #123, Col. Centro</small><br>
                            <strong>Repartidor:</strong> <span id="tickRepartidor">Carlos</span>
                        </div>

                        <hr class="my-1 border-dashed">

                        <table class="w-100 my-2" style="font-size: 11px;">
                            <thead>
                                <tr class="border-bottom">
                                    <th>CANT/KG</th>
                                    <th>CONCEPTO</th>
                                    <th class="text-end">IMP.</th>
                                </tr>
                            </thead>
                            <tbody id="tickBodyItems">
                                <!-- Items dinámicos -->
                            </tbody>
                        </table>

                        <hr class="my-1 border-dashed">

                        <div class="text-end fw-bold fs-6 my-2">
                            TOTAL: $<span id="tickTotal">0.00</span>
                        </div>

                        <div class="text-center mt-3">
                            <small class="d-block">¡Gracias por su compra!</small>
                            <small class="d-block fw-bold text-uppercase">Conservar para aclaraciones</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success btn-sm" onclick="imprimirTicketDirecto()">
                        <i class="fa-solid fa-print me-1"></i> Imprimir en Miniprinter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Proveedor -->
    <div class="modal fade" id="modalNuevoProveedor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-truck-field me-2"></i> Alta de Nuevo Proveedor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('/proveedores/guardar') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre de la Empresa / Granja</label>
                            <input type="text" class="form-control" name="provEmpresa" placeholder="Ej: Avícola San Antonio" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Persona de Contacto</label>
                            <input type="text" class="form-control" name="provContacto" placeholder="Ej: Juan Pérez">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Teléfono de Contacto</label>
                            <input type="text" class="form-control" name="provTelefono" placeholder="Ej: 9931234567" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-1"></i> Guardar Proveedor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Nuevo Lote (Conectado a Base de Datos) -->
    <div class="modal fade" id="modalNuevoLote" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-truck-ramp-box me-2"></i> Capturar Nuevo Lote</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- Apunta a la ruta POST en el ProduccionController y acepta archivos con enctype -->
                <form action="<?= base_url('/produccion/guardar-lote') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Proveedor</label>
                                <select class="form-select" name="mockProveedor" required>
                                    <option value="">Seleccione un proveedor...</option>
                                    <?php if (!empty($proveedores)): ?>
                                        <?php foreach ($proveedores as $prov): ?>
                                            <option value="<?= $prov['id'] ?>"><?= esc($prov['nombre_empresa']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipo de Ave</label>
                                <select class="form-select" name="mockTipo" required>
                                    <option value="pollo">Pollo</option>
                                    <option value="gallina">Gallina</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Cantidad de Piezas</label>
                                <input type="number" class="form-control" name="mockPiezas" placeholder="Ej: 50" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Peso Total (Kg)</label>
                                <input type="number" step="0.01" class="form-control" name="mockPeso" placeholder="Ej: 115.50" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Precio Compra x Pza ($)</label>
                                <input type="number" step="0.50" class="form-control" name="mockCosto" placeholder="Ej: 95.00" required>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label fw-bold"><i class="fa-solid fa-file-arrow-up text-primary me-1"></i> Comprobante / Nota del Proveedor (Imagen o PDF)</label>
                            <input type="file" class="form-control" name="mockComprobante" accept="image/*,application/pdf" required>
                            <div class="form-text">Sube la foto del ticket físico o el PDF de la factura del proveedor.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-1"></i> Guardar Lote en BD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Armar Viaje de Reparto -->
    <div class="modal fade" id="modalArmarViaje" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-motorcycle me-2"></i> Armar Viaje de Reparto (Manifiesto)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Seleccionar Repartidor</label>
                            <select class="form-select" id="viajeRepartidorSel">
                                <option value="Carlos|9931234567">Carlos (Tel: 9931234567)</option>
                                <option value="Luis|9937654321">Luis (Tel: 9937654321)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Filtrar por Colonia / Sector</label>
                            <input type="text" class="form-control" id="filtroColoniaViaje" placeholder="Ej: Centro, Atasta..." onkeyup="filtrarColoniaTablaViaje()">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2"><i class="fa-solid fa-list-check me-1"></i> Selecciona los pedidos para este viaje:</h6>
                    <div class="table-responsive border rounded mb-3" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 40px;">
                                        <input type="checkbox" id="chkTodosViaje" onchange="toggleTodosViaje(this)">
                                    </th>
                                    <th>Folio</th>
                                    <th>Cliente</th>
                                    <th>Dirección / Colonia</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPedidosParaViaje">
                                <!-- Inyectado por JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Resumen previo del manifiesto -->
                    <div class="p-3 bg-light border rounded">
                        <h6 class="fw-bold text-secondary mb-1"><i class="fa-solid fa-calculator me-1"></i> Resumen del Viaje:</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Pedidos seleccionados: <strong id="lblCantPedidosViaje">0</strong></span>
                            <span class="fs-5 text-success fw-bold">Total a Cobrar: $<span id="lblTotalViaje">0.00</span></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success fw-bold" onclick="enviarManifiestoWhatsApp()">
                        <i class="fa-brands fa-whatsapp fa-lg me-1"></i> Enviar Manifiesto por WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Procesamiento (Conectado a CI4) -->
    <div class="modal fade" id="modalProcesarLote" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-feather-pointed me-2"></i> Procesar Lote Vivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Apunta directamente al método del ProduccionController en CI4 -->
                <form action="<?= base_url('/produccion/guardar-proceso') ?>" method="POST">
                    <?= csrf_field() ?> 
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Seleccionar Lote Vivo</label>
                            <select class="form-select" name="procLoteOrigen" required>
                                <?php if (!empty($lotesDisponibles)): ?>
                                    <?php foreach ($lotesDisponibles as $l): ?>
                                        <option value="<?= $l['id'] ?>">Lote #<?= $l['id'] ?> (<?= $l['tipo_ave'] ?>) - <?= $l['peso'] ?> Kg</option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No hay lotes disponibles</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Peso Vivo Inicial (Kg)</label>
                            <input type="number" step="0.01" class="form-control" name="procPesoInicial" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Peso Total Neto Obtenido (Kg)</label>
                            <input type="number" step="0.01" class="form-control" name="procPesoUtil" placeholder="Ej: 78.50" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-1"></i> Guardar y Calcular Merma</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Variables y datos simulados locales
    /** 
    let lotesMock = [
        { id: 1, proveedor: 'Avícola San Antonio', tipo: 'pollo', piezas: 40, peso: 92.00, costo: 90.00, fecha: '2026-10-18', obs: 'Excelente calidad' },
        { id: 2, proveedor: 'Granja El Gallero', tipo: 'gallina', piezas: 30, peso: 85.50, costo: 150.00, fecha: '2026-10-19', obs: 'Buen tamaño' }
    ];

    let preciosMock = [
        { id: 1, nombre: 'Pollo Entero', unidad: 'piezas', precio: 150.00 },
        { id: 2, nombre: 'Gallina', unidad: 'piezas', precio: 230.00 },
        { id: 3, nombre: 'Alitas Naturales', unidad: 'kilos', precio: 110.00 },
        { id: 4, nombre: 'Alitas Adobadas', unidad: 'kilos', precio: 130.00 },
        { id: 5, nombre: 'Milanesa Natural', unidad: 'kilos', precio: 130.00 }
    ];

    document.addEventListener('DOMContentLoaded', () => {
        renderLotes();
        renderPrecios();

        // Escuchar el evento submit del formulario
        const form = document.getElementById('formMockLote');
        if (form) {
            form.addEventListener('submit', guardarLoteMock);
        }
    });
    */

    function cambiarVista(vista, elemento) {
        document.querySelectorAll('.vista-secccion').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));

        document.getElementById(`vista-${vista}`).classList.remove('d-none');
        if (elemento) {
            elemento.classList.add('active');
        }
    }
/** 
    function renderLotes() {
        let html = '';
        lotesMock.forEach(l => {
            let badgeComprobante = l.comprobante 
                ? `<span class="badge bg-success"><i class="fa-solid fa-file-pdf me-1"></i> Ver Ticket</span>` 
                : `<span class="badge bg-secondary">Sin archivo</span>`;

            html += `
                <tr>
                    <td><span class="badge bg-secondary">#${l.id}</span></td>
                    <td class="fw-bold">${l.proveedor}</td>
                    <td><span class="badge bg-${l.tipo === 'pollo' ? 'warning text-dark' : 'danger'}">${l.tipo.toUpperCase()}</span></td>
                    <td>${l.piezas} aves</td>
                    <td>${l.peso.toFixed(2)} kg</td>
                    <td class="text-success fw-bold">$${l.costo.toFixed(2)}</td>
                    <td>${l.fecha}</td>
                    <td class="text-center">${badgeComprobante}</td>
                    <td>${l.obs}</td>
                </tr>
            `;
        });
        document.getElementById('tablaLotesMock').innerHTML = html;
    }

    function renderPrecios() {
        let html = '';
        preciosMock.forEach(p => {
            html += `
                <tr>
                    <td class="fw-bold">${p.nombre}</td>
                    <td><span class="badge bg-secondary">${p.unidad}</span></td>
                    <td class="text-success fw-bold">$${p.precio.toFixed(2)}</td>
                    <td><input type="number" step="0.50" class="form-control form-control-sm w-50" id="inputP_${p.id}" value="${p.precio}"></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick="simularGuardarPrecio(${p.id})">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Actualizar
                        </button>
                    </td>
                </tr>
            `;
        });
        document.getElementById('tablaPreciosMock').innerHTML = html;
    }

    function guardarLoteMock(e) {
        e.preventDefault();
        
        let archivoInput = document.getElementById('mockComprobante');
        
        // Validación obligatoria de archivo
        if (archivoInput.files.length === 0) {
            alert('¡Atención! Es obligatorio adjuntar la imagen o PDF del ticket del proveedor.');
            return;
        }

        let nombreArchivo = archivoInput.files[0].name;

        let nuevo = {
            id: lotesMock.length + 1,
            proveedor: document.getElementById('mockProveedor').value,
            tipo: document.getElementById('mockTipo').value,
            piezas: parseInt(document.getElementById('mockPiezas').value),
            peso: parseFloat(document.getElementById('mockPeso').value),
            costo: parseFloat(document.getElementById('mockCosto').value),
            fecha: new Date().toISOString().split('T')[0],
            comprobante: nombreArchivo,
            obs: `Comprobante adjunto: ${nombreArchivo}`
        };

        lotesMock.unshift(nuevo);
        renderLotes();

        // Ocultar modal y resetear formulario correctamente
        let modalEl = document.getElementById('modalNuevoLote');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }
        document.getElementById('formMockLote').reset();

        alert('¡Lote registrado con su comprobante obligatorio con éxito!');
    }
*/

    function simularGuardarPrecio(id) {
        let val = document.getElementById(`inputP_${id}`).value;
        alert(`¡Precio del producto ID ${id} actualizado a $${val} (Simulación)!`);
    }

    let pedidosMock = [
    { id: 101, cliente: 'Ana Gómez', tel: '9931112233', tipo: 'domicilio', direccion: 'Calle 5 de Mayo #123, Col. Centro', repartidor: 'Carlos', total: 300.00, estado: 'pendiente' },
    { id: 102, cliente: 'Pedro Ruíz', tel: '9934445566', tipo: 'domicilio', direccion: 'Av. Mina #45, Col. Nueva', repartidor: 'Luis', total: 260.00, estado: 'en_camino' },
    { id: 103, cliente: 'María Ramos', tel: '9937778899', tipo: 'tienda', direccion: 'Recoge en mostrador', repartidor: 'N/A', total: 230.00, estado: 'entregado' }
];

let itemsTemporal = [];

// En DOMContentLoaded agrega renderPedidos();
renderPedidos();

document.getElementById('formMockPedido').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (itemsTemporal.length === 0) {
        alert('Debes agregar al menos un producto al pedido.');
        return;
    }
    
    let total = itemsTemporal.reduce((sum, item) => sum + item.subtotal, 0);
    let cliente = document.getElementById('pedCliente').value;
    let tel = document.getElementById('pedTelefono').value;
    let tipo = document.getElementById('pedTipoEntrega').value;
    let direccion = tipo === 'tienda' ? 'Recoge en mostrador' : document.getElementById('pedDireccion').value;
    let folio = 100 + pedidosMock.length + 1;

    // YA NO LEEMOS 'pedRepartidor' DEL DOM; SE ASIGNA AUTOMÁTICAMENTE SEGÚN EL TIPO
    let repartidor = tipo === 'tienda' ? 'N/A' : 'Sin asignar';

    let nuevoPedido = {
        id: folio,
        cliente: cliente,
        tel: tel,
        tipo: tipo,
        direccion: direccion,
        repartidor: repartidor,
        total: total,
        estado: 'pendiente',
        productos: [...itemsTemporal] // Guardamos la lista de productos para el manifiesto
    };

    pedidosMock.unshift(nuevoPedido);
    renderPedidos();

    // Rellenar formato del ticket impreso
    document.getElementById('tickFolio').innerText = `FOLIO: #${folio}`;
    document.getElementById('tickFecha').innerText = new Date().toLocaleString();
    document.getElementById('tickCliente').innerText = cliente;
    document.getElementById('tickTel').innerText = tel;
    document.getElementById('tickTipo').innerText = tipo.toUpperCase();
    document.getElementById('tickDir').innerText = direccion;
    document.getElementById('tickRepartidor').innerText = repartidor;

    let htmlBody = '';
    itemsTemporal.forEach(i => {
        htmlBody += `
            <tr>
                <td>${i.cant}</td>
                <td>${i.nombre}</td>
                <td class="text-end">$${i.subtotal.toFixed(2)}</td>
            </tr>
        `;
    });
    document.getElementById('tickBodyItems').innerHTML = htmlBody;
    document.getElementById('tickTotal').innerText = total.toFixed(2);

    // Limpiar variables temporales y resetear el formulario
    itemsTemporal = [];
    document.getElementById('tbodyDetallePedido').innerHTML = '<tr><td colspan="4" class="text-center text-muted fs-7">No se han añadido productos.</td></tr>';
    document.getElementById('lblTotalPedido').innerText = '0.00';
    this.reset();

    // Ocultar modal de captura
    let modalEl = document.getElementById('modalNuevoPedido');
    let modalInstance = bootstrap.Modal.getInstance(modalEl);
    if (modalInstance) {
        modalInstance.hide();
    }

    // Si seleccionó "Guardar e Imprimir"
    if (window.modoImpresion) {
        let modalTicket = new bootstrap.Modal(document.getElementById('modalTicketPrint'));
        modalTicket.show();
    } else {
        alert('¡Pedido registrado con éxito!');
    }
});

function renderPedidos() {
    let html = '';
    pedidosMock.forEach(p => {
        let badgeEstado = p.estado === 'pendiente' ? 'bg-warning text-dark' : (p.estado === 'en_camino' ? 'bg-info text-white' : 'bg-success');
        let textoEstado = p.estado === 'pendiente' ? 'En Producción' : (p.estado === 'en_camino' ? 'En Camino' : 'Entregado');
        
        // AQUÍ COLOCAMOS EL BADGE DEL REPARTIDOR
        let badgeRepartidor = p.repartidor === 'Sin asignar' || p.repartidor === 'N/A'
            ? `<span class="badge bg-light text-muted border"><i class="fa-solid fa-clock me-1"></i> Por asignar</span>`
            : `<span class="fw-bold text-dark"><i class="fa-solid fa-motorcycle text-primary me-1"></i> ${p.repartidor}</span>`;

        // Control de botones de acción según el estado
        let accionHtml = '';
        if (p.estado === 'pendiente') {
            if (p.tipo === 'tienda') {
                accionHtml = `<button class="btn btn-sm btn-outline-success" onclick="cambiarEstadoPedido(${p.id}, 'entregado')"><i class="fa-solid fa-check"></i> Entregar</button>`;
            } else {
                accionHtml = `<span class="badge bg-light text-secondary border align-self-center"><i class="fa-solid fa-clock me-1"></i> Por Agrupar</span>`;
            }
        } else if (p.estado === 'en_camino') {
            accionHtml = `<button class="btn btn-sm btn-outline-success" onclick="cambiarEstadoPedido(${p.id}, 'entregado')"><i class="fa-solid fa-check"></i> Entregar</button>`;
        } else {
            accionHtml = `<span class="badge bg-light text-success border align-self-center"><i class="fa-solid fa-circle-check"></i> Completo</span>`;
        }

        html += `
            <tr>
                <td><span class="badge bg-secondary">#${p.id}</span></td>
                <td><strong>${p.cliente}</strong><br><small class="text-muted">${p.tel}</small></td>
                <td><span class="badge bg-light text-dark border">${p.tipo.toUpperCase()}</span></td>
                <td><small>${p.direccion}</small></td>
                <td>${badgeRepartidor}</td> <!-- AQUÍ SE PINTA EL REPARTIDOR -->
                <td class="text-success fw-bold">$${p.total.toFixed(2)}</td>
                <td><span class="badge ${badgeEstado}">${textoEstado}</span></td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        <button class="btn btn-sm btn-outline-dark" onclick="reimprimirTicket(${p.id})" title="Reimprimir Ticket">
                            <i class="fa-solid fa-print"></i>
                        </button>
                        ${accionHtml}
                    </div>
                </td>
            </tr>
        `;
    });
    document.getElementById('tablaPedidosMock').innerHTML = html;
}

function agregarItemPedido() {
    let raw = document.getElementById('selProducto').value.split('|');
    let nombre = raw[0];
    let precio = parseFloat(raw[1]);
    let cant = parseFloat(document.getElementById('cantProducto').value);
    
    if(cant <= 0) return;

    let subtotal = precio * cant;
    itemsTemporal.push({ nombre, precio, cant, subtotal });

    let html = '';
    let total = 0;
    itemsTemporal.forEach(i => {
        total += i.subtotal;
        html += `<tr><td>${i.nombre}</td><td>${i.cant}</td><td>$${i.precio.toFixed(2)}</td><td>$${i.subtotal.toFixed(2)}</td></tr>`;
    });

    document.getElementById('tbodyDetallePedido').innerHTML = html;
    document.getElementById('lblTotalPedido').innerText = total.toFixed(2);
}

function cambiarEstadoPedido(id, nuevoEstado) {
    let ped = pedidosMock.find(x => x.id === id);
    if(ped) {
        ped.estado = nuevoEstado;
        renderPedidos();
    }
}

function toggleDireccion(val) {
    let show = val === 'domicilio';
    document.getElementById('divColonia').style.display = show ? 'block' : 'none';
    document.getElementById('divDireccion').style.display = show ? 'block' : 'none';
    // Se elimina la línea que manipulaba 'divRepartidor'
}

let proveedoresMock = [
    { id: 1, empresa: 'Avícola San Antonio', contacto: 'Juan Pérez', telefono: '9931234567', fecha: '2026-10-01' },
    { id: 2, empresa: 'Granja El Gallero', contacto: 'María López', telefono: '9937654321', fecha: '2026-10-05' }
];

// Ejecutar al cargar
renderProveedores();

function renderProveedores() {
    let html = '';
    proveedoresMock.forEach(p => {
        html += `
            <tr>
                <td><span class="badge bg-secondary">#${p.id}</span></td>
                <td class="fw-bold">${p.empresa}</td>
                <td>${p.contacto || '-'}</td>
                <td><i class="fa-solid fa-phone text-success me-1"></i>${p.telefono}</td>
                <td>${p.fecha}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarProveedor(${p.id})"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
    document.getElementById('tablaProveedoresMock').innerHTML = html;
}

function guardarProveedorMock(e) {
    e.preventDefault();
    let nuevo = {
        id: proveedoresMock.length + 1,
        empresa: document.getElementById('provEmpresa').value,
        contacto: document.getElementById('provContacto').value,
        telefono: document.getElementById('provTelefono').value,
        fecha: new Date().toISOString().split('T')[0]
    };
    proveedoresMock.push(nuevo);
    renderProveedores();
    document.getElementById('formMockProveedor').reset();
    bootstrap.Modal.getInstance(document.getElementById('modalNuevoProveedor')).hide();
    alert('¡Proveedor registrado con éxito en el cascarón!');
}

function eliminarProveedor(id) {
    if(confirm('¿Deseas eliminar este proveedor?')) {
        proveedoresMock = proveedoresMock.filter(p => p.id !== id);
        renderProveedores();
    }
}
function imprimirTicketDirecto() {
    window.print();
}
function reimprimirTicket(id) {
    let ped = pedidosMock.find(x => x.id === id);
    if (!ped) {
        alert('No se encontró el pedido.');
        return;
    }

    // Rellenar cabecera y datos del cliente en el ticket
    document.getElementById('tickFolio').innerText = `FOLIO: #${ped.id}`;
    document.getElementById('tickFecha').innerText = new Date().toLocaleString() + ' (REIMPRESIÓN)';
    document.getElementById('tickCliente').innerText = ped.cliente;
    document.getElementById('tickTel').innerText = ped.tel;
    document.getElementById('tickTipo').innerText = ped.tipo.toUpperCase();
    document.getElementById('tickDir').innerText = ped.direccion;
    document.getElementById('tickRepartidor').innerText = ped.repartidor;

    // Rellenar ítems (en la simulación mostramos el concepto general o la lista)
    let htmlBody = `
        <tr>
            <td>1</td>
            <td>Venta Pedido #${ped.id}</td>
            <td class="text-end">$${ped.total.toFixed(2)}</td>
        </tr>
    `;
    document.getElementById('tickBodyItems').innerHTML = htmlBody;
    document.getElementById('tickTotal').innerText = ped.total.toFixed(2);

    // Abrir el modal del ticket listo para miniprinter
    let modalTicket = new bootstrap.Modal(document.getElementById('modalTicketPrint'));
    modalTicket.show();
}

// Cargar pedidos disponibles para armar un viaje
function cargarPedidosParaViaje() {
    let html = '';
    // Filtramos solo pedidos que estén pendientes o en cocina y sean a domicilio
    let pendientes = pedidosMock.filter(p => (p.estado === 'pendiente' || p.estado === 'en_camino') && p.tipo === 'domicilio');

    if (pendientes.length === 0) {
        html = '<tr><td colspan="5" class="text-center text-muted p-3">No hay pedidos pendientes de entrega a domicilio.</td></tr>';
    } else {
        pendientes.forEach(p => {
            html += `
                <tr class="fila-pedido-viaje" data-colonia="${p.direccion.toLowerCase()}">
                    <td class="text-center">
                        <input type="checkbox" class="chk-ped-viaje" value="${p.id}" data-monto="${p.total}" onchange="calcularTotalesViaje()">
                    </td>
                    <td><span class="badge bg-secondary">#${p.id}</span></td>
                    <td><strong>${p.cliente}</strong><br><small class="text-muted">${p.tel}</small></td>
                    <td><small>${p.direccion}</small></td>
                    <td class="fw-bold text-success">$${p.total.toFixed(2)}</td>
                </tr>
            `;
        });
    }

    document.getElementById('tbodyPedidosParaViaje').innerHTML = html;
    document.getElementById('lblCantPedidosViaje').innerText = '0';
    document.getElementById('lblTotalViaje').innerText = '0.00';
    document.getElementById('chkTodosViaje').checked = false;
}

// Marcar/Desmarcar todos
function toggleTodosViaje(chkPrincipal) {
    document.querySelectorAll('.chk-ped-viaje').forEach(chk => {
        chk.checked = chkPrincipal.checked;
    });
    calcularTotalesViaje();
}

// Filtrar filas por colonia
function filtrarColoniaTablaViaje() {
    let filtro = document.getElementById('filtroColoniaViaje').value.toLowerCase();
    document.querySelectorAll('.fila-pedido-viaje').forEach(tr => {
        let text = tr.getAttribute('data-colonia');
        tr.style.display = text.includes(filtro) ? '' : 'none';
    });
}

// Recalcular montos seleccionados
function calcularTotalesViaje() {
    let seleccionados = document.querySelectorAll('.chk-ped-viaje:checked');
    let cant = seleccionados.length;
    let total = 0;

    seleccionados.forEach(chk => {
        total += parseFloat(chk.getAttribute('data-monto'));
    });

    document.getElementById('lblCantPedidosViaje').innerText = cant;
    document.getElementById('lblTotalViaje').innerText = total.toFixed(2);
}

// Enviar Manifiesto vía WhatsApp
function enviarManifiestoWhatsApp() {
    let seleccionados = document.querySelectorAll('.chk-ped-viaje:checked');
    if (seleccionados.length === 0) {
        alert('Debes seleccionar al menos un pedido para armar el viaje de reparto.');
        return;
    }

    let repRaw = document.getElementById('viajeRepartidorSel').value.split('|');
    let nombreRepartidor = repRaw[0];
    let telRepartidor = repRaw[1];
    let numViaje = Math.floor(Math.random() * 90) + 10;
    let totalCobrar = 0;
    
    // Objeto para acumular el resumen total de mercancía a cargar en la moto
    let resumenCarga = {};

    // Construir mensaje redactado
    let msj = `🛵 *MANIFIESTO DE CARGA - EXPENDIO AVÍCOLA*\n`;
    msj += `----------------------------------\n`;
    msj += `👤 *Repartidor:* ${nombreRepartidor}\n`;
    msj += `📅 *Fecha:* ${new Date().toLocaleDateString()}\n`;
    msj += `📦 *Viaje #V-${numViaje}* (${seleccionados.length} Pedidos en Ruta)\n\n`;
    msj += `📍 *ENTREGAS EN RUTA:*\n`;

    let index = 1;
    seleccionados.forEach(chk => {
        let pedId = parseInt(chk.value);
        let ped = pedidosMock.find(x => x.id === pedId);
        if (ped) {
            totalCobrar += ped.total;
            msj += `${index}️⃣ *Folio #${ped.id}* - ${ped.cliente}\n`;
            msj += `   📞 Tel: ${ped.tel}\n`;
            msj += `   🏠 Dir: ${ped.direccion}\n`;
            
            // Desglosar productos del pedido
            msj += `   🍗 *Productos:*\n`;
            if (ped.productos && ped.productos.length > 0) {
                ped.productos.forEach(prod => {
                    msj += `      - ${prod.cant}x ${prod.nombre} ($${prod.subtotal.toFixed(2)})\n`;
                    
                    // Acumular para el resumen general de carga
                    if (resumenCarga[prod.nombre]) {
                        resumenCarga[prod.nombre] += parseFloat(prod.cant);
                    } else {
                        resumenCarga[prod.nombre] = parseFloat(prod.cant);
                    }
                });
            } else {
                msj += `      - 1x Venta General ($${ped.total.toFixed(2)})\n`;
            }

            msj += `   💵 *Importe a Cobrar:* $${ped.total.toFixed(2)}\n\n`;
            
            // Actualizar estado del pedido a 'en_camino'
            ped.estado = 'en_camino';
            ped.repartidor = nombreRepartidor;
            index++;
        }
    });

    // Agregar resumen general de carga al mensaje
    msj += `📦 *RESUMEN TOTAL DE CARGA:*\n`;
    for (let [item, cantidad] of Object.entries(resumenCarga)) {
        msj += `   - ${cantidad}x ${item}\n`;
    }
    msj += `\n`;

    msj += `💰 *TOTAL A LIQUIDAR EN CAJA:* $${totalCobrar.toFixed(2)}\n`;
    msj += `----------------------------------\n`;
    msj += `_Por favor confirmar recepción al salir. ¡Buen viaje!_`;

    // Renderizar la tabla principal con los nuevos estados 'En Camino'
    renderPedidos();

    // Ocultar modal
    let modalEl = document.getElementById('modalArmarViaje');
    bootstrap.Modal.getInstance(modalEl).hide();

    // Abrir enlace directo a WhatsApp Web / App
    let urlWA = `https://api.whatsapp.com/send?phone=52${telRepartidor}&text=${encodeURIComponent(msj)}`;
    window.open(urlWA, '_blank');
}

let procesamientoMock = [
    { id: 1, lote: 'Lote #1 (Avícola San Antonio)', inicial: 92.00, util: 78.20, mermaKg: 13.80, mermaPorc: 15.00, fecha: '2026-10-19' }
];

// Ejecutar al cargar la página
renderProcesamiento();

function renderProcesamiento() {
    let html = '';
    procesamientoMock.forEach(p => {
        html += `
            <tr>
                <td><span class="badge bg-secondary">#PR-${p.id}</span></td>
                <td class="fw-bold">${p.lote}</td>
                <td>${p.inicial.toFixed(2)} kg</td>
                <td class="text-success fw-bold">${p.util.toFixed(2)} kg</td>
                <td class="text-danger fw-bold">${p.mermaKg.toFixed(2)} kg (${p.mermaPorc.toFixed(1)}%)</td>
                <td>${p.fecha}</td>
                <td class="text-center"><span class="badge bg-success">Procesado</span></td>
            </tr>
        `;
    });
    document.getElementById('tablaProcesamientoMock').innerHTML = html;
}

function guardarProcesoMock(e) {
    e.preventDefault();

    let inicial = parseFloat(document.getElementById('procPesoInicial').value);
    let util = parseFloat(document.getElementById('procPesoUtil').value);

    if (util >= inicial) {
        alert('Error: El peso útil obtenido no puede ser mayor o igual al peso vivo inicial.');
        return;
    }

    let mermaKg = inicial - util;
    let mermaPorc = (mermaKg / inicial) * 100;

    let nuevoProceso = {
        id: procesamientoMock.length + 1,
        lote: document.getElementById('procLoteOrigen').value,
        inicial: inicial,
        util: util,
        mermaKg: mermaKg,
        mermaPorc: mermaPorc,
        fecha: new Date().toISOString().split('T')[0]
    };

    procesamientoMock.unshift(nuevoProceso);
    renderProcesamiento();

    // Ocultar modal y resetear
    let modalEl = document.getElementById('modalProcesarLote');
    bootstrap.Modal.getInstance(modalEl).hide();
    document.getElementById('formMockProceso').reset();

    alert(`¡Procesamiento registrado con éxito! Merma calculada: ${mermaKg.toFixed(2)} Kg (${mermaPorc.toFixed(1)}%)`);
}
</script>
</body>
</html>