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

    <!-- Navbar Principal Agrupado -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fa-solid fa-store text-warning me-2"></i> 
                <span id="lblBrandNombre">
                    <?= esc($nombreEmpresa ?? 'EXPENDIO AVÍCOLA') ?> - <span id="lblSucursalNavbar"><?= esc($sucursalActiva ?? 'Centro') ?></span>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- OPERACIÓN DIARIA (Siempre a la vista) -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('pedidos', this)"><i class="fa-solid fa-receipt me-1"></i> Pedidos</a>
                    </li>
                    <?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('produccion', this)"><i class="fa-solid fa-feather me-1"></i> Producción</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('despiece', this)"><i class="fa-solid fa-scissors me-1"></i> Despiece</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('mermas', this); cargarInventarioGeneral();">
                            <i class="fa-solid fa-boxes-stacked me-1"></i> Almacén
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- MENÚ DESPLEGABLE: CATÁLOGOS Y CONTACTOS -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuCatalogos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-folder-open me-1"></i> Catálogos
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark shadow" aria-labelledby="menuCatalogos">
                            <?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
                            <li>
                                <a class="dropdown-item" href="#" onclick="cambiarVista('precios', this)">
                                    <i class="fa-solid fa-tags me-2 text-warning"></i> Precios Diarios
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="cambiarVista('proveedores', this)">
                                    <i class="fa-solid fa-truck-field me-2 text-secondary"></i> Proveedores
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="cambiarVista('repartidores', this)">
                                    <i class="fa-solid fa-motorcycle me-2 text-info"></i> Repartidores
                                </a>
                            </li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item" href="#" onclick="cambiarVista('clientes', this); cargarClientesDirectorio();">
                                    <i class="fa-solid fa-address-book me-2 text-primary"></i> Clientes
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
                    <!-- REPORTES -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiarVista('reportes', this); cargarReportesDinamicos();">
                            <i class="fa-solid fa-chart-line me-1"></i> Reportes
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- MENÚ DESPLEGABLE: SISTEMA Y SEGURIDAD -->
                    <!-- MENÚ DINÁMICO SEGÚN ROL -->
                    <?php if (auth()->user()->inGroup('superadmin')): ?>
                        <!-- MENÚ EXCLUSIVO PARA SUPERADMIN (Contiene la selección de sucursal y opciones del sistema) -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-warning fw-bold" href="#" id="menuSistema" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-shield me-1"></i> <?= auth()->user()->username ?? 'Superadmin' ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" aria-labelledby="menuSistema">
                                <!-- Encabezado de Rol -->
                                <li>
                                    <h6 class="dropdown-header text-light">
                                        <i class="fa-solid fa-shield-halved text-warning me-1"></i> Rol: Superadmin
                                    </h6>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                
                                <!-- Contenedor de la sucursal actual seleccionada -->
                                <li>
                                    <a class="dropdown-item" href="#" onclick="cambiarVista('sucursales', this); cargarSucursalesDirectorio();">
                                        <i class="fa-solid fa-store me-2 text-warning"></i> Catálogo de Sucursales
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>

                                <!-- SECCIÓN DE SUCURSALES (SELECTOR UNIFICADO Y MODERNO) -->
                                <li>
                                    <h6 class="dropdown-header text-warning fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <i class="fa-solid fa-location-dot me-1"></i> Sucursales:
                                    </h6>
                                </li>

                                <?php if (!empty($sucursales)): ?>
                                    <?php foreach ($sucursales as $suc): ?>
                                        <?php $esActiva = (session()->get('sucursal_activa_id') == $suc['id']); ?>
                                        <li>
                                            <a class="dropdown-item py-2 d-flex align-items-center justify-content-between <?= $esActiva ? 'active bg-warning text-dark fw-bold' : '' ?>" 
                                            href="#" 
                                            onclick="cambiarSucursal(<?= $suc['id'] ?>, '<?= esc($suc['nombre_municipio'], 'js') ?>')">
                                                <span><i class="fa-solid fa-store me-2"></i> <?= esc($suc['nombre_municipio']) ?></span>
                                                <i class="fa-solid fa-check <?= $esActiva ? '' : 'd-none' ?>"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <li><hr class="dropdown-divider"></li>

                                <!-- Opciones Generales del Superadmin -->
                                <li>
                                    <a class="dropdown-item" href="#" onclick="cambiarVista('usuarios', this); cargarUsuariosPermisos();">
                                        <i class="fa-solid fa-users-gear me-2 text-warning"></i> Usuarios y Roles
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalConfiguracionSistema" onclick="cargarConfiguracionSistema()">
                                        <i class="fa-solid fa-gears me-2 text-warning"></i> Configuración de Empresa
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- MENÚ PARA OTROS ROLES (Gerente, Mostrador, Repartidor) -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-light fw-bold" href="#" id="menuUsuarioSimple" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-circle me-1"></i> <?= auth()->user()->username ?? 'Usuario' ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" aria-labelledby="menuUsuarioSimple">
                                <li>
                                    <h6 class="dropdown-header text-light text-capitalize">
                                        <i class="fa-solid fa-user-tag text-info me-1"></i> Rol: <?= auth()->user()->getGroups()[0] ?? 'Personal' ?>
                                    </h6>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">

        <?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
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
                                    <th class="text-center">Comprobante del Proveedor</th>
                                </tr>
                            </thead>
                            <tbody id="tablaLotesMock">
                                <?php if (!empty($lotes)): ?>
                                    <?php foreach ($lotes as $l): ?>
                                        <tr id="fila-lote-tabla-<?= $l['id'] ?>">
                                            <td><span class="badge bg-secondary">#<?= $l['id'] ?></span></td>
                                            <td class="fw-bold">Proveedor ID #<?= $l['proveedor_id'] ?></td>
                                            <td><span class="badge bg-<?= $l['tipo_ave'] === 'pollo' ? 'warning text-dark' : 'danger' ?>"><?= strtoupper($l['tipo_ave']) ?></span></td>
                                            <td><?= $l['piezas'] ?> aves</td>
                                            <td><?= number_format($l['peso'], 2) ?> kg</td>
                                            <td class="text-success fw-bold">$<?= number_format($l['costo_unitario'], 2) ?></td>
                                            <td><?= $l['fecha_recepcion'] ?></td>
                                            <td class="text-center" id="celda-comprobante-<?= $l['id'] ?>">
                                                <?php if (!empty($l['comprobante'])): ?>
                                                    <?php $urlArchivo = base_url('uploads/lotes/' . $l['comprobante']); ?>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <button type="button" class="btn btn-sm btn-success" onclick="verComprobanteModal('<?= $urlArchivo ?>')" title="Ver comprobante">
                                                            <i class="fa-solid fa-file-lines me-1"></i> Ver
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="abrirModalSubirComprobante(<?= $l['id'] ?>)" title="Cambiar archivo">
                                                            <i class="fa-solid fa-upload"></i>
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="abrirModalSubirComprobante(<?= $l['id'] ?>)">
                                                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Subir Comprobante
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="8" class="text-center text-muted">No hay lotes registrados en la base de datos.</td></tr>
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
                <h3 class="fw-bold mb-0"><i class="fa-solid fa-tags text-warning me-2"></i> Control de Precios Diarios y Catálogo</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
                    <i class="fa-solid fa-plus me-1"></i> Registrar Nuevo Producto
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3"># ID</th>
                                    <th>Producto</th>
                                    <th>Unidad</th>
                                    <th>Precio Actual</th>
                                    <th>Nuevo Precio</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaCatalogoProductos">
                                <!-- Aquí puedes iterar desde PHP o cargarlo por AJAX -->
                                <?php foreach ($productos as $p): ?>
                                    <?php if ($p['estado'] == 1): ?>
                                    <tr id="fila_prod_<?= $p['id'] ?>">
                                        <td class="ps-3">#<?= $p['id'] ?></td>
                                        <td class="fw-bold"><?= $p['nombre'] ?></td>
                                        <td><span class="badge bg-secondary"><?= $p['tipo_unidad'] ?></span></td>
                                        <td class="text-success fw-bold" id="lblPrecioActual_<?= $p['id'] ?>">$<?= number_format($p['precio_venta_actual'], 2) ?></td>
                                        <td>
                                            <input type="number" step="0.50" id="precio_<?= $p['id'] ?>" class="form-control form-control-sm" value="<?= $p['precio_venta_actual'] ?>" style="width: 100px;">
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="actualizarPrecioProducto(<?= $p['id'] ?>)" title="Actualizar Precio">
                                                <i class="fa-solid fa-floppy-disk"></i> Actualizar
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarProductoLogico(<?= $p['id'] ?>)" title="Eliminar Producto">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- VISTA 3: PEDIDOS -->
        <div id="vista-pedidos" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-receipt text-primary me-2"></i> Pedidos, Mostrador y Reparto</h2>
                <div class="d-flex gap-2">
                    <!-- Botón de consulta rápida en la Vista de Pedidos -->
                    <button class="btn btn-outline-dark shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalStockDisponibilidad" onclick="cargarTablaStockRapido()">
                        <i class="fa-solid fa-boxes-stacked me-1 text-warning"></i> Consultar Stock
                    </button>
                    <button class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalArmarViaje" onclick="cargarPedidosParaViaje(); cargarRepartidoresParaViaje();">
                        <i class="fa-solid fa-route me-1"></i> Agrupar y Crear Viaje de Reparto
                    </button>
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoPedido" onclick="cargarProductos()">
                        <i class="fa-solid fa-cart-plus me-1"></i> Registrar Nuevo Pedido
                    </button>
                </div>
            </div>

            <!-- Filtros por Estado (Contadores estáticos o dinámicos) -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card bg-warning text-dark border-0 shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold">Pendientes / Producción</h6>
                                <h3 class="mb-0 fw-bold" id="cntPendientes">--</h3>
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
                                <h3 class="mb-0 fw-bold" id="cntCamino">--</h3>
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
                                <h3 class="mb-0 fw-bold" id="cntEntregados">--</h3>
                            </div>
                            <i class="fa-solid fa-circle-check fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-dark text-white border-0 shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold">Venta Total Hoy</h6>
                                <h4 class="mb-1 fw-bold text-success" id="totalVentaHoy">$0.00</h4>
                                <div class="small text-light opacity-75" style="font-size: 11px;">
                                    💵 Efec: <span id="ventaEfectivoHoy" class="fw-bold text-white">$0.00</span> | 
                                    📱 Transf: <span id="ventaTransferenciaHoy" class="fw-bold text-white">$0.00</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-dollar-sign fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Pedidos Conectada a MySQL -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th># Folio</th>
                                    <th>Cliente / Teléfono</th>
                                    <th>Tipo Entrega</th>
                                    <th>Tipo Pago</th> <!-- NUEVA COLUMNA -->
                                    <th>Dirección / Colonia</th>
                                    <th>Repartidor</th>
                                    <th>Total ($)</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaPedidosDb">
                                <?php if (!empty($pedidos)): ?>
                                    <?php foreach ($pedidos as $p): ?>
                                        <?php 
                                            $tipoPago = $p['tipo_pago'] ?? 'efectivo';
                                            $esTransferencia = ($tipoPago === 'transferencia');
                                            $verificado = isset($p['pago_verificado']) && $p['pago_verificado'] == 1;
                                            
                                            // Si es transferencia y NO está verificado, resalta el renglón
                                            $claseFila = ($esTransferencia && !$verificado) ? 'table-warning' : '';
                                        ?>
                                        <tr id="fila-pedido-<?= $p['id'] ?>" class="<?= $claseFila ?>">
                                            <td><span class="badge bg-secondary">#<?= $p['id'] ?></span></td>
                                            <td><strong><?= esc($p['cliente']) ?></strong><br><small class="text-muted"><?= esc($p['telefono']) ?></small></td>
                                            <td><span class="badge bg-light text-dark border"><?= strtoupper($p['tipo_entrega']) ?></span></td>
                                            
                                            <!-- TIPO DE PAGO CON BOTÓN INTERACTIVO SI ES TRANSFERENCIA -->
                                            <td>
                                                <?php if ($esTransferencia): ?>
                                                    <?php if ($verificado): ?>
                                                        <span class="badge bg-primary text-white" title="Transferencia Verificada"><i class="fa-solid fa-circle-check me-1"></i> Transferencia</span>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-warning text-dark fw-bold border shadow-sm" onclick="abrirModalVerificarTransferencia(<?= $p['id'] ?>, '<?= addslashes($p['cliente']) ?>', <?= $p['total'] ?>)" title="Clic para verificar transferencia">
                                                            <i class="fa-solid fa-mobile-screen-button me-1"></i> Transferencia
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-success text-white">Efectivo</span>
                                                <?php endif; ?>
                                            </td>
            
                                            <td><small><?= esc($p['direccion'] ?? $p['colonia']) ?></small></td>
                                            <td>
                                                <?php if ($p['tipo_entrega'] === 'tienda'): ?>
                                                    <span class="badge bg-light text-dark border"><i class="fa-solid fa-store me-1"></i> N/A</span>
                                                <?php elseif ($p['repartidor'] === 'Sin asignar' || empty($p['repartidor'])): ?>
                                                    <span class="badge bg-light text-muted border"><i class="fa-solid fa-clock me-1"></i> Por asignar</span>
                                                <?php else: ?>
                                                    <span class="fw-bold text-dark"><i class="fa-solid fa-motorcycle text-primary me-1"></i> <?= esc($p['repartidor']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-success fw-bold">$<?= number_format($p['total'], 2) ?></td>
                                            <td>
                                                <?php 
                                                    $badgeEstado = 'bg-warning text-dark';
                                                    $textoEstado = 'En Producción';
                                                    if ($p['estado'] === 'en_camino') {
                                                        $badgeEstado = 'bg-info text-white';
                                                        $textoEstado = 'En Camino';
                                                    } elseif ($p['estado'] === 'entregado') {
                                                        $badgeEstado = 'bg-success';
                                                        $textoEstado = 'Entregado';
                                                    }
                                                ?>
                                                <span class="badge <?= $badgeEstado ?>"><?= $textoEstado ?></span>
                                            </td>
                                            <td class="text-center">

                                                <div class="d-flex justify-content-center gap-1">
                                                    <button class="btn btn-sm btn-outline-dark" onclick="reimprimirTicket({
                                                        id: '<?= $p['id'] ?>',
                                                        cliente: '<?= addslashes($p['cliente']) ?>',
                                                        tel: '<?= addslashes($p['telefono']) ?>',
                                                        tipo: '<?= addslashes($p['tipo_entrega']) ?>',
                                                        direccion: '<?= addslashes($p['direccion'] ?? $p['colonia']) ?>',
                                                        repartidor: '<?= addslashes($p['repartidor']) ?>',
                                                        total: '<?= $p['total'] ?>'
                                                    })" title="Reimprimir Ticket">
                                                        <i class="fa-solid fa-print"></i>
                                                    </button>
                                                    <?php if ($p['estado'] !== 'entregado'): ?>
                                                        <button class="btn btn-sm btn-outline-success" onclick="cambiarEstadoPedido(<?= $p['id'] ?>, 'entregado', '<?= $p['tipo_entrega'] ?>', '<?= esc($p['repartidor']) ?>')">
                                                            <i class="fa-solid fa-check"></i> Entregar
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-success border align-self-center"><i class="fa-solid fa-circle-check"></i> Completo</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="9" class="text-center text-muted">No hay pedidos registrados en la base de datos.</td></tr>
                                <?php endif; ?>
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
        <?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
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
                                        <tr id="fila_prov_<?= $p['id'] ?>"> <!-- <--- ¡Asegúrate de agregar este ID aquí! -->
                                            <td><span class="badge bg-secondary">#<?= $p['id'] ?></span></td>
                                            <td class="fw-bold"><?= esc($p['nombre_empresa']) ?></td>
                                            <td><?= esc($p['contacto'] ?? '-') ?></td>
                                            <td><i class="fa-solid fa-phone text-success me-1"></i><?= esc($p['telefono']) ?></td>
                                            <td><?= $p['created_at'] ?? '-' ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarProveedorAjax(<?= $p['id'] ?>)" title="Dar de baja">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
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
                <button class="btn btn-warning shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalProcesarLote" onclick="cargarProductosParaDespiece()">
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

        <!-- VISTA 7: REPARTIDORES -->
        <div id="vista-repartidores" class="container-fluid py-3 vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0"><i class="fa-solid fa-motorcycle text-info me-2"></i> Gestión de Repartidores</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoRepartidor">
                    <i class="fa-solid fa-user-plus me-1"></i> Registrar Nuevo Repartidor
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3"># ID</th>
                                    <th>Nombre del Repartidor</th>
                                    <th>Teléfono / WhatsApp</th>
                                    <th>Vehículo</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaRepartidores">
                                <?php 
                                $repModel = new \App\Models\RepartidorModel();
                                $repartidores = $repModel->getActivos();
                                foreach ($repartidores as $r): 
                                ?>
                                    <tr id="fila_rep_<?= $r['id'] ?>">
                                        <td class="ps-3">#<?= $r['id'] ?></td>
                                        <td class="fw-bold"><?= $r['nombre'] ?></td>
                                        <td><?= $r['telefono'] ?></td>
                                        <td><span class="badge bg-info text-dark"><?= $r['vehiculo'] ?></span></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarRepartidor(<?= $r['id'] ?>)" title="Dar de baja">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISTA 4: DESPIECE Y CONVERSIÓN DE LOTES -->
        <div id="vista-despiece" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-scissors text-warning me-2"></i> Módulo de Despiece y Conversión de Lotes</h2>
                <button class="btn btn-warning shadow-sm fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#modalNuevoDespiece">
                    <i class="fa-solid fa-gears me-1"></i> Registrar Transformación de Lote
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan="3" class="border-end">Lote Original</th>
                                    <th colspan="4">Despiece y Conversión</th>
                                </tr>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto / Lote</th>
                                    <th>Piezas / Kilos Originales</th>
                                    <th>Producto para Venta</th>
                                    <th>Piezas Usadas / Restantes</th>
                                    <th>Kilos Usados / Restantes</th>
                                    <th>Piezas / Kilos Usables (Stock)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($lotes)): ?>
                                    <?php foreach ($lotes as $l): ?>
                                        <?php 
                                            // 1. Buscar si el lote tiene proceso de despiece
                                            $procesoAsociado = null;
                                            $detallesCortes = [];
                                            if (!empty($procesos)) {
                                                foreach ($procesos as $p) {
                                                    if ($p['lote_id'] == $l['id']) {
                                                        $procesoAsociado = $p;
                                                        break;
                                                    }
                                                }
                                            }
                                            
                                            if ($procesoAsociado && !empty($procesamientoDetalles)) {
                                                foreach ($procesamientoDetalles as $det) {
                                                    if ($det['proceso_id'] == $procesoAsociado['id']) {
                                                        $detallesCortes[] = $det;
                                                    }
                                                }
                                            }

                                            // 2. Cálculos matemáticos del resto de lote
                                            $piezasOriginales = (int) $l['piezas'];
                                            $pesoOriginal = (float) $l['peso'];
                                            
                                            $piezasUsadas = $procesoAsociado ? (int) $procesoAsociado['piezas_destinadas'] : 0;
                                            $piezasRestantes = $piezasOriginales - $piezasUsadas;

                                            // Peso proporcional descontado para los enteros
                                            $pesoPromedio = $piezasOriginales > 0 ? ($pesoOriginal / $piezasOriginales) : 0;
                                            $kilosDescontados = $pesoPromedio * $piezasUsadas;
                                            $kilosRestantesEnteros = $pesoOriginal - $kilosDescontados;
                                        ?>
                                        <tr id="fila-lote-<?= $l['id'] ?>">
                                            <!-- Lote Original -->
                                            <td class="fw-bold bg-light">#<?= $l['id'] ?></td>
                                            <td class="fw-bold bg-light text-start">
                                                <?= ucfirst($l['tipo_ave']) ?> <br>
                                                <small class="text-muted">Lote ID: #<?= $l['id'] ?></small>
                                            </td>
                                            <td class="bg-light">
                                                <span class="badge bg-secondary"><?= $piezasOriginales ?> Pz</span><br>
                                                <span class="text-success fw-bold"><?= number_format($pesoOriginal, 2) ?> Kg</span>
                                            </td>

                                            <!-- Despiece y Conversión Dinámico -->
                                            <td colspan="4" class="p-0">
                                                <table class="table table-sm table-borderless mb-0 text-center align-middle">
                                                    
                                                    <!-- Fila del Resto de Lote (Enteros para Venta) -->
                                                    <tr class="bg-white">
                                                        <td class="text-start ps-3 fw-bold text-dark">
                                                            <?= ucfirst($l['tipo_ave']) ?> (Enteros)
                                                        </td>
                                                        <td>
                                                            <?php if ($piezasUsadas > 0): ?>
                                                                <span class="text-danger fw-bold">-<?= $piezasUsadas ?> Pz</span> / <span class="text-primary"><?= $piezasRestantes ?> Pz</span>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($piezasUsadas > 0): ?>
                                                                <span class="text-danger fw-bold">-<?= number_format($kilosDescontados, 2) ?> Kg</span> / <span class="text-success"><?= number_format($kilosRestantesEnteros, 2) ?> Kg</span>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="fw-bold text-primary">
                                                            <?= $piezasRestantes ?> Pz / <?= number_format($kilosRestantesEnteros, 2) ?> Kg
                                                        </td>
                                                    </tr>

                                                    <!-- Renderizado dinámico de los cortes o mensaje de entero -->
                                                    <?php if (!empty($detallesCortes)): ?>
                                                        <?php foreach ($detallesCortes as $corte): ?>
                                                            <tr>
                                                                <td class="text-start ps-3 border-bottom">🔪 <?= esc($corte['producto_nombre']) ?> (Corte)</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td class="fw-bold text-success"><?= number_format($corte['cantidad_kg'], 2) ?> Kg</td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php elseif ($piezasUsadas == 0): ?>
                                                        <tr>
                                                            <td colspan="4" class="text-muted py-2 fst-italic">Sin despiece registrado (Todo el lote está entero)</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No hay lotes originales registrados en almacén.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISTA: MERMAS Y ALMACÉN (INVENTARIO GENERAL) -->
        <div id="vista-mermas" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-scale-balanced text-warning me-2"></i> Inventario General del Almacén y Mermas</h2>
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAjusteInventario">
                    <i class="fa-solid fa-sliders me-1"></i> Registrar Merma / Ajuste Manual
                </button>
                <!-- Botón para registrar gastos rápidos -->
                <button class="btn btn-outline-danger shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalRegistrarGasto">
                    <i class="fa-solid fa-receipt me-1"></i> Registrar Gasto Operativo
                </button>
                <!-- Botón para el Cierre de Día -->
                <button class="btn btn-dark shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalCierreDia" onclick="cargarResumenCierre()">
                    <i class="fa-solid fa-lock me-1"></i> Cierre de Jornada / Caja
                </button>
            </div>

            <!-- Tarjetas de Resumen de Almacén -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 bg-white">
                        <small class="text-muted fw-bold">Aves Vivas en Stock (Lotes)</small>
                        <h3 class="text-primary fw-bold mb-0" id="stockTotalAves">-- Pz</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 bg-white">
                        <small class="text-muted fw-bold">Kilos de Cortes Disponibles</small>
                        <h3 class="text-success fw-bold mb-0" id="stockTotalKilosCortes">-- Kg</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 bg-white">
                        <small class="text-muted fw-bold">Mermas Registradas Hoy</small>
                        <h3 class="text-danger fw-bold mb-0" id="mermasHoyAlmacen">-- Kg</h3>
                    </div>
                </div>
            </div>

            <!-- Tabla de Existencias en Almacén -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="fa-solid boxes-stacked me-2"></i> Existencias Actuales por Producto
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start ps-3">Producto / Presentación</th>
                                    <th>Unidad</th>
                                    <th>Entradas / Procesado</th>
                                    <th>Salidas (Vendidas Hoy)</th>
                                    <th>Stock Real Disponible</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tablaInventarioGeneral">
                                <tr><td colspan="6" class="text-center py-3 text-muted">Cargando inventario general...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- VISTA: CLIENTES -->
        <div id="vista-clientes" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-address-book text-primary me-2"></i> Directorio y Gestión de Clientes</h2>
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoClienteDirectorio" onclick="prepararModalClienteNuevo()">
                    <i class="fa-solid fa-user-plus me-1"></i> Registrar Nuevo Cliente
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3"># ID</th>
                                    <th>Nombre del Cliente</th>
                                    <th>Teléfono / WhatsApp</th>
                                    <th>Colonia / Dirección</th>
                                    <th>Referencia</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaDirectorioClientes">
                                <tr><td colspan="6" class="text-center py-3 text-muted">Cargando directorio de clientes...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
        <!-- VISTA 4: REPORTES Y ESTADÍSTICAS DE NEGOCIO -->
        <div id="vista-reportes" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-chart-pie text-danger me-2"></i> Reportes y Estadísticas de Negocio</h2>
                <div class="d-flex gap-2 align-items-center">
                    <input type="date" class="form-control form-control-sm" id="repFechaInicio" value="<?= date('Y-m-01') ?>">
                    <input type="date" class="form-control form-control-sm" id="repFechaFin" value="<?= date('Y-m-d') ?>">
                    <input type="text" class="form-control form-control-sm" id="repBusquedaHistorial" placeholder="Buscar pedido (Cliente/Tel/ID)..." style="width: 220px;">
                    <button class="btn btn-sm btn-dark" onclick="cargarReportesDinamicos()"><i class="fa-solid fa-filter me-1"></i> Filtrar</button>
                </div>
            </div>

            <!-- Pestañas internas de Reportes -->
            <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark" id="tab-merma" data-bs-toggle="tab" data-bs-target="#content-merma" type="button" onclick="cargarReportesDinamicos()">
                        <i class="fa-solid fa-scale-balanced text-warning me-1"></i> Volumen de Merma y Rendimiento
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="tab-fluctuacion" data-bs-toggle="tab" data-bs-target="#content-fluctuacion" type="button" onclick="cargarFluctuacionCompraVenta()">
                        <i class="fa-solid fa-arrow-trend-up text-success me-1"></i> Fluctuación de Lotes y Costos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="tab-rutas" data-bs-toggle="tab" data-bs-target="#content-rutas" type="button" onclick="cargarConcentradoColonias()">
                        <i class="fa-solid fa-map-location-dot text-primary me-1"></i> Concentrado por Colonia (Zonas)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="tab-historial" data-bs-toggle="tab" data-bs-target="#content-historial" type="button" onclick="cargarHistorialPedidosDinamico()">
                        <i class="fa-solid fa-clock-rotate-left text-info me-1"></i> Historial de Pedidos Pasados
                    </button>
                </li>
            </ul>

            <div class="tab-content card shadow-sm border-0 p-4 bg-white" id="reportTabsContent">
                
                <!-- CONTENIDO 1: MERMA Y RENDIMIENTO -->
                <div class="tab-pane fade show active" id="content-merma" role="tabpanel">
                    <h5 class="fw-bold mb-3">Análisis de Rendimiento de Materia Prima en Procesamiento</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="p-3 border rounded bg-light border-primary">
                                <small class="text-muted fw-bold">Peso Vivo Inicial Total</small>
                                <h4 class="text-primary fw-bold mb-0" id="lblPesoInicial">0.00 Kg</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded bg-light border-success">
                                <small class="text-muted fw-bold">Producto Útil Obtenido</small>
                                <h4 class="text-success fw-bold mb-0" id="lblPesoUtil">0.00 Kg</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded bg-light border-danger">
                                <small class="text-muted fw-bold">Merma Total</small>
                                <h4 class="text-danger fw-bold mb-0" id="lblMermaKg">0.00 Kg</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded bg-light border-warning">
                                <small class="text-muted fw-bold">% Promedio Merma</small>
                                <h4 class="text-dark fw-bold mb-0" id="lblMermaPorc">0.0%</h4>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted small"><i class="fa-solid fa-lightbulb text-warning me-1"></i> Los datos de este concentrado corresponden al cálculo acumulado de las mermas registradas en los despieces dentro del rango de fecha seleccionado.</p>
                </div>

                <!-- CONTENIDO 2: FLUCTUACIÓN DE LOTES -->
                <div class="tab-pane fade" id="content-fluctuacion" role="tabpanel">
                    <h5 class="fw-bold mb-3">Historial de Entrada de Lotes y Costos de Adquisición</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tablaLotesReporte">
                            <thead class="table-dark">
                                <tr>
                                    <th># Lote</th>
                                    <th>Proveedor</th>
                                    <th>Tipo Ave</th>
                                    <th>Costo Unitario</th>
                                    <th>Fecha Recepción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="5" class="text-center text-muted">Cargando fluctuación de lotes...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CONTENIDO 3: ZONAS PARA CAMPAÑAS -->
                <div class="tab-pane fade" id="content-rutas" role="tabpanel">
                    <h5 class="fw-bold mb-3">Concentrado de Pedidos por Colonia (Volumen de Venta por Sector)</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tablaZonasReporte">
                            <thead class="table-dark">
                                <tr>
                                    <th>Colonia / Sector</th>
                                    <th>Total Pedidos</th>
                                    <th>Volumen de Venta ($)</th>
                                    <th>Estado de Cobertura</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="4" class="text-center text-muted">Cargando datos por colonia...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CONTENIDO 4: HISTORIAL DE PEDIDOS PASADOS -->
                <div class="tab-pane fade" id="content-historial" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Registro Histórico Completo de Pedidos</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle" id="tablaHistorialPedidos">
                            <thead class="table-dark">
                                <tr>
                                    <th># Folio</th>
                                    <th>Cliente</th>
                                    <th>Teléfono</th>
                                    <th>Tipo Entrega</th>
                                    <th>Colonia</th>
                                    <th>Total ($)</th>
                                    <th>Estado</th>
                                    <th>Fecha y Hora</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="9" class="text-center text-muted">Cargando historial de pedidos...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- VISTA: GESTIÓN DE USUARIOS Y ROLES (SHIELD) -->
        <div id="vista-usuarios" class="vista-secccion d-none">
            <!-- Botón para abrir el modal de nuevo usuario (dentro de la vista de usuarios) -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-user-shield text-warning me-2"></i> Control de Usuarios y Permisos</h2>
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuarioShield">
                    <i class="fa-solid fa-user-plus me-1"></i> Registrar Nuevo Usuario
                </button>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3"># ID</th>
                                    <th>Usuario / Correo</th>
                                    <th>Estado</th>
                                    <th class="text-center">Rol Asignado (Grupo Shield)</th>
                                    <th class="text-center">Acción Rápida de Rol</th>
                                </tr>
                            </thead>
                            <tbody id="tablaUsuariosShield">
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">
                                        <i class="fa-solid fa-spinner fa-spin me-1"></i> Cargando lista de usuarios...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISTA: GESTIÓN DE SUCURSALES -->
        <div id="vista-sucursales" class="vista-secccion d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fa-solid fa-store text-warning me-2"></i> Gestión de Sucursales</h2>
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaSucursal" onclick="prepararModalSucursalNueva()">
                    <i class="fa-solid fa-plus me-1"></i> Registrar Nueva Sucursal
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3"># ID</th>
                                    <th>Nombre / Municipio</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaDirectorioSucursales">
                                <tr><td colspan="5" class="text-center py-3 text-muted">Cargando sucursales...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

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
                                <label class="form-label fw-bold">Teléfono (WhatsApp)</label>
                                <!-- Añadimos onblur o oninput para buscar automáticamente al escribir el número -->
                                <input type="text" class="form-control" id="pedTelefono" placeholder="Ej: 9931234567" oninput="buscarClientePorTelefono(this.value)" required>
                                <small class="text-muted">Escribe el número para autocompletar datos guardados.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre del Cliente</label>
                                <input type="text" class="form-control" id="pedCliente" placeholder="Ej: Juan Pérez" list="listaClientes" oninput="seleccionarClientePorNombre(this.value)" required>
                                <datalist id="listaClientes">
                                    <!-- Se llena dinámicamente con AJAX -->
                                </datalist>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tipo de Entrega</label>
                                <select class="form-select" id="pedTipoEntrega" onchange="toggleDireccion(this.value)">
                                    <option value="domicilio">Entrega a Domicilio</option>
                                    <option value="tienda">Recolección en Tienda</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tipo de Pago</label>
                                <select class="form-select" id="pedTipoPago" name="pedTipoPago" required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-md-4" id="divColonia">
                                <label class="form-label fw-bold">Colonia / Sector</label>
                                <input type="text" class="form-control" id="pedColonia" placeholder="Ej: Centro / Atasta">
                            </div>
                            <div class="col-md-12" id="divDireccion">
                                <label class="form-label fw-bold">Dirección Completa</label>
                                <input type="text" class="form-control" id="pedDireccion" placeholder="Calle, número exterior/interior">
                            </div>
                            <!-- NUEVO APARTADO DE REFERENCIAS PARA DOMICILIO -->
                            <div class="col-md-12" id="divReferencia">
                                <label class="form-label fw-bold text-secondary"><i class="fa-solid fa-map-pin me-1"></i> Referencias de Ubicación</label>
                                <textarea class="form-control" id="pedReferencia" rows="2" placeholder="Ej: Casa de dos plantas color verde, frente al parque, zaguán negro..."></textarea>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-basket-shopping me-2"></i> Detalle de Productos</h6>
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-md-5">
                                <select class="form-select" id="selProducto">
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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-secondary mb-0"><i class="fa-solid fa-basket-shopping me-2"></i> Detalle de Productos</h6>
                            <button type="button" class="btn btn-sm btn-outline-warning fw-bold" onclick="autorizarDescuentoNIP()">
                                <i class="fa-solid fa-key me-1"></i> Autorizar Descuento (NIP)
                            </button>
                        </div>

                        <table class="table table-sm mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio U.</th>
                                    <th>Subtotal</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyDetallePedido">
                                <tr><td colspan="5" class="text-center text-muted fs-7">No se han añadido productos.</td></tr>
                            </tbody>
                        </table>

                        <div class="text-end fw-bold fs-5 text-success mt-2">
                            Total: $<span id="lblTotalPedido">0.00</span>
                        </div>
                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" onclick="guardarPedidoAjax(true)">
                                    <i class="fa-solid fa-print me-1"></i> Guardar e Imprimir Ticket
                                </button>
                                <!--
                                <button type="button" class="btn btn-success" onclick="guardarPedidoAjax(false)">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Solo Guardar
                                </button>-->
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
                            <h6 class="fw-bold mb-0 text-uppercase" id="tickBrandNombre"><?= esc($nombreEmpresa ?? 'EXPENDIO AVÍCOLA') ?></h6>
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

    <?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
    <!-- Modal Registrar Proveedor -->
    <div class="modal fade" id="modalNuevoProveedor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-truck-field me-2"></i> Alta de Nuevo Proveedor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formNuevoProveedorDb">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre de la Empresa / Granja</label>
                            <input type="text" class="form-control" id="provEmpresa" name="provEmpresa" placeholder="Ej: Avícola San Antonio" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Persona de Contacto</label>
                            <input type="text" class="form-control" id="provContacto" name="provContacto" placeholder="Ej: Juan Pérez">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Teléfono de Contacto</label>
                            <input type="text" class="form-control" id="provTelefono" name="provTelefono" placeholder="Ej: 9931234567" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" onclick="guardarProveedorAjax()">
                            <i class="fa-solid fa-check me-1"></i> Guardar Proveedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Registrar Nuevo Lote (Actualizado con Peso Unitario y Cálculo Automático de Peso Total) -->
    <div class="modal fade" id="modalNuevoLote" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-truck-ramp-box me-2"></i> Capturar Nuevo Lote</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <form id="formNuevoLoteDb" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Proveedor</label>
                                <select class="form-select" id="mockProveedor" name="mockProveedor" required>
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
                                <select class="form-select" id="mockTipo" name="mockTipo" onchange="cambiarLogicaTipoAve(this.value)" required>
                                    <option value="pollo">Pollo (Compra por Peso / Jaulas)</option>
                                    <option value="gallina">Gallina (Compra por Pieza / Jaulas)</option>
                                </select>
                            </div>
                            
                            <!-- Control de Jaulas y Piezas -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Número de Jaulas</label>
                                <input type="number" class="form-control" id="loteJaulas" name="jaulas" placeholder="Ej: 10" oninput="calcularTotalesLote()" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Piezas por Jaula</label>
                                <input type="number" class="form-control" id="lotePiezasPorJaula" name="piezas_por_jaula" placeholder="Ej: 12" oninput="calcularTotalesLote()" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-primary">Total de Piezas (Calculado)</label>
                                <input type="number" class="form-control bg-light fw-bold text-primary" id="lotePiezas" name="mockPiezas" readonly required>
                            </div>

                            <!-- NUEVO CAMPO: PESO UNITARIO -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold" id="lblPesoUnitario">Peso Unitario (Kg) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="lotePesoUnitario" name="peso_unitario" placeholder="Ej: 2.30" oninput="calcularTotalesLote()">
                                <small class="text-muted" id="helpPesoUnit">Peso promedio por ave.</small>
                            </div>

                            <!-- PESO TOTAL CALCULADO AUTOMÁTICAMENTE -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-success">Peso Total (Kg) (Calculado)</label>
                                <input type="number" step="0.01" class="form-control bg-light fw-bold text-success" id="lotePesoTotal" name="mockPeso" readonly required>
                                <small class="text-muted" id="helpPesoTotal">Piezas × Peso Unitario</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold" id="lblCostoUnitario">Costo Unitario ($)</label>
                                <input type="number" step="0.50" class="form-control" id="mockCosto" name="mockCosto" placeholder="Ej: 95.00" required>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <label class="form-label fw-bold"><i class="fa-solid fa-file-arrow-up text-primary me-1"></i> Comprobante / Nota del Proveedor (Opcional)</label>
                            <input type="file" class="form-control" id="mockComprobante" name="mockComprobante" accept="image/*,application/pdf">
                            <div class="form-text">Puedes subirlo ahora o adjuntarlo posteriormente.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" onclick="guardarLoteAjax()"><i class="fa-solid fa-check me-1"></i> Guardar Lote en BD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

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
                                <option value="">Cargando repartidores...</option>
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

    <!-- Modal Previsualización de Comprobante -->
    <div class="modal fade" id="modalVerComprobante" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white py-2">
                    <h6 class="modal-title mb-0"><i class="fa-solid fa-file-lines me-2"></i> Comprobante / Nota del Proveedor</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center bg-light p-3" id="contenedorComprobante">
                    <!-- Se inyectará dinámicamente la imagen o el visor PDF -->
                </div>
                <div class="modal-footer py-2">
                    <a id="btnDescargarComprobante" href="#" target="_blank" class="btn btn-sm btn-outline-dark">
                        <i class="fa-solid fa-download me-1"></i> Abrir en pestaña nueva
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
    <!-- Modal para Registrar Nuevo Producto -->
    <div class="modal fade" id="modalNuevoProducto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h5 class="modal-title fs-6"><i class="fa-solid fa-box-open me-2"></i> Nuevo Producto al Catálogo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoProducto">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nuevoNombreProd" required placeholder="Ej. Pechuga Deshebrada">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de Unidad</label>
                            <select class="form-select" id="nuevoUnidadProd">
                                <option value="kilos">Kilos</option>
                                <option value="piezas">Piezas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Precio de Venta Actual ($)</label>
                            <input type="number" step="0.50" class="form-control" id="nuevoPrecioProd" required placeholder="0.00">
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-sm" onclick="guardarNuevoProducto()">Guardar Producto</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Repartidor -->
    <div class="modal fade" id="modalNuevoRepartidor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-dark py-2">
                    <h5 class="modal-title fs-6"><i class="fa-solid fa-user-plus me-2"></i> Nuevo Repartidor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoRepartidor">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" class="form-control" id="repNombre" required placeholder="Ej. Carlos Mendoza">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Teléfono / WhatsApp</label>
                            <input type="text" class="form-control" id="repTelefono" required placeholder="Ej. 9931234567">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Vehículo</label>
                            <select class="form-select" id="repVehiculo">
                                <option value="Motocicleta">Motocicleta</option>
                                <option value="Bicicleta">Bicicleta</option>
                                <option value="Automóvil">Automóvil</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-sm" id="btnGuardarRepartidor" onclick="guardarRepartidor()">Guardar Repartidor</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Realizar Despiece de Lote Avanzado -->
    <div class="modal fade" id="modalNuevoDespiece" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-scissors me-2"></i> Realizar Despiece de Lote</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formDespieceOperativo">
                        <!-- 1. Selección de Lote -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Seleccionar Lote de Origen</label>
                            <select class="form-select" id="despieceLoteId" onchange="cargarDatosLoteOrigen(this)" required>
                                <option value="">Seleccione un lote...</option>
                                <?php if (!empty($lotes)): ?>
                                    <?php foreach ($lotes as $l): ?>
                                        <?php 
                                            // Comprobar si este lote ya cuenta con un registro de despiece
                                            $yaProcesado = false;
                                            if (!empty($procesos)) {
                                                foreach ($procesos as $p) {
                                                    if ($p['lote_id'] == $l['id']) {
                                                        $yaProcesado = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        ?>
                                        
                                        <!-- Mostrar únicamente si NO ha sido procesado -->
                                        <?php if (!$yaProcesado): ?>
                                            <option value="<?= $l['id'] ?>" data-piezas="<?= $l['piezas'] ?>" data-peso="<?= $l['peso'] ?>">
                                                Lote #<?= $l['id'] ?> - <?= ucfirst($l['tipo_ave']) ?> (<?= $l['piezas'] ?> Pz / <?= $l['peso'] ?> Kg)
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted" id="infoLoteOriginal">Al seleccionar un origen se calculará automáticamente el resto del lote.</small>
                        </div>

                        <!-- 2. Piezas a Descontar del Original para los cortes -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Piezas a Destinar para Cortes</label>
                                <input type="number" class="form-control" id="piezasADestinar" value="" oninput="calcularRestoDespiece()" required>
                            </div>
                        </div>

                        <!-- 3. Tabla de Cortes y Kilos Usables -->
                        <h6 class="fw-bold text-secondary mb-2"><i class="fa-solid fa-basket-shopping me-1"></i> Cortes / Subproductos a Obtener:</h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start">Cortes</th>
                                        <th style="width: 150px;">Kilos Usables (Kg)</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCortesCatalogo">
                                    <?php if (!empty($productos)): ?>
                                        <?php foreach ($productos as $p): ?>
                                            <?php if ($p['estado'] == 1 && $p['tipo_unidad'] === 'kilos'): ?>
                                                <tr>
                                                    <td class="text-start ps-3 fw-bold">🔪 <?= esc($p['nombre']) ?></td>
                                                    <td>
                                                        <input type="number" 
                                                            step="0.01" 
                                                            min="0" 
                                                            class="form-control form-control-sm text-center corte-input" 
                                                            data-producto-id="<?= $p['id'] ?>" 
                                                            data-producto="<?= esc($p['nombre']) ?>" 
                                                            placeholder="0.00">
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-2">No hay cortes configurados en el catálogo (unidad: kilos).</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- 4. Resto de Lote (Resultado de Enteros) -->
                        <div class="card bg-light border-0 p-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-box-archive me-1"></i> Resto de Lote (Enteros para Venta)</h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <span class="text-muted d-block small">Piezas Restantes</span>
                                    <span class="fs-5 fw-bold text-primary" id="restPz">80 Pz</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Kilos Restantes</span>
                                    <span class="fs-5 fw-bold text-success" id="restKg">240 Kg</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnAplicarDespiece" class="btn btn-success btn-sm fw-bold" onclick="guardarDespieceOperativo()">
                        <i class="fa-solid fa-check me-1"></i> Aplicar Transformación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Merma o Ajuste Manual -->
    <div class="modal fade" id="modalAjusteInventario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i> Registrar Merma o Ajuste de Almacén</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formAjusteMerma">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Seleccionar Producto / Artículo</label>
                            <select class="form-select" id="ajusteProductoId" required>
                                <option value="">Cargando productos...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de Movimiento</label>
                            <select class="form-select" id="ajusteTipo" required>
                                <option value="merma">Merma / Pérdida (Resta al stock)</option>
                                <option value="entrada">Ajuste Positivo / Sobrante (Suma al stock)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cantidad (Kilos o Piezas)</label>
                            <input type="number" step="0.01" class="form-control" id="ajusteCantidad" placeholder="Ej: 2.50" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Motivo u Observaciones</label>
                            <textarea class="form-control" id="ajusteMotivo" rows="2" placeholder="Ej: Daño por manipulación, deshidratación, error de conteo..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" onclick="guardarAjusteMermaAjax()">
                            <i class="fa-solid fa-check me-1"></i> Registrar Ajuste
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modal Tabla Rápida de Stock Disponible vs Vendido -->
    <div class="modal fade" id="modalStockDisponibilidad" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-boxes-stacked me-2 text-warning"></i> Inventario de Productos y Disponibilidad</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-start ps-3">Producto para Venta</th>
                                    <!--
                                    <th class="fade">Stock Usable (Procesado)</th>
                                    <th class="fade">Vendidos Hoy</th>
                                    -->
                                    <th>Quedan Disponibles (Tenemos)</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyConsultaStockRapido">
                                <tr><td colspan="4" class="text-center py-3 text-muted">Cargando inventarios...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar / Editar Cliente -->
    <div class="modal fade" id="modalNuevoClienteDirectorio" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tituloModalCliente"><i class="fa-solid fa-user-plus me-2"></i> Alta de Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formClienteDirectorio">
                    <!-- Campo oculto para detectar si es edición -->
                    <input type="hidden" id="dirClienteId" value="">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Teléfono (WhatsApp)</label>
                            <input type="text" class="form-control" id="dirTelefono" placeholder="Ej: 9931234567" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" class="form-control" id="dirNombre" placeholder="Ej: Juan Pérez" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de Entrega Frecuente</label>
                            <select class="form-select" id="dirTipoEntrega">
                                <option value="domicilio">Entrega a Domicilio</option>
                                <option value="tienda">Recolección en Tienda</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Colonia / Sector</label>
                            <input type="text" class="form-control" id="dirColonia" placeholder="Ej: Centro / Atasta">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dirección Completa</label>
                            <textarea class="form-control" id="dirDireccion" rows="2" placeholder="Calle, número exterior/interior"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Referencias de Ubicación</label>
                            <textarea class="form-control" id="dirReferencia" rows="2" placeholder="Frente al parque, zaguán negro..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" onclick="guardarClienteDirectorioAjax()">
                            <i class="fa-solid fa-check me-1"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Registrar Gasto Operativo (Caja Chica) -->
    <div class="modal fade" id="modalRegistrarGasto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-wallet me-2"></i> Registrar Gasto Operativo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formRegistrarGasto">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Categoría de Gasto</label>
                            <select class="form-select" id="gastoTipo" required>
                                <option value="gasolina">Gasolina de Moto / Reparto</option>
                                <option value="saldo">Saldo Celular Repartidores</option>
                                <option value="bolsas">Bolsas / Empaque</option>
                                <option value="limpieza">Artículos de Limpieza</option>
                                <option value="varios">Varios / Otros</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Monto ($)</label>
                            <input type="number" step="0.50" class="form-control" id="gastoMonto" placeholder="Ej: 50.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción / Observación</label>
                            <textarea class="form-control" id="gastoDescripcion" rows="2" placeholder="Ej: Gasolina para la moto 1 de Carlos" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" onclick="guardarGastoOperativoAjax()">
                            <i class="fa-solid fa-check me-1"></i> Guardar Gasto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Cierre de Jornada y Arqueo de Caja -->
    <div class="modal fade" id="modalCierreDia" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-lock me-2 text-warning"></i> Cierre de Jornada y Arqueo de Caja</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <div class="p-3 bg-light border rounded">
                                <h6 class="fw-bold text-secondary mb-2"><i class="fa-solid fa-calculator me-1"></i> Resumen Financiero del Día</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td>💵 Fondo Inicial de Caja:</td>
                                        <td class="text-end fw-bold" id="cierreFondo">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>➕ Ventas Totales en Efectivo:</td>
                                        <td class="text-end fw-bold text-success" id="cierreVentasEfectivo">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>➖ Gastos Operativos (Gasolina/Saldo/Bolsas):</td>
                                        <td class="text-end fw-bold text-danger" id="cierreTotalGastos">$0.00</td>
                                    </tr>
                                    <!-- Contenedor oculto con la lista de los gastos detallados -->
                                    <tr id="filaDetalleGastos" style="display: none; background-color: #f8f9fa;">
                                        <td colspan="2" class="p-2">
                                            <div class="small text-secondary border rounded p-2 bg-white">
                                                <strong class="d-block mb-1 text-dark">📋 Desglose de Gastos Operativos del Día:</strong>
                                                <ul class="mb-0 ps-3" id="listaGastosDetalle">
                                                    <!-- Se inyecta por JS -->
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="fw-bold text-dark pt-2">💰 EFECTIVO ESPERADO EN CAJA:</td>
                                        <td class="text-end fw-bold text-primary fs-5 pt-2" id="cierreEfectivoEsperado">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><small>📱 Ventas por Transferencia (No cuentan en caja física):</small></td>
                                        <td class="text-end text-muted"><small id="cierreVentasTransferencia">$0.00</small></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <form id="formProcesarCierre">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">💵 Efectivo Real Contado en el Cajón:</label>
                            <input type="number" step="0.50" class="form-control form-control-lg fw-bold text-success" id="cierreEfectivoReal" placeholder="0.00" oninput="calcularDiferenciaCierre()" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Diferencia (Sobrante / Faltante):</label>
                            <div class="p-2 border rounded bg-white text-center">
                                <span class="fs-4 fw-bold" id="lblDiferenciaCierre">$0.00</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Observaciones del Cierre</label>
                            <textarea class="form-control" id="cierreObservaciones" rows="2" placeholder="Ej: Caja cuadrada sin novedad / Faltó $20 por cambio..."></textarea>
                        </div>
                    </form>
                    <!-- Mini-Resumen de Inventario Restante -->
                    <div class="card bg-light border p-2 mb-3">
                        <div class="fw-bold mb-1"><i class="fa-solid fa-boxes-stacked me-2"></i> Stock de Aves / Productos para Mañana:</div>
                        <div id="resumenStockDesglosado" class="small text-secondary">
                            <!-- Aquí se inyectará el listado detallado mediante JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success fw-bold" onclick="procesarCierreDefinitivoAjax()">
                        <i class="fa-solid fa-shield-halved me-1"></i> Cerrar Día Oficialmente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Seguridad para Autorización de NIP -->
    <div class="modal fade" id="modalNipSeguridad" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-dark py-2">
                    <h6 class="modal-title fw-bold"><i class="fa-solid fa-lock me-1"></i> Autorización Gerencial</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="small text-muted mb-2">Ingresa el NIP para desbloquear descuentos:</p>
                    <input type="password" id="inputNipSecreto" class="form-control text-center fw-bold fs-4" placeholder="••••" maxlength="4" autofocus>
                </div>
                <div class="modal-footer py-2 justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-sm fw-bold" onclick="verificarNipSeguridad()">Autorizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Rápido para Subir / Actualizar Comprobante de Lote -->
    <div class="modal fade" id="modalSubirComprobanteRapido" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title mb-0"><i class="fa-solid fa-upload me-1"></i> Adjuntar Comprobante</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formSubirComprobanteRapido" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" id="subirLoteId" name="lote_id">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Selecciona imagen o PDF:</label>
                            <input type="file" class="form-control form-control-sm" id="archivoComprobanteRapido" name="mockComprobante" accept="image/*,application/pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer py-1">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <!-- Agregamos id="btnGuardarComprobante" -->
                        <button type="button" id="btnGuardarComprobante" class="btn btn-success btn-sm" onclick="enviarComprobanteRapidoAjax()">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Verificación de Transferencia -->
    <div class="modal fade" id="modalVerificarTransferencia" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title fw-bold"><i class="fa-solid fa-university me-1"></i> Verificación de Transferencia</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" id="verificarPedidoId" value="">
                    <p class="mb-1 text-muted small">Folio: <strong id="verificarFolioTxt" class="text-dark"></strong></p>
                    <p class="mb-2 text-muted small">Cliente: <strong id="verificarClienteTxt" class="text-dark"></strong></p>
                    <h4 class="text-success fw-bold mb-3" id="verificarMontoTxt">$0.00</h4>
                    <p class="small text-dark mb-0">¿Confirmas que el dinero ya se reflejó en las cuentas de la empresa?</p>
                </div>
                <div class="modal-footer py-2 justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-sm fw-bold" onclick="confirmarLlegadaTransferencia()">
                        <i class="fa-solid fa-check me-1"></i> Sí, ya llegó
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle de Pedido Histórico -->
    <div class="modal fade" id="modalDetalleHistorico" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-receipt me-2"></i> Productos del Pedido #<span id="lblIdPedidoModal"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start">Producto ID</th>
                                <th>Cantidad / Kg</th>
                                <th>Precio Unitario</th>
                                <th class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDetalleHistorico">
                            <!-- Items del pedido -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Perfil -->
    <div class="modal fade" id="modalEditarPerfil" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-dark text-white py-2">
                    <h6 class="modal-title fw-bold"><i class="fa-solid fa-user-pen me-1"></i> Modificar Nombre de Usuario</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPerfil">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nuevo Nombre de Usuario</label>
                            <input type="text" class="form-control" id="inputNuevoUsername" value="<?= auth()->user()->username ?? '' ?>" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-sm fw-bold" onclick="guardarNuevoUsername()">
                        <i class="fa-solid fa-check me-1"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
    <!-- Modal Registrar Nuevo Usuario (Interno) -->
    <div class="modal fade" id="modalNuevoUsuarioShield" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-plus me-2 text-warning"></i> Alta de Nuevo Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formNuevoUsuarioShield">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="shUsername" placeholder="Ej: carlos_reparto" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="shEmail" placeholder="Ej: carlos@expendio.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña Temporal</label>
                            <input type="password" class="form-control" id="shPassword" placeholder="••••••••" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rol / Grupo Asignado</label>
                            <select class="form-select" id="shGrupo" required>
                                <option value="gerente">Gerente</option>
                                <option value="mostrador">Mostrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success fw-bold" onclick="guardarUsuarioShieldAjax()">
                            <i class="fa-solid fa-check me-1"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Configuración de Plataforma (Exclusivo Superadmin) -->
    <div class="modal fade" id="modalConfiguracionSistema" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-dark text-white py-2">
                    <h6 class="modal-title fw-bold"><i class="fa-solid fa-gears me-1 text-warning"></i> Configuración de la Empresa</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formConfiguracionSistema">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nombre Comercial de la Empresa</label>
                            <input type="text" class="form-control" id="inputCompanyNombre" placeholder="Ej: EXPENDIO AVÍCOLA" required>
                            <small class="text-muted">Este nombre se actualizará en la barra de navegación superior y en los tickets de venta impresos.</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-sm fw-bold" onclick="guardarConfiguracionSistema()">
                        <i class="fa-solid fa-check me-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Registrar / Editar Sucursal -->
    <div class="modal fade" id="modalNuevaSucursal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tituloModalSucursal"><i class="fa-solid fa-store me-2"></i> Alta de Sucursal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formSucursalDirectorio">
                    <input type="hidden" id="sucursalId" value="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre / Municipio</label>
                            <input type="text" class="form-control" id="sucNombre" placeholder="Ej: Expendio Comalcalco" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dirección</label>
                            <textarea class="form-control" id="sucDireccion" rows="2" placeholder="Calle, número, colonia..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Teléfono</label>
                            <input type="text" class="form-control" id="sucTelefono" placeholder="Ej: 9931234567">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" onclick="guardarSucursalAjax()">
                            <i class="fa-solid fa-check me-1"></i> Guardar Sucursal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Editar Usuario Completo -->
    <div class="modal fade" id="modalEditarUsuarioCompleto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="tituloEditUser"><i class="fa-solid fa-user-pen me-2 text-warning"></i> Editar Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarUsuarioCompleto">
                    <input type="hidden" id="editUserId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="editUsername" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nueva Contraseña <span class="text-muted fw-normal">(Dejar en blanco si no deseas cambiarla)</span></label>
                            <input type="password" class="form-control" id="editPassword" placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rol / Grupo Asignado</label>
                            <select class="form-select" id="editGrupo" required>
                                <option value="superadmin">Super Admin</option>
                                <option value="gerente">Gerente</option>
                                <option value="mostrador">Mostrador</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIP de Autorización (4 Dígitos)</label>
                            <input type="password" class="form-control text-center fw-bold fs-5" id="editNip" name="nip" placeholder="••••" maxlength="4" pattern="[0-9]*" inputmode="numeric">
                            <small class="text-muted">Utilizado para autorizar descuentos y operaciones especiales en mostrador.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Estado del Usuario</label>
                            <select class="form-select" id="editActive" required>
                                <option value="1">Activo (Permitir acceso)</option>
                                <option value="0">Inactivo (Desactivar acceso)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sucursal Asignada</label>
                            <select class="form-select" id="editSucursalId" name="sucursal_id">
                                <option value="">-- Seleccionar Sucursal --</option>
                                <?php if (!empty($sucursales)): ?>
                                    <?php foreach ($sucursales as $s): ?>
                                        <option value="<?= $s['id'] ?>"><?= esc($s['nombre_municipio']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Si es superadmin, puede dejarse sin sucursal fija.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success fw-bold" onclick="guardarEdicionUsuarioAjax()">
                            <i class="fa-solid fa-check me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
function cambiarVista(vista, elemento) {
    document.querySelectorAll('.vista-secccion').forEach(el => el.classList.add('d-none'));
    document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));

    document.getElementById(`vista-${vista}`).classList.remove('d-none');
    if (elemento) {
        elemento.classList.add('active');
    }
}

function simularGuardarPrecio(id) {
    let val = document.getElementById(`inputP_${id}`).value;
    alert(`¡Precio del producto ID ${id} actualizado a $${val} (Simulación)!`);
}

let itemsTemporal = [];

// En DOMContentLoaded agrega renderPedidos();

// 1. Eliminar la recarga del evento submit del formulario
document.getElementById('formMockPedido').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (typeof itemsTemporal === 'undefined' || itemsTemporal.length === 0) {
        alert('Debes agregar al menos un producto al pedido.');
        return;
    }
    
    let total = itemsTemporal.reduce((sum, item) => sum + item.subtotal, 0);
    let cliente = document.getElementById('pedCliente').value;
    let telefono = document.getElementById('pedTelefono').value;
    let tipoEntrega = document.getElementById('pedTipoEntrega').value;
    let colonia = document.getElementById('pedColonia') ? document.getElementById('pedColonia').value : '';
    let direccion = document.getElementById('pedDireccion').value;
    let referencia = document.getElementById('pedReferencia').value;

    let formData = new FormData();
    formData.append('cliente', cliente);
    formData.append('telefono', telefono);
    formData.append('tipo_entrega', tipoEntrega);
    formData.append('colonia', colonia);
    formData.append('direccion', direccion);
    formData.append('referencia', referencia);
    formData.append('total', total);
    formData.append('items', JSON.stringify(itemsTemporal));
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/pedidos/guardar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            let modalEl = document.getElementById('modalNuevoPedido');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            if (window.modoImpresion) {
                document.getElementById('tickFolio').innerText = `FOLIO: #${data.folio}`;
                document.getElementById('tickFecha').innerText = new Date().toLocaleString();
                document.getElementById('tickCliente').innerText = cliente;
                document.getElementById('tickTel').innerText = telefono;
                document.getElementById('tickTipo').innerText = tipoEntrega.toUpperCase();
                document.getElementById('tickDir').innerText = tipoEntrega === 'tienda' ? 'Recoge en mostrador' : `${direccion} (Ref: ${referencia})`;
                document.getElementById('tickRepartidor').innerText = tipoEntrega === 'tienda' ? 'N/A' : 'Sin asignar';

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

                let modalTicket = new bootstrap.Modal(document.getElementById('modalTicketPrint'));
                modalTicket.show();
            } else {
                alert('¡Pedido registrado con éxito en la base de datos!');
            }

            // Limpiar variables temporales y formulario sin recargar la página
            itemsTemporal = [];
            document.getElementById('tbodyDetallePedido').innerHTML = '<tr><td colspan="5" class="text-center text-muted fs-7">No se han añadido productos.</td></tr>';
            document.getElementById('lblTotalPedido').innerText = '0.00';
            this.reset();
            actualizarMetricasCards();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud.');
    });
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
// Añadir producto al carrito temporal
function agregarItemPedido() {
    let selectEl = document.getElementById('selProducto');
    if (!selectEl.value) return;

    let raw = selectEl.value.split('|');
    let nombreProducto = raw[0];
    let precio = parseFloat(raw[1]);
    let unidadMedida = raw[2]; // 'kilos' o 'piezas'
    
    // 1. Stock total disponible en el sistema
    let stockDisponible = parseFloat(selectEl.options[selectEl.selectedIndex].getAttribute('data-stock')) || 0;
    let cantSolicitada = parseFloat(document.getElementById('cantProducto').value);
    
    if (cantSolicitada <= 0) {
        alert('Por favor ingresa una cantidad mayor a cero.');
        return;
    }

    // 2. Calcular cuánto de este mismo producto YA se agregó en el carrito temporal actual
    let cantidadYaEnCarrito = 0;
    itemsTemporal.forEach(item => {
        if (item.nombre === nombreProducto) {
            cantidadYaEnCarrito += parseFloat(item.cant);
        }
    });

    // 3. Sumar lo que ya está en el carrito + lo que intenta agregar ahora
    let totalAcumuladoPrevisto = cantidadYaEnCarrito + cantSolicitada;

    // 4. CANDADO ESTRICTO: Bloquear si se supera el stock total
    if (totalAcumuladoPrevisto > stockDisponible) {
        let disponibleReal = Math.max(0, stockDisponible - cantidadYaEnCarrito);
        alert(`⚠️ ¡Stock insuficiente!\n\nEstás intentando acumular un total de ${totalAcumuladoPrevisto.toFixed(2)} ${unidadMedida} de "${nombreProducto}", pero tu límite de stock disponible es de ${stockDisponible.toFixed(2)} ${unidadMedida}.\n\n(Ya tienes ${cantidadYaEnCarrito.toFixed(2)} ${unidadMedida} en este pedido. Solo te quedan disponibles ${disponibleReal.toFixed(2)} ${unidadMedida}).`);
        return; // Detenemos la acción para proteger el inventario
    }

    let subtotal = precio * cantSolicitada;
    itemsTemporal.push({ nombre: nombreProducto, precio: precio, cant: cantSolicitada, subtotal: subtotal });

    renderizarDetallePedido();
    document.getElementById('cantProducto').value = '1';
}

function agregarItemPedido2() {
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

function cambiarEstadoPedido(id, nuevoEstado, tipoEntrega, repartidorActual) {
    // 🔔 ALARMA / ADVERTENCIA: Si es a domicilio y sigue sin repartidor asignado (no pasó por el manifiesto)
    if (tipoEntrega === 'domicilio' && (!repartidorActual || repartidorActual.includes('Por asignar') || repartidorActual === 'Sin asignar') && nuevoEstado === 'entregado') {
        let confirmarEntregaDirecta = confirm(
            "⚠️ ¡Aviso de Almacén / Reparto!\n\n" +
            "Este pedido es a Domicilio y figura como 'Por asignar' (no fue agrupado en ningún 'Viaje de Reparto').\n\n" +
            "¿Deseas marcarlo como entregado de forma directa de todos modos?"
        );
        
        if (!confirmarEntregaDirecta) {
            return; // Se cancela la acción si el usuario prefiere asignarlo desde el modal de manifiesto
        }
    }

    let formData = new FormData();
    formData.append('id', id);
    formData.append('estado', nuevoEstado);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/pedidos/actualizar-estado') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // ACTUALIZACIÓN VISUAL EN TIEMPO REAL (SIN RECARGAR LA PÁGINA)
            let filas = document.querySelectorAll('#tablaPedidosDb tr');
            filas.forEach(fila => {
                let badgeFolio = fila.querySelector('span.badge');
                if (badgeFolio && badgeFolio.textContent.trim() === `#${id}`) {
                    let celdas = fila.querySelectorAll('td');
                    if (celdas.length >= 9) {
                        
                        // 1. Actualizar la columna de Estado (índice 7)
                        if (nuevoEstado === 'entregado') {
                            celdas[7].innerHTML = `<span class="badge bg-success">Entregado</span>`;
                            
                            // 2. Actualizar la columna de Acción (índice 8) para quitar el botón de entregar y poner "Completo"
                            let tdAccion = celdas[8].querySelector('div');
                            if (tdAccion) {
                                let btnImprimir = tdAccion.querySelector('button[title="Reimprimir Ticket"]').outerHTML;
                                tdAccion.innerHTML = `
                                    ${btnImprimir}
                                    <span class="badge bg-light text-success border align-self-center"><i class="fa-solid fa-circle-check"></i> Completo</span>
                                `;
                            }
                        } else if (nuevoEstado === 'en_camino') {
                            celdas[7].innerHTML = `<span class="badge bg-info text-white">En Camino</span>`;
                        }
                    }
                }
            });
            actualizarMetricasCards();
        } else {
            alert('No se pudo actualizar el estado.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function ejecutarCambioEstado(id, nuevoEstado, repartidor) {
    let formData = new FormData();
    formData.append('id', id);
    formData.append('estado', nuevoEstado);
    if (repartidor) {
        formData.append('repartidor', repartidor);
    }
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/pedidos/actualizar-estado') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // ACTUALIZACIÓN VISUAL EN TIEMPO REAL (SIN RECARGAR)
            let filas = document.querySelectorAll('#tablaPedidosDb tr');
            filas.forEach(fila => {
                let badgeFolio = fila.querySelector('span.badge');
                if (badgeFolio && badgeFolio.textContent.trim() === `#${id}`) {
                    let celdas = fila.querySelectorAll('td');
                    if (celdas.length >= 9) {
                        
                        // Actualizar Repartidor si se asignó uno nuevo (Índice 5)
                        if (repartidor) {
                            celdas[5].innerHTML = `<span class="fw-bold text-dark"><i class="fa-solid fa-motorcycle text-primary me-1"></i> ${repartidor}</span>`;
                        }

                        // Actualizar la columna de Estado (Índice 7)
                        if (nuevoEstado === 'entregado') {
                            celdas[7].innerHTML = `<span class="badge bg-success">Entregado</span>`;
                            
                            // Actualizar la columna de Acción (Índice 8)
                            let tdAccion = celdas[8].querySelector('div');
                            if (tdAccion) {
                                let btnImprimir = tdAccion.querySelector('button[title="Reimprimir Ticket"]').outerHTML;
                                tdAccion.innerHTML = `
                                    ${btnImprimir}
                                    <span class="badge bg-light text-success border align-self-center"><i class="fa-solid fa-circle-check"></i> Completo</span>
                                `;
                            }
                        } else if (nuevoEstado === 'en_camino') {
                            celdas[7].innerHTML = `<span class="badge bg-info text-white">En Camino</span>`;
                        }
                    }
                }
            });
            actualizarMetricasCards();
        } else {
            alert('No se pudo actualizar el estado.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function toggleDireccion(val) {
    let show = val === 'domicilio';
    document.getElementById('divColonia').style.display = show ? 'block' : 'none';
    document.getElementById('divDireccion').style.display = show ? 'block' : 'none';
    // Se elimina la línea que manipulaba 'divRepartidor'
}
/** 
let proveedoresMock = [
    { id: 1, empresa: 'Avícola San Antonio', contacto: 'Juan Pérez', telefono: '9931234567', fecha: '2026-10-01' },
    { id: 2, empresa: 'Granja El Gallero', contacto: 'María López', telefono: '9937654321', fecha: '2026-10-05' }
];
*/
// Ejecutar al cargar
//renderProveedores();

/**
 * 
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
 */

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
    //renderProveedores();
    document.getElementById('formMockProveedor').reset();
    bootstrap.Modal.getInstance(document.getElementById('modalNuevoProveedor')).hide();
    alert('¡Proveedor registrado con éxito en el cascarón!');
}

function eliminarProveedor(id) {
    if(confirm('¿Deseas eliminar este proveedor?')) {
        proveedoresMock = proveedoresMock.filter(p => p.id !== id);
        //renderProveedores();
    }
}
function imprimirTicketDirecto() {
    window.print();
}
function reimprimirTicket(ped) {
    // Consultar los productos específicos de este pedido en la base de datos
    fetch(`<?= base_url('/pedidos/detalle-json/') ?>/${ped.id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(items => {
        // Rellenar cabecera y datos del cliente en el ticket térmico
        document.getElementById('tickFolio').innerText = `FOLIO: #${ped.id}`;
        document.getElementById('tickFecha').innerText = new Date().toLocaleString() + ' (REIMPRESIÓN)';
        document.getElementById('tickCliente').innerText = ped.cliente;
        document.getElementById('tickTel').innerText = ped.tel;
        document.getElementById('tickTipo').innerText = ped.tipo.toUpperCase();
        document.getElementById('tickDir').innerText = ped.direccion;
        document.getElementById('tickRepartidor').innerText = ped.repartidor;

        let htmlBody = '';
        if (items && items.length > 0) {
            items.forEach(i => {
                htmlBody += `
                    <tr>
                        <td>${i.cantidad}</td>
                        <td>${i.nombre}</td>
                        <td class="text-end">$${parseFloat(i.subtotal).toFixed(2)}</td>
                    </tr>
                `;
            });
        } else {
            // Si por alguna razón no tuviera ítems, muestra el total general
            htmlBody = `
                <tr>
                    <td>1</td>
                    <td>Venta Pedido #${ped.id}</td>
                    <td class="text-end">$${parseFloat(ped.total).toFixed(2)}</td>
                </tr>
            `;
        }

        document.getElementById('tickBodyItems').innerHTML = htmlBody;
        document.getElementById('tickTotal').innerText = parseFloat(ped.total).toFixed(2);

        // Abrir el modal del ticket listo para miniprinter
        let modalTicket = new bootstrap.Modal(document.getElementById('modalTicketPrint'));
        modalTicket.show();
    })
    .catch(error => {
        console.error('Error al obtener el detalle del pedido:', error);
        alert('No se pudieron cargar los detalles para la reimpresión.');
    });
}
function reimprimirTicket2(id) {
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
function cargarPedidosParaViaje2() {
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
function cargarPedidosParaViaje() {
    let tbody = document.getElementById('tbodyPedidosParaViaje');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted p-3">Cargando pedidos pendientes...</td></tr>';

    fetch('<?= base_url('/pedidos/pendientes-json') ?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(pedidos => {
        let html = '';

        // Validamos estrictamente que sea un arreglo
        if (!Array.isArray(pedidos) || pedidos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted p-3">No hay pedidos pendientes de entrega a domicilio.</td></tr>';
            document.getElementById('lblCantPedidosViaje').innerText = '0';
            document.getElementById('lblTotalViaje').innerText = '0.00';
            document.getElementById('chkTodosViaje').checked = false;
            return;
        }

        window.pedidosPendientesCache = pedidos;

        pedidos.forEach(p => {
            let direccionCompleta = p.direccion ? p.direccion : p.colonia;
            html += `
                <tr class="fila-pedido-viaje" data-colonia="${direccionCompleta.toLowerCase()}">
                    <td class="text-center">
                        <input type="checkbox" class="chk-ped-viaje" value="${p.id}" data-monto="${p.total}" onchange="calcularTotalesViaje()">
                    </td>
                    <td><span class="badge bg-secondary">#${p.id}</span></td>
                    <td><strong>${p.cliente}</strong><br><small class="text-muted">${p.telefono}</small></td>
                    <td><small>${direccionCompleta}</small></td>
                    <td class="fw-bold text-success">$${parseFloat(p.total).toFixed(2)}</td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        document.getElementById('lblCantPedidosViaje').innerText = '0';
        document.getElementById('lblTotalViaje').innerText = '0.00';
        document.getElementById('chkTodosViaje').checked = false;
    })
    .catch(error => {
        console.error('Error al cargar pedidos para viaje:', error);
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger p-3">Error al cargar los datos desde el servidor.</td></tr>';
    });
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
function enviarManifiestoWhatsApp2() {
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
    
    let resumenCarga = {};
    let idsAsignados = [];

    let msj = `🛵 *MANIFIESTO DE CARGA - EXPENDIO AVÍCOLA*\n`;
    msj += `----------------------------------\n`;
    msj += `👤 *Repartidor:* ${nombreRepartidor}\n`;
    msj += `📅 *Fecha:* ${new Date().toLocaleDateString()}\n`;
    msj += `📦 *Viaje #V-${numViaje}* (${seleccionados.length} Pedidos en Ruta)\n\n`;
    msj += `📍 *ENTREGAS EN RUTA:*\n`;

    let index = 1;
    seleccionados.forEach(chk => {
        let pedId = parseInt(chk.value);
        let ped = window.pedidosPendientesCache.find(x => parseInt(x.id) === pedId);
        
        if (ped) {
            totalCobrar += parseFloat(ped.total);
            idsAsignados.push(ped.id);
            
            msj += `${index}️⃣ *Folio #${ped.id}* - ${ped.cliente}\n`;
            msj += `   📞 Tel: ${ped.telefono}\n`;
            msj += `   🏠 Dir: ${ped.direccion || ped.colonia}\n`;
            
            // >>> AGREGAMOS ESTA LÍNEA PARA INCLUIR LA REFERENCIA EN EL MENSAJE <<<
            if (ped.referencia) {
                msj += `   📍 *Ref:* ${ped.referencia}\n`;
            }

            msj += `   🍗 *Productos:*\n`;
            if (ped.productos && ped.productos.length > 0) {
                ped.productos.forEach(prod => {
                    msj += `      - ${prod.cantidad}x ${prod.nombre} ($${parseFloat(prod.subtotal).toFixed(2)})\n`;
                    if (resumenCarga[prod.nombre]) {
                        resumenCarga[prod.nombre] += parseFloat(prod.cantidad);
                    } else {
                        resumenCarga[prod.nombre] = parseFloat(prod.cantidad);
                    }
                });
            } else {
                msj += `      - 1x Venta General ($${parseFloat(ped.total).toFixed(2)})\n`;
            }

            msj += `   💵 *Importe a Cobrar:* $${parseFloat(ped.total).toFixed(2)}\n\n`;
            index++;
        }
    });

    msj += `📦 *RESUMEN TOTAL DE CARGA:*\n`;
    for (let [item, cantidad] of Object.entries(resumenCarga)) {
        msj += `   - ${cantidad}x ${item}\n`;
    }
    msj += `\n`;

    msj += `💰 *TOTAL A LIQUIDAR EN CAJA:* $${totalCobrar.toFixed(2)}\n`;
    msj += `----------------------------------\n`;
    msj += `_Por favor confirmar recepción al salir. ¡Buen viaje!_`;

    // 1. Ocultar el modal de forma limpia
    let modalEl = document.getElementById('modalArmarViaje');
    let modalInstance = bootstrap.Modal.getInstance(modalEl);
    if (modalInstance) {
        modalInstance.hide();
    }

    // 2. Actualizar la base de datos y la vista en tiempo real (SIN RECARGAR LA PÁGINA)
    idsAsignados.forEach(id => {
        let formData = new FormData();
        formData.append('id', id);
        formData.append('estado', 'en_camino');
        formData.append('repartidor', nombreRepartidor);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('/pedidos/actualizar-estado') ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        }).catch(err => console.error('Error al actualizar estado en BD:', err));

        let filas = document.querySelectorAll('#tablaPedidosDb tr');
        filas.forEach(fila => {
            let badgeFolio = fila.querySelector('span.badge');
            if (badgeFolio && badgeFolio.textContent.trim() === `#${id}`) {
                let celdas = fila.querySelectorAll('td');
                if (celdas.length >= 9) {
                    celdas[5].innerHTML = `<span class="fw-bold text-dark"><i class="fa-solid fa-motorcycle text-primary me-1"></i> ${nombreRepartidor}</span>`; // Repartidor en índice 5
                    celdas[7].innerHTML = `<span class="badge bg-info text-white">En Camino</span>`; // Estado en índice 7

                    let tdAccion = celdas[8].querySelector('div'); 
                    if (tdAccion) {
                        let btnImprimir = tdAccion.querySelector('button[title="Reimprimir Ticket"]').outerHTML;
                        tdAccion.innerHTML = `
                            ${btnImprimir}
                            <!-- 🛠️ CORREGIDO: Pasando 'domicilio' y el nombre del repartidor asignado -->
                            <button class="btn btn-sm btn-outline-success" onclick="cambiarEstadoPedido(${id}, 'entregado', 'domicilio', '${nombreRepartidor}')">
                                <i class="fa-solid fa-check"></i> Entregar
                            </button>
                        `;
                    }
                }
            }
        });
    });

    // 3. Abrir el enlace de WhatsApp Web en una pestaña nueva
    let urlWA = `https://api.whatsapp.com/send?phone=52${telRepartidor}&text=${encodeURIComponent(msj)}`;
    window.open(urlWA, '_blank');
    actualizarMetricasCards();
}

let procesamientoMock = [
    { id: 1, lote: 'Lote #1 (Avícola San Antonio)', inicial: 92.00, util: 78.20, mermaKg: 13.80, mermaPorc: 15.00, fecha: '2026-10-19' }
];

// Ejecutar al cargar la página
//renderProcesamiento();

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
    //renderProcesamiento();

    // Ocultar modal y resetear
    let modalEl = document.getElementById('modalProcesarLote');
    bootstrap.Modal.getInstance(modalEl).hide();
    document.getElementById('formMockProceso').reset();

    alert(`¡Procesamiento registrado con éxito! Merma calculada: ${mermaKg.toFixed(2)} Kg (${mermaPorc.toFixed(1)}%)`);
}

function actualizarPrecioAjax(id) {
    let nuevoPrecio = document.getElementById(`inputP_${id}`).value;

    // Creamos el objeto FormData
    let formData = new FormData();
    formData.append('producto_id', id);
    formData.append('nuevo_precio', nuevoPrecio);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    $.ajax({
        url: '<?= base_url("/precios/actualizar") ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            // Actualizar token CSRF si el servidor retorna uno nuevo
            if (response && response.csrf_hash) {
                $('meta[name="csrf-token-hash"]').attr('content', response.csrf_hash);
            }

            if (response.status === 'success') {
                // Actualizamos el precio visualmente en la tabla sin recargar
                let lbl = document.getElementById(`lblPrecio_${id}`);
                if (lbl) {
                    lbl.innerText = `$${parseFloat(nuevoPrecio).toFixed(2)}`;
                }
                alert('¡Precio actualizado con éxito!');
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr);
            alert('No se pudo. '+error);
        }
    });
}

// Asegúrate de enviar la referencia en la función guardarPedidoAjax existente:
// 2. Limpiar también la función guardarPedidoAjax para que NO tenga location.reload()

function guardarPedidoAjax(imprimir) {
    if (typeof itemsTemporal === 'undefined' || itemsTemporal.length === 0) {
        alert('Debes agregar al menos un producto al pedido.');
        return;
    }
    
    let cliente = document.getElementById('pedCliente').value.trim();
    let telefono = document.getElementById('pedTelefono').value.trim();

    if (!cliente || !telefono) {
        alert('Por favor completa el nombre del cliente y su teléfono.');
        return;
    }

    let total = itemsTemporal.reduce((sum, item) => sum + item.subtotal, 0);
    let tipoEntrega = document.getElementById('pedTipoEntrega').value;
    let tipoPago = document.getElementById('pedTipoPago').value;
    let colonia = document.getElementById('pedColonia') ? document.getElementById('pedColonia').value : '';
    let direccion = document.getElementById('pedDireccion').value;
    let referencia = document.getElementById('pedReferencia').value;

    let formData = new FormData();
    formData.append('cliente', cliente);
    formData.append('telefono', telefono);
    formData.append('tipo_entrega', tipoEntrega);
    formData.append('tipo_pago', tipoPago);
    formData.append('colonia', colonia);
    formData.append('direccion', direccion);
    formData.append('referencia', referencia);
    formData.append('total', total);
    formData.append('items', JSON.stringify(itemsTemporal));

    /*
    formData.forEach((valor, clave) => {
        console.log(`${clave}:`, valor);
    });
     */
    //console.log(tipoPago);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/pedidos/guardar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.text()) // 1. Capturamos primero como texto plano para evitar errores de parseo por HTML adjunto
    .then(text => {
        let data;
        try {
            data = JSON.parse(text); // 2. Intentamos convertirlo manualmente a JSON
        } catch (e) {
            console.error("Respuesta del servidor no válida:", text);
            throw new Error("El servidor respondió con un formato no válido.");
        }
        console.log(data);

        if (data.status === 'success') {
            let modalEl = document.getElementById('modalNuevoPedido');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }

            if (imprimir) {
                document.getElementById('tickFolio').innerText = `FOLIO: #${data.folio}`;
                document.getElementById('tickFecha').innerText = new Date().toLocaleString();
                document.getElementById('tickCliente').innerText = cliente;
                document.getElementById('tickTel').innerText = telefono;
                document.getElementById('tickTipo').innerText = tipoEntrega.toUpperCase();
                document.getElementById('tickDir').innerText = (tipoEntrega === 'tienda' ? 'Recoge en mostrador' : `${direccion} (Ref: ${referencia})`);
                document.getElementById('tickRepartidor').innerText = tipoEntrega === 'tienda' ? 'N/A' : 'Sin asignar';

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

                let modalTicket = new bootstrap.Modal(document.getElementById('modalTicketPrint'));
                modalTicket.show();
            } else {
                alert('¡Pedido registrado con éxito en la base de datos!');
            }

            // Inyectar el nuevo pedido directamente en la tabla de forma visual y fluida
            let tabla = document.getElementById('tablaPedidosDb');
            if (tabla.querySelector('td[colspan]')) {
                tabla.innerHTML = '';
            }

            let badgeEntrega = 'bg-light text-dark border';
            let badgePago = tipoPago === 'transferencia' ? 'bg-primary text-white' : 'bg-success text-white';
            
            let badgeRepartidor = `<span class="badge bg-light text-muted border"><i class="fa-solid fa-clock me-1"></i> Por asignar</span>`;
            let repartidorTextoTicket = 'Sin asignar';
            
            if (tipoEntrega === 'tienda') {
                badgeRepartidor = `<span class="badge bg-light text-dark border"><i class="fa-solid fa-store me-1"></i> N/A</span>`;
                repartidorTextoTicket = 'N/A';
            }

            let claseFila = (tipoPago === 'transferencia') ? 'table-warning' : '';
            let htmlTipoPago = (tipoPago === 'transferencia') 
                ? `<button type="button" class="btn btn-sm btn-warning text-dark fw-bold border shadow-sm" onclick="abrirModalVerificarTransferencia(${data.folio}, '${cliente.replace(/'/g, "\\'")}', ${total})" title="Clic para verificar transferencia"><i class="fa-solid fa-mobile-screen-button me-1"></i> Transferencia</button>`
                : `<span class="badge bg-success text-white">Efectivo</span>`;

            let nuevaFila = `
                <tr id="fila-pedido-${data.folio}" class="${claseFila}">
                    <td><span class="badge bg-secondary">#${data.folio}</span></td>
                    <td><strong>${cliente}</strong><br><small class="text-muted">${telefono}</small></td>
                    <td><span class="badge bg-light text-dark border">${tipoEntrega.toUpperCase()}</span></td>
                    <td>${htmlTipoPago}</td>
                    <td><small>${tipoEntrega === 'tienda' ? 'Recoge en mostrador' : direccion}</small></td>
                    <td>${badgeRepartidor}</td>
                    <td class="text-success fw-bold">$${total.toFixed(2)}</td>
                    <td><span class="badge bg-warning text-dark">En Producción</span></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button class="btn btn-sm btn-outline-dark" onclick="reimprimirTicket({
                                id: '${data.folio}',
                                cliente: '${cliente.replace(/'/g, "\\'")}',
                                tel: '${telefono}',
                                tipo: '${tipoEntrega}',
                                direccion: '${(tipoEntrega === 'tienda' ? 'Recoge en mostrador' : direccion).replace(/'/g, "\\'")}',
                                repartidor: '${repartidorTextoTicket}',
                                total: '${total}'
                            })" title="Reimprimir Ticket">
                                <i class="fa-solid fa-print"></i>
                            </button>
                            <!-- 🛠️ CORREGIDO: Pasando tipoEntrega y repartidorActual -->
                            <button class="btn btn-sm btn-outline-success" onclick="cambiarEstadoPedido(${data.folio}, 'entregado', '${tipoEntrega}', '${repartidorTextoTicket}')">
                                <i class="fa-solid fa-check"></i> Entregar
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tabla.insertAdjacentHTML('afterbegin', nuevaFila);
            
            // Limpiar variables temporales, formulario y RESTABLECER EL NIP sin recargar
            itemsTemporal = [];
            descuentoAutorizado = false; 
            document.getElementById('tbodyDetallePedido').innerHTML = '<tr><td colspan="5" class="text-center text-muted fs-7">No se han añadido productos.</td></tr>';
            document.getElementById('lblTotalPedido').innerText = '0.00';
            
            // Limpiar formulario de forma segura
            let formPedido = document.getElementById('formMockPedido');
            if (formPedido) formPedido.reset();
            
            actualizarMetricasCards();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud con el servidor.');
    });
}

function cargarProductos() {
    let select = document.getElementById('selProducto');
    select.innerHTML = '<option value="">Cargando catálogo...</option>';

    fetch('<?= base_url('/productos/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        let productos = Array.isArray(data) ? data : (data.productos || []);
        select.innerHTML = '';
        if (!productos || productos.length === 0) {
            select.innerHTML = '<option value="">No hay productos disponibles</option>';
            return;
        }

        productos.forEach(p => {
            if (p.estado == 1 || p.estado === "1") {
                let option = document.createElement('option');
                option.value = `${p.nombre}|${p.precio_venta_actual}|${p.tipo_unidad}`;
                option.setAttribute('data-stock', p.stock_disponible_num);
                option.text = `${p.nombre} - Disp: ${parseFloat(p.stock_disponible_num).toFixed(2)} ${p.tipo_unidad} ($${parseFloat(p.precio_venta_actual).toFixed(2)})`;
                select.appendChild(option);
            }
        });
    })
    .catch(err => console.error('Error al cargar productos:', err));
}

// Función central para actualizar la tabla y el total visualmente
function renderizarDetallePedido() {
    let html = '';
    document.getElementById('tbodyDetallePedido').innerHTML = html;
    let total = 0;

    if (itemsTemporal.length === 0) {
        html = '<tr><td colspan="5" class="text-center text-muted fs-7">No se han añadido productos.</td></tr>';
    } else {
        itemsTemporal.forEach((i, index) => {
            total += i.subtotal;
            html += `
                <tr>
                    <td>${i.nombre}</td>
                    <td>${i.cant}</td>
                    <td>$${i.precio.toFixed(2)}</td>
                    <td>$${i.subtotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarItemPedido(${index})" title="Quitar producto">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }

    document.getElementById('tbodyDetallePedido').innerHTML = html;
    document.getElementById('lblTotalPedido').innerText = total.toFixed(2);
}

// Quitar producto de la lista temporal por su índice
function eliminarItemPedido(index) {
    itemsTemporal.splice(index, 1);
    renderizarDetallePedido();
}

function actualizarMetricasCards() {
    fetch('<?= base_url('/pedidos/metricas-json') ?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data) {
            document.getElementById('cntPendientes').innerText = data.pendientes;
            document.getElementById('cntCamino').innerText = data.en_camino;
            document.getElementById('cntEntregados').innerText = data.entregados;
            
            // Actualizar totales y desgloses
            document.getElementById('totalVentaHoy').innerText = `$${data.venta_total}`;
            
            // Si tu backend ya retorna los campos separados, los inyectamos aquí:
            if (data.venta_efectivo !== undefined) {
                document.getElementById('ventaEfectivoHoy').innerText = `$${data.venta_efectivo}`;
                document.getElementById('ventaTransferenciaHoy').innerText = `$${data.venta_transferencia}`;
            }
        }
    })
    .catch(error => {
        console.error('Error al actualizar métricas:', error);
    });
}

function verComprobanteModal(urlArchivo) {
    let contenedor = document.getElementById('contenedorComprobante');
    let btnDescargar = document.getElementById('btnDescargarComprobante');
    
    // Actualizar el enlace del botón inferior
    btnDescargar.href = urlArchivo;

    // Verificar la extensión del archivo para decidir cómo mostrarlo
    let extension = urlArchivo.split('.').pop().toLowerCase();

    if (['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(extension)) {
        // Si es una imagen, la mostramos adaptada al modal
        contenedor.innerHTML = `<img src="${urlArchivo}" class="img-fluid rounded shadow-sm" style="max-height: 75vh;" alt="Comprobante">`;
    } else if (extension === 'pdf') {
        // Si es PDF, usamos un iframe integrado
        contenedor.innerHTML = `<iframe src="${urlArchivo}" style="width: 100%; height: 70vh;" frameborder="0"></iframe>`;
    } else {
        // Fallback genérico para otros archivos
        contenedor.innerHTML = `
            <div class="p-4">
                <i class="fa-solid fa-file-arrow-down fa-3x text-secondary mb-3"></i>
                <p class="mb-2">Este archivo no se puede previsualizar directamente.</p>
                <a href="${urlArchivo}" target="_blank" class="btn btn-primary btn-sm">Descargar / Ver Archivo</a>
            </div>
        `;
    }

    // Mostrar el modal con Bootstrap
    let modalEl = new bootstrap.Modal(document.getElementById('modalVerComprobante'));
    modalEl.show();
}
actualizarMetricasCards();

function actualizarPrecioProducto(id) {
    let inputPrecio = document.getElementById(`precio_${id}`);
    let nuevoPrecio = inputPrecio.value;

    let formData = new FormData();
    formData.append('id', id);
    formData.append('precio_venta_actual', nuevoPrecio);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/catalogo/actualizar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log(data);
            // Actualizar visualmente el texto de precio actual sin recargar
            document.getElementById(`lblPrecioActual_${id}`).innerText = `$${parseFloat(nuevoPrecio).toFixed(2)}`;
            alert('¡Precio actualizado con éxito!');
        } else {
            alert('Error al actualizar el precio.');
        }
    })
    .catch(err => console.error('Error:', err));
}

// Guardar un producto nuevo mediante AJAX (Sin recargar la página)
function guardarNuevoProducto() {
    let nombre = document.getElementById('nuevoNombreProd').value;
    let unidad = document.getElementById('nuevoUnidadProd').value;
    let precio = document.getElementById('nuevoPrecioProd').value;

    if (!nombre || !precio) {
        alert('Por favor completa los campos obligatorios.');
        return;
    }

    let formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('tipo_unidad', unidad);
    formData.append('precio_venta_actual', precio);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/catalogo/guardar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // 1. Cerrar el modal y limpiar el formulario
            let modalEl = document.getElementById('modalNuevoProducto');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
            document.getElementById('formNuevoProducto').reset();

            // 2. ACTUALIZAR LA VISTA DE LA TABLA DINÁMICAMENTE (SIN RECARGAR)
            // Si tu backend devuelve el ID insertado (ej. data.id), lo usamos. Si no, simulamos una fila limpia o puedes pedir el ID en tu JSON.
            let nuevoId = data.id || Date.now(); // ID temporal o real devuelto por el controlador
            let tabla = document.getElementById('tablaCatalogoProductos');

            let nuevaFila = document.createElement('tr');
            nuevaFila.id = `fila_prod_${nuevoId}`;
            nuevaFila.innerHTML = `
                <td class="ps-3">#${nuevoId}</td>
                <td class="fw-bold">${nombre}</td>
                <td><span class="badge bg-secondary">${unidad}</span></td>
                <td class="text-success fw-bold" id="lblPrecioActual_${nuevoId}">$${parseFloat(precio).toFixed(2)}</td>
                <td>
                    <input type="number" step="0.50" id="precio_${nuevoId}" class="form-control form-control-sm" value="${precio}" style="width: 100px;">
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="actualizarPrecioProducto(${nuevoId})" title="Actualizar Precio">
                        <i class="fa-solid fa-floppy-disk"></i> Actualizar
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarProductoLogico(${nuevoId})" title="Eliminar Producto">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;
            
            // Añadir la fila al principio o al final de la tabla
            tabla.appendChild(nuevaFila);

            alert('¡Producto registrado con éxito!');
        } else {
            alert('Error al guardar el producto: ' + (data.message || ''));
        }
    })
    .catch(err => console.error('Error:', err));
}

// Eliminación lógica (Ocultar de la tabla cambiando estado en base de datos)
function eliminarProductoLogico(id) {
    if (!confirm('¿Estás seguro de dar de baja este producto del catálogo?')) return;

    fetch(`<?= base_url('/catalogo/eliminar/') ?>/${id}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Remover la fila de la tabla visualmente al instante
            let fila = document.getElementById(`fila_prod_${id}`);
            if (fila) fila.remove();
        } else {
            alert('No se pudo eliminar el producto.');
        }
    })
    .catch(err => console.error('Error:', err));
}

// Guardar Repartidor vía AJAX
function guardarRepartidor() {
    let nombre = document.getElementById('repNombre').value.trim();
    let telefono = document.getElementById('repTelefono').value.trim();
    let vehiculo = document.getElementById('repVehiculo').value;

    if (!nombre || !telefono) {
        alert('Por favor llena los campos obligatorios.');
        return;
    }

    // 1. SELECCIONAR EL BOTÓN Y BLOQUEARLO
    let btnGuardar = document.getElementById('btnGuardarRepartidor');
    let textoOriginal = btnGuardar.innerHTML;
    
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Guardando...`;

    let formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('telefono', telefono);
    formData.append('vehiculo', vehiculo);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/repartidores/guardar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // 2. RESTAURAR EL BOTÓN EN CASO DE RESPUESTA
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;

        if (data.status === 'success') {
            let modalEl = document.getElementById('modalNuevoRepartidor');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
            document.getElementById('formNuevoRepartidor').reset();

            // Insertar fila dinámicamente en la tabla
            let tabla = document.getElementById('tablaRepartidores');
            let nuevaFila = document.createElement('tr');
            nuevaFila.id = `fila_rep_${data.id}`;
            nuevaFila.innerHTML = `
                <td class="ps-3">#${data.id}</td>
                <td class="fw-bold">${nombre}</td>
                <td>${telefono}</td>
                <td><span class="badge bg-info text-dark">${vehiculo}</span></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarRepartidor(${data.id})" title="Dar de baja">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;
            tabla.appendChild(nuevaFila);
            alert('¡Repartidor registrado con éxito!');
        } else {
            alert('Error al guardar: ' + (data.message || ''));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        // 3. RESTAURAR EL BOTÓN TAMBIÉN SI HAY UN ERROR DE RED
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
        alert('Ocurrió un error al procesar la solicitud con el servidor.');
    });
}

// Baja lógica de Repartidor
function eliminarRepartidor(id) {
    if (!confirm('¿Estás seguro de dar de baja a este repartidor?')) return;

    fetch(`<?= base_url('/repartidores/eliminar/') ?>/${id}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            let fila = document.getElementById(`fila_rep_${id}`);
            if (fila) fila.remove();
        } else {
            alert('No se pudo eliminar.');
        }
    })
    .catch(err => console.error('Error:', err));
}

function cargarRepartidoresParaViaje() {
    let selectRep = document.getElementById('viajeRepartidorSel');
    selectRep.innerHTML = '<option value="">Cargando repartidores...</option>';

    fetch('<?= base_url('/repartidores/json') ?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(repartidores => {
        selectRep.innerHTML = '';

        if (!Array.isArray(repartidores) || repartidores.length === 0) {
            selectRep.innerHTML = '<option value="">No hay repartidores activos</option>';
            return;
        }

        repartidores.forEach(r => {
            let option = document.createElement('option');
            // Guardamos "Nombre|Teléfono" en el value para usarlo al armar el mensaje de WhatsApp
            option.value = `${r.nombre}|${r.telefono}`;
            option.text = `${r.nombre} (Tel: ${r.telefono} - ${r.vehiculo})`;
            selectRep.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error al cargar repartidores:', error);
        selectRep.innerHTML = '<option value="">Error al cargar repartidores</option>';
    });
}

<?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
// Actualización centralizada del módulo de Reportes
function cargarReportesDinamicos() {
    let inicio = document.getElementById('repFechaInicio').value;
    let fin = document.getElementById('repFechaFin').value;

    fetch(`<?= base_url('/reportes/datos-json') ?>?inicio=${inicio}&fin=${fin}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // 1. Tarjetas de Mermas y Despieces
            document.getElementById('lblPesoInicial').innerText = (data.merma && data.merma.inicial ? data.merma.inicial : '0.00') + ' Kg';
            document.getElementById('lblPesoUtil').innerText = (data.merma && data.merma.util ? data.merma.util : '0.00') + ' Kg';
            document.getElementById('lblMermaKg').innerText = (data.merma && data.merma.merma_kg ? data.merma.merma_kg : '0.00') + ' Kg';
            document.getElementById('lblMermaPorc').innerText = (data.merma && data.merma.merma_porc ? data.merma.merma_porc : '0.0') + '%';

            // 2. Tabla de Fluctuación de Lotes
            let tbodyLotes = document.querySelector('#tablaLotesReporte tbody');
            let htmlLotes = '';
            if (data.fluctuacion && data.fluctuacion.length > 0) {
                data.fluctuacion.forEach(l => {
                    htmlLotes += `
                        <tr>
                            <td><span class="badge bg-secondary">#${l.id}</span></td>
                            <td class="fw-bold">${l.nombre_empresa || 'Sin Proveedor'}</td>
                            <td><span class="badge bg-${l.tipo_ave === 'pollo' ? 'warning text-dark' : 'danger'}">${l.tipo_ave.toUpperCase()}</span></td>
                            <td class="text-success fw-bold">$${parseFloat(l.costo_unitario).toFixed(2)}</td>
                            <td>${l.fecha_recepcion}</td>
                        </tr>
                    `;
                });
            } else {
                htmlLotes = `<tr><td colspan="5" class="text-center text-muted py-3">No hay registros de lotes en este rango de fechas.</td></tr>`;
            }
            tbodyLotes.innerHTML = htmlLotes;

            // 3. Tabla de Zonas y Colonias
            let tbodyZonas = document.querySelector('#tablaZonasReporte tbody');
            let htmlZonas = '';
            if (data.zonas && data.zonas.length > 0) {
                data.zonas.forEach(z => {
                    htmlZonas += `
                        <tr>
                            <td class="fw-bold"><i class="fa-solid fa-location-dot text-danger me-2"></i> ${z.colonia}</td>
                            <td><span class="badge bg-secondary">${z.total_pedidos} pedidos</span></td>
                            <td class="text-success fw-bold">$${parseFloat(z.volumen_venta).toFixed(2)}</td>
                            <td><span class="badge bg-success">Zona Fuerte</span></td>
                        </tr>
                    `;
                });
            } else {
                htmlZonas = `<tr><td colspan="4" class="text-center text-muted py-3">No hay pedidos registrados por colonia en este periodo.</td></tr>`;
            }
            tbodyZonas.innerHTML = htmlZonas;

            // 4. Recargar también el historial de pedidos
            cargarHistorialPedidosDinamico();
        }
    })
    .catch(err => console.error('Error al cargar datos del módulo de reportes:', err));
}

function cargarFluctuacionCompraVenta() {
    let inicio = document.getElementById('repFechaInicio').value;
    let fin = document.getElementById('repFechaFin').value;

    fetch(`<?= base_url('/reportes/fluctuacion-json') ?>?inicio=${inicio}&fin=${fin}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success') {
            let tbody = document.querySelector('#content-fluctuacion tbody');
            let html = '';

            if (response.data.length > 0) {
                response.data.forEach(item => {
                    html += `
                        <tr>
                            <td class="fw-bold">${item.tipo_ave} (Lote #${item.lote_id})</td>
                            <td>$${item.costo_compra} / pza</td>
                            <td>$${item.precio_venta} / pza</td>
                            <td class="text-success fw-bold">+$${item.margen_dinero} (${item.margen_porc}%)</td>
                            <td><i class="fa-solid fa-caret-up text-success fs-5 me-1"></i> ${item.tendencia}</td>
                        </tr>
                    `;
                });
            } else {
                html = `<tr><td colspan="5" class="text-center text-muted">No hay registros de lotes en este periodo.</td></tr>`;
            }

            tbody.innerHTML = html;
        }
    })
    .catch(err => console.error('Error:', err));
}
cargarFluctuacionCompraVenta();
<?php endif; ?>

let cortesTemporal = [];

// Función para añadir un corte al arreglo temporal del modal
function agregarCorteDespiece() {
    let select = document.getElementById('selCorteProducto');
    if (!select.value) return;

    let productoId = select.value;
    let nombreProducto = select.options[select.selectedIndex].text;
    let cantidad = parseFloat(document.getElementById('corteCantidadKg').value);

    if (!cantidad || cantidad <= 0) {
        alert('Ingresa una cantidad válida.');
        return;
    }

    cortesTemporal.push({ producto_id: productoId, nombre: nombreProducto, cantidad_kg: cantidad });
    renderizarCortesDespiece();
    document.getElementById('corteCantidadKg').value = '';
}

function renderizarCortesDespiece() {
    let tbody = document.getElementById('tbodyCortesDespiece');
    let html = '';

    if (cortesTemporal.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No se han agregado cortes al despiece.</td></tr>';
        return;
    }

    cortesTemporal.forEach((c, index) => {
        html += `
            <tr>
                <td>${c.nombre}</td>
                <td>${c.cantidad_kg} Kg</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="cortesTemporal.splice(${index}, 1); renderizarCortesDespiece();">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function enviarDespieceAjax() {
    let loteId = document.getElementById('procLoteOrigen').value;
    let pesoInicial = document.getElementById('procPesoInicial').value;
    let pesoUtil = document.getElementById('procPesoUtil').value;

    if (!loteId || !pesoInicial || !pesoUtil || cortesTemporal.length === 0) {
        alert('Por favor completa todos los campos y añade al menos un corte resultante.');
        return;
    }

    let formData = new FormData();
    formData.append('procLoteOrigen', loteId);
    formData.append('procPesoInicial', pesoInicial);
    formData.append('procPesoUtil', pesoUtil);
    formData.append('cortes_json', JSON.stringify(cortesTemporal));
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/produccion/guardar-despiece') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            console.log(data);
            alert(data.message);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

function cargarProductosParaDespiece() {
    fetch('<?= base_url('/productos/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(productos => {
        let select = document.getElementById('selCorteProducto');
        select.innerHTML = '';

        if (!productos || productos.length === 0) {
            select.innerHTML = '<option value="">No hay productos en el catálogo</option>';
            return;
        }

        productos.forEach(p => {
            let option = document.createElement('option');
            option.value = p.id; // Guardamos el ID del producto
            option.text = `${p.nombre} (${p.tipo_unidad})`;
            select.appendChild(option);
        });
    })
    .catch(err => console.error('Error al cargar catálogo para despiece:', err));
}

let loteSeleccionadoPz = 0;
let loteSeleccionadoKg = 0;

function cargarDatosLoteOrigen(select) {
    let option = select.options[select.selectedIndex];
    if (!option.value) {
        loteSeleccionadoPz = 0;
        loteSeleccionadoKg = 0;
        return;
    }
    loteSeleccionadoPz = parseInt(option.getAttribute('data-piezas')) || 0;
    loteSeleccionadoKg = parseFloat(option.getAttribute('data-peso')) || 0.0;
    
    calcularRestoDespiece();
}

// Escuchar cambios en los inputs de kilos usables para recalcular al instante
document.addEventListener('input', function (e) {
    if (e.target.classList.contains('corte-input') || e.target.id === 'piezasADestinar') {
        calcularRestoDespiece();
    }
});

function guardarDespieceOperativo() {
    let loteId = document.getElementById('despieceLoteId').value;
    let piezasDestinadas = document.getElementById('piezasADestinar').value;

    if (!loteId) {
        alert('Por favor selecciona un lote de origen.');
        return;
    }

    let cortesObtenidos = [];
    let inputsCortes = document.querySelectorAll('.corte-input');
    
    inputsCortes.forEach(input => {
        let productoId = input.getAttribute('data-producto-id');
        let nombreProducto = input.getAttribute('data-producto');
        let cantidadKg = parseFloat(input.value) || 0;
        
        if (cantidadKg > 0) {
            cortesObtenidos.push({
                producto_id: productoId,
                producto_nombre: nombreProducto,
                cantidad_kg: cantidadKg
            });
        }
    });

    if (cortesObtenidos.length === 0) {
        alert('Debes ingresar al menos una cantidad mayor a 0 en los cortes.');
        return;
    }

    // 1. BLOQUEAR EL BOTÓN PARA EVITAR DOBLE CLIC
    let btnAplicar = document.getElementById('btnAplicarDespiece');
    let textoOriginalBtn = btnAplicar.innerHTML;
    btnAplicar.disabled = true;
    btnAplicar.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Aplicando...`;

    let formData = new FormData();
    formData.append('procLoteOrigen', loteId);
    formData.append('piezas_destinadas', piezasDestinadas);
    formData.append('cortes_json', JSON.stringify(cortesObtenidos));
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/produccion/guardar-despiece') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btnAplicar.disabled = false;
        btnAplicar.innerHTML = textoOriginalBtn;

        if (data.status === 'success') {
            alert(data.message);

            // 2. Cerrar modal y limpiar campos
            let modalElement = document.getElementById('modalNuevoDespiece');
            let modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) modalInstance.hide();

            document.getElementById('formDespieceOperativo').reset();
            document.getElementById('restPz').innerText = '0 Pz';
            document.getElementById('restKg').innerText = '0.00 Kg';

            // 3. Quitar el lote procesado del select del modal
            let selectLote = document.getElementById('despieceLoteId');
            let optionToRemove = selectLote.querySelector(`option[value="${loteId}"]`);
            if (optionToRemove) optionToRemove.remove();
            selectLote.value = "";

            // 4. Actualizar dinámicamente la tabla de despiece
            let p = data.datos_proceso;
            let tbody = document.querySelector('#vista-despiece table tbody');

            if (tbody.querySelector('.text-muted') && tbody.rows.length === 1) {
                tbody.innerHTML = '';
            }

            let htmlCortes = '';
            p.cortes.forEach(c => {
                htmlCortes += `
                    <tr>
                        <td class="text-start ps-3 border-bottom">🔪 ${c.producto_nombre} (Corte)</td>
                        <td>-</td>
                        <td>-</td>
                        <td class="fw-bold text-success">${parseFloat(c.cantidad_kg).toFixed(2)} Kg</td>
                    </tr>
                `;
            });

            let contenidoFilaHtml = `
                <td class="fw-bold bg-light">#${p.lote_id}</td>
                <td class="fw-bold bg-light text-start">
                    ${p.tipo_ave.charAt(0).toUpperCase() + p.tipo_ave.slice(1)} <br>
                    <small class="text-muted">Lote ID: #${p.lote_id}</small>
                </td>
                <td class="bg-light">
                    <span class="badge bg-secondary">${p.piezas_originales} Pz</span><br>
                    <span class="text-success fw-bold">${parseFloat(p.peso_original).toFixed(2)} Kg</span>
                </td>
                <td colspan="4" class="p-0">
                    <table class="table table-sm table-borderless mb-0 text-center align-middle">
                        <tr class="bg-white">
                            <td class="text-start ps-3 fw-bold text-dark">
                                ${p.tipo_ave.charAt(0).toUpperCase() + p.tipo_ave.slice(1)} (Enteros)
                            </td>
                            <td>
                                <span class="text-danger fw-bold">-${p.piezas_destinadas} Pz</span> / <span class="text-primary">${p.piezas_restantes} Pz</span>
                            </td>
                            <td>
                                <span class="text-danger fw-bold">-${parseFloat(p.kilos_descontados).toFixed(2)} Kg</span> / <span class="text-success">${parseFloat(p.kilos_restantes).toFixed(2)} Kg</span>
                            </td>
                            <td class="fw-bold text-primary">
                                ${p.piezas_restantes} Pz / ${parseFloat(p.kilos_restantes).toFixed(2)} Kg
                            </td>
                        </tr>
                        ${htmlCortes}
                    </table>
                </td>
            `;

            let filaExistente = document.getElementById(`fila-lote-${p.lote_id}`);
            if (filaExistente) {
                filaExistente.innerHTML = contenidoFilaHtml;
            } else {
                let nuevaFila = document.createElement('tr');
                nuevaFila.id = `fila-lote-${p.lote_id}`;
                nuevaFila.innerHTML = contenidoFilaHtml;
                tbody.appendChild(nuevaFila);
            }

            // Recargar catálogo si está disponible la función
            if (typeof cargarInventarioGeneral === 'function') {
                cargarInventarioGeneral();
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Error al guardar despiece:', err);
        btnAplicar.disabled = false;
        btnAplicar.innerHTML = textoOriginalBtn;
        alert('Ocurrió un error al procesar el despiece.');
    });
}

let btnAplicar = document.getElementById('btnAplicarDespiece');

function calcularRestoDespiece() {
    let piezasDestinadas = parseInt(document.getElementById('piezasADestinar').value) || 0;
    
    // Validación de seguridad para no exceder las piezas originales del lote
    if (piezasDestinadas > loteSeleccionadoPz) {
        alert('¡Atención! Las piezas a destinar no pueden ser mayores a las piezas totales del lote original.');
        document.getElementById('piezasADestinar').value = loteSeleccionadoPz;
        piezasDestinadas = loteSeleccionadoPz;
    }

    // 1. CÁLCULO DE KILOS DESCONTADOS (Basado en tu fórmula de Excel: Promedio por pieza * Piezas a destinar)
    let pesoPromedioPorPieza = loteSeleccionadoPz > 0 ? (loteSeleccionadoKg / loteSeleccionadoPz) : 0;
    let kilosDescontadosAlLote = pesoPromedioPorPieza * piezasDestinadas;

    // 2. SUMAR LOS KILOS USABLES DE LOS CORTES
    let totalKilosCortes = 0;
    document.querySelectorAll('.corte-input').forEach(input => {
        totalKilosCortes += parseFloat(input.value) || 0;
    });

    // 3. CANDADITO: Los kilos de los cortes no deben pasar de los kilos descontados al lote
    if (totalKilosCortes > kilosDescontadosAlLote) {
        alert(`¡Candadito activado! La suma de los kilos de los cortes (${totalKilosCortes.toFixed(2)} Kg) excede los kilos asignados a las piezas a destinar (${kilosDescontadosAlLote.toFixed(2)} Kg).`);
        if (btnAplicar) btnAplicar.disabled = true;
    } else {
        if (btnAplicar) btnAplicar.disabled = false;
    }

    // 4. CÁLCULO DEL RESTO DE LOTE (Enteros para venta)
    let piezasRestantes = loteSeleccionadoPz - piezasDestinadas;
    let kilosRestantes = loteSeleccionadoKg - kilosDescontadosAlLote;

    // Actualizar la interfaz visual del Resto de Lote
    document.getElementById('restPz').innerText = `${piezasRestantes >= 0 ? piezasRestantes : 0} Pz`;
    document.getElementById('restKg').innerText = `${kilosRestantes >= 0 ? kilosRestantes.toFixed(2) : '0.00'} Kg`;
}

function actualizarPrecioProducto(productoId) {
    // 1. Tomamos el valor directamente del input usando tu ID existente: precio_ID
    let inputPrecio = document.getElementById(`precio_${productoId}`);
    if (!inputPrecio) return;

    let nuevoPrecio = inputPrecio.value;

    let formData = new FormData();
    formData.append('producto_id', productoId);
    formData.append('nuevo_precio', nuevoPrecio);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    // 2. Petición AJAX por fetch hacia tu controlador
    fetch('<?= base_url('/precios/actualizar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // 3. Actualizamos visualmente el texto del precio actual al instante con tu ID: lblPrecioActual_ID
            let lblPrecioActual = document.getElementById(`lblPrecioActual_${productoId}`);
            if (lblPrecioActual) {
                lblPrecioActual.innerText = `$${parseFloat(nuevoPrecio).toFixed(2)}`;
            }
            alert(data.message);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error al actualizar precio:', err));
}

function calcularPesoTotalLote() {
    let piezas = parseFloat(document.getElementById('lotePiezas').value) || 0;
    let pesoPromedio = parseFloat(document.getElementById('lotePesoPromedio').value) || 0;
    let inputPesoTotal = document.getElementById('lotePesoTotal');

    if (piezas > 0 && pesoPromedio > 0) {
        let pesoTotalCalculado = piezas * pesoPromedio;
        inputPesoTotal.value = pesoTotalCalculado.toFixed(2);
    }
}
<?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
function guardarLoteAjax() {
    let formElement = document.getElementById('formNuevoLoteDb');
    let formData = new FormData(formElement);

    let pesoTotal = document.getElementById('lotePesoTotal').value;
    if (!pesoTotal || parseFloat(pesoTotal) <= 0) {
        alert('¡Atención! El peso total del lote es obligatorio y debe ser mayor a cero.');
        document.getElementById('lotePesoTotal').focus();
        return;
    }

    // ❌ ELIMINAMOS O COMENTAMOS ESTA VALIDACIÓN PARA QUE NO SEA OBLIGATORIO:
    /*
    let archivoInput = document.getElementById('mockComprobante');
    if (archivoInput.files.length === 0) {
        alert('¡Atención! Es obligatorio adjuntar el comprobante o ticket del proveedor.');
        return;
    }
    */

    let btnGuardar = document.querySelector('#modalNuevoLote .modal-footer .btn-success');
    let textoOriginalBtn = btnGuardar.innerHTML;
    
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Guardando...`;

    $.ajax({
        url: '<?= base_url('/produccion/guardar-lote') ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            if (response && response.csrf_hash) {
                $('meta[name="csrf-token-hash"]').attr('content', response.csrf_hash);
            }

            if (response.status === 'success') {
                alert(response.message);
                let modalEl = document.getElementById('modalNuevoLote');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
                formElement.reset();
                setTimeout(() => { location.reload(); }, 1000);
            } else {
                alert('Error al guardar el lote: ' + (response.message || 'Error desconocido'));
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = textoOriginalBtn;
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr);
            alert('Ocurrió un error al procesar la solicitud con el servidor: ' + error);
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = textoOriginalBtn;
        }
    });
}
<?php endif; ?>
function cargarTablaStockRapido() {
    let tbody = document.getElementById('tbodyConsultaStockRapido');
    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Cargando disponibilidad de inventarios...</td></tr>';

    fetch('<?= base_url('/productos/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        let productos = Array.isArray(data) ? data : (data.productos || []);
        let html = '';
        if (!productos || productos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-3 text-muted">No hay productos registrados.</td></tr>';
            return;
        }

        productos.forEach(p => {
            let esCorte = p.tipo_unidad === 'kilos';
            let icono = esCorte ? '🔪 ' : '🐔 ';
            let badgeColor = parseFloat(p.stock_disponible_num) > 0 ? 'bg-warning text-dark' : 'bg-danger text-white';

            html += `
                <tr>
                    <td class="text-start ps-3 fw-bold">${icono} ${p.nombre}</td>
                    <td class="fw-bold text-success">
                        <span class="badge ${badgeColor} fs-6">${parseFloat(p.stock_disponible_num).toFixed(2)} ${p.tipo_unidad}</span>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    })
    .catch(err => {
        console.error('Error al obtener datos de stock:', err);
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-3">Error al cargar datos desde el servidor.</td></tr>';
    });
}

function guardarProveedorAjax() {
    let formElement = document.getElementById('formNuevoProveedorDb');
    let formData = new FormData(formElement);

    let empresa = document.getElementById('provEmpresa').value.trim();
    let telefono = document.getElementById('provTelefono').value.trim();

    if (!empresa || !telefono) {
        alert('Por favor completa los campos obligatorios (Empresa y Teléfono).');
        return;
    }

    let btnGuardar = document.querySelector('#modalNuevoProveedor .modal-footer .btn-success');
    let textoOriginal = btnGuardar.innerHTML;
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Guardando...`;

    fetch('<?= base_url('/proveedores/guardar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;

        if (data.status === 'success') {
            // 1. Ocultar modal y limpiar formulario
            let modalEl = document.getElementById('modalNuevoProveedor');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();
            formElement.reset();

            // 2. Insertar dinámicamente la nueva fila en la tabla de Proveedores
            let tbody = document.getElementById('tablaProveedoresMock');
            if (tbody.querySelector('td[colspan]')) {
                tbody.innerHTML = ''; // Limpiar mensaje de "No hay proveedores"
            }

            let p = data.proveedor;
            let nuevaFila = document.createElement('tr');
            nuevaFila.id = `fila_prov_${p.id}`;
            nuevaFila.innerHTML = `
                <td><span class="badge bg-secondary">#${p.id}</span></td>
                <td class="fw-bold">${p.nombre_empresa}</td>
                <td>${p.contacto ? p.contacto : '-'}</td>
                <td><i class="fa-solid fa-phone text-success me-1"></i>${p.telefono}</td>
                <td>${p.created_at || new Date().toISOString().slice(0, 19).replace('T', ' ')}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarProveedorAjax(${p.id})" title="Dar de baja">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.insertAdjacentElement('afterbegin', nuevaFila);

            // 3. Agregar también la opción al <select> de proveedores en el modal de Nuevo Lote
            let selectLoteProv = document.getElementById('mockProveedor');
            if (selectLoteProv) {
                let nuevaOpt = document.createElement('option');
                nuevaOpt.value = p.id;
                nuevaOpt.text = p.nombre_empresa;
                selectLoteProv.appendChild(nuevaOpt);
            }

            alert(data.message);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
        alert('Ocurrió un error al procesar la solicitud con el servidor.');
    });
}

function eliminarProveedorAjax(id) {
    if (!confirm('¿Estás seguro de dar de baja a este proveedor?')) return;

    fetch(`<?= base_url('/proveedores/eliminar/') ?>/${id}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Remover la fila de la tabla visualmente al instante sin recargar
            let fila = document.getElementById(`fila_prov_${id}`);
            if (fila) {
                fila.remove();
            }

            // Remover también la opción del select en el modal de Nuevo Lote
            let selectLoteProv = document.getElementById('mockProveedor');
            if (selectLoteProv) {
                let opt = selectLoteProv.querySelector(`option[value="${id}"]`);
                if (opt) opt.remove();
            }
        } else {
            alert('No se pudo dar de baja el proveedor.');
        }
    })
    .catch(err => console.error('Error:', err));
}

// Buscar cliente automáticamente al escribir el teléfono (mínimo 8 dígitos)
function buscarClientePorTelefono(telefono) {
    if (telefono.length < 8) return; // Esperar a que escriba un número completo

    fetch(`<?= base_url('/pedidos/buscar-cliente') ?>?telefono=${telefono}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let c = data.cliente;
            document.getElementById('pedCliente').value = c.cliente || '';
            //document.getElementById('pedTipoEntrega').value = c.tipo_entrega || 'domicilio';
            //toggleDireccion(c.tipo_entrega || 'domicilio');
            
            document.getElementById('pedColonia').value = c.colonia || '';
            document.getElementById('pedDireccion').value = c.direccion || '';
            document.getElementById('pedReferencia').value = c.referencia || '';
        }
    })
    .catch(err => console.error('Error al buscar cliente:', err));
}
<?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
function cargarInventarioGeneral() {
    let tbody = document.getElementById('tablaInventarioGeneral');
    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Cargando inventario general...</td></tr>';

    fetch('<?= base_url('/productos/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        // Compatibilidad por si el endpoint devuelve directo el array o el objeto estructurado
        let productos = Array.isArray(data) ? data : (data.productos || []);
        let mermasHoy = data.mermas_hoy || 0;

        let html = '';
        let totalKilosCortes = 0;
        let totalAvesVivas = 0;

        if (!productos || productos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3 text-muted">No hay productos registrados en el almacén.</td></tr>';
            return;
        }

        productos.forEach(p => {
            let esCorte = p.tipo_unidad === 'kilos';
            let icono = esCorte ? '🔪 ' : '🐔 ';
            
            let stockDisponible = parseFloat(p.stock_disponible_num) || 0;
            let vendidoHoy = parseFloat(p.vendido_hoy) || 0;
            let entradasTotal = stockDisponible + vendidoHoy;

            if (esCorte) {
                totalKilosCortes += stockDisponible;
            } else {
                totalAvesVivas += stockDisponible;
            }

            let badgeEstado = stockDisponible > 0 
                ? '<span class="badge bg-success">Disponible</span>' 
                : '<span class="badge bg-danger">Agotado</span>';

            html += `
                <tr>
                    <td class="text-start ps-3 fw-bold">${icono} ${p.nombre}</td>
                    <td><span class="badge bg-secondary">${p.tipo_unidad}</span></td>
                    <td>${entradasTotal.toFixed(2)} ${p.tipo_unidad}</td>
                    <td class="text-danger fw-bold">-${vendidoHoy.toFixed(2)} ${p.tipo_unidad}</td>
                    <td class="text-success fw-bold">${stockDisponible.toFixed(2)} ${p.tipo_unidad}</td>
                    <td>${badgeEstado}</td>
                </tr>
            `;
        });

        tbody.innerHTML = html;

        // Actualizamos las tarjetas con datos reales
        document.getElementById('stockTotalAves').innerText = `${totalAvesVivas.toFixed(0)} Pz`;
        document.getElementById('stockTotalKilosCortes').innerText = `${totalKilosCortes.toFixed(2)} Kg`;
        document.getElementById('mermasHoyAlmacen').innerText = `${parseFloat(mermasHoy).toFixed(2)} Kg`;
    })
    .catch(err => {
        console.error('Error al cargar inventario general:', err);
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-3">Error al cargar datos desde el servidor.</td></tr>';
    });
}
<?php endif; ?>
// Cargar productos en el select del modal de merma
function cargarProductosParaAjuste() {
    let select = document.getElementById('ajusteProductoId');
    select.innerHTML = '<option value="">Cargando productos...</option>';

    fetch('<?= base_url('/productos/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        let productos = Array.isArray(data) ? data : (data.productos || []);
        select.innerHTML = '<option value="">Selecciona un producto...</option>';
        if (!productos || productos.length === 0) return;

        productos.forEach(p => {
            let option = document.createElement('option');
            option.value = p.id;
            option.text = `${p.nombre} (Disp: ${parseFloat(p.stock_disponible_num).toFixed(2)} ${p.tipo_unidad})`;
            select.appendChild(option);
        });
    })
    .catch(err => console.error('Error al cargar productos para ajuste:', err));
}

<?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
// Disparar la carga al abrir el modal (puedes agregar data-bs-toggle al botón o llamarlo así)
document.getElementById('modalAjusteInventario').addEventListener('show.bs.modal', function () {
    cargarProductosParaAjuste();
});
<?php endif; ?>

// Escuchar la apertura del modal para cargar los productos automáticamente
document.addEventListener('DOMContentLoaded', function() {
    let modalAjuste = document.getElementById('modalAjusteInventario');
    if (modalAjuste) {
        modalAjuste.addEventListener('show.bs.modal', function () {
            cargarProductosParaAjuste();
        });
    }
    // Si estás por defecto en la pestaña de mermas o quieres que cargue al iniciar:
    if (typeof cargarInventarioGeneral === 'function') {
        cargarInventarioGeneral();
    }
});

// Función AJAX para guardar la merma o ajuste manual
function guardarAjusteMermaAjax() {
    let productoId = document.getElementById('ajusteProductoId').value;
    let tipo = document.getElementById('ajusteTipo').value;
    let cantidad = document.getElementById('ajusteCantidad').value;
    let motivo = document.getElementById('ajusteMotivo').value.trim();

    if (!productoId || !cantidad || !motivo) {
        alert('Por favor completa todos los campos obligatorios.');
        return;
    }

    // 1. SELECCIONAR EL BOTÓN Y BLOQUEARLO
    let btnGuardar = document.querySelector('#modalAjusteInventario .modal-footer .btn-danger');
    let textoOriginal = btnGuardar.innerHTML;
    
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Guardando...`;

    let formData = new FormData();
    formData.append('producto_id', productoId);
    formData.append('tipo', tipo);
    formData.append('cantidad', cantidad);
    formData.append('motivo', motivo);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/almacen/ajustar-stock') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        // 2. RESTAURAR EL BOTÓN
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;

        if (data.status === 'success') {
            let modalEl = document.getElementById('modalAjusteInventario');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            document.getElementById('formAjusteMerma').reset();
            alert('¡Ajuste de inventario registrado con éxito!');

            // Refrescar la tabla de inventario general si está disponible
            if (typeof cargarInventarioGeneral === 'function') {
                cargarInventarioGeneral();
            }
        } else {
            alert('Error: ' + (data.message || 'No se pudo procesar el ajuste.'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        // 3. RESTAURAR EL BOTÓN TAMBIÉN SI HAY UN ERROR DE RED
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
        alert('Ocurrió un error al procesar la solicitud con el servidor.');
    });
}

let cacheClientesDirectorio = [];

// Cargar la tabla del directorio de clientes sin la columna de Tipo de Entrega

function cargarClientesDirectorio() {
    let tbody = document.getElementById('tablaDirectorioClientes');
    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Cargando clientes...</td></tr>';

    fetch('<?= base_url('/clientes/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(clientes => {
        cacheClientesDirectorio = clientes || [];
        let html = '';

        if (!clientes || clientes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3 text-muted">No hay clientes registrados en el directorio.</td></tr>';
            return;
        }

        clientes.forEach(c => {
            html += `
            <tr id="fila_cliente_${c.id}">
                <td class="ps-3">#${c.id}</td>
                <td class="fw-bold">${c.nombre}</td>
                <td><i class="fa-solid fa-phone text-success me-1"></i>${c.telefono}</td>
                <td><small>${c.colonia || '-'} <br> ${c.direccion || ''}</small></td>
                <td><small class="text-muted">${c.referencia || 'Sin referencias'}</small></td>
                <td class="text-center">
                <button class="btn btn-sm btn-outline-primary" onclick="editarClienteDirectorio(${c.id})" title="Editar datos del cliente">
                <i class="fa-solid fa-pen-to-square"></i> Editar
                </button>
                </td>
            </tr>
            `;
        });
        tbody.innerHTML = html;
    })
    .catch(err => {
        console.error('Error al cargar clientes:', err);
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-3">Error al cargar datos del servidor.</td></tr>';
    });
}

// Guardar o Actualizar Cliente mediante AJAX
function guardarClienteDirectorioAjax() {
    let id          = document.getElementById('dirClienteId').value;
    let telefono    = document.getElementById('dirTelefono').value.trim();
    let nombre      = document.getElementById('dirNombre').value.trim();
    let tipoEntrega = document.getElementById('dirTipoEntrega').value;
    let colonia     = document.getElementById('dirColonia').value.trim();
    let direccion   = document.getElementById('dirDireccion').value.trim();
    let referencia  = document.getElementById('dirReferencia').value.trim();

    if (!telefono || !nombre) {
        alert('Por favor completa el teléfono y el nombre del cliente.');
        return;
    }

    // 1. SELECCIONAR EL BOTÓN Y BLOQUEARLO
    let btnGuardar = document.querySelector('#modalNuevoClienteDirectorio .modal-footer .btn-success');
    let textoOriginal = btnGuardar.innerHTML;
    
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Guardando...`;

    let formData = new FormData();
    if (id) formData.append('id', id);
    formData.append('telefono', telefono);
    formData.append('nombre', nombre);
    formData.append('tipo_entrega', tipoEntrega);
    formData.append('colonia', colonia);
    formData.append('direccion', direccion);
    formData.append('referencia', referencia);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/clientes/guardar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        // 2. RESTAURAR EL BOTÓN
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;

        if (data.status === 'success') {
            let modalEl = document.getElementById('modalNuevoClienteDirectorio');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            prepararModalClienteNuevo();
            alert(data.message);

            // Recargar tabla de directorio
            cargarClientesDirectorio();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        // 3. RESTAURAR EL BOTÓN TAMBIÉN SI HAY UN ERROR DE RED
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
        alert('Ocurrió un error al procesar la solicitud.');
    });
}

// Limpiar modal para crear un cliente nuevo
function prepararModalClienteNuevo() {
    document.getElementById('dirClienteId').value = '';
    document.getElementById('formClienteDirectorio').reset();
    document.getElementById('tituloModalCliente').innerHTML = '<i class="fa-solid fa-user-plus me-2"></i> Alta de Cliente';
}

// Cargar los datos del cliente en el modal para editar
function editarClienteDirectorio(id) {
    let c = cacheClientesDirectorio.find(x => parseInt(x.id) === parseInt(id));
    if (!c) return;

    document.getElementById('dirClienteId').value = c.id;
    document.getElementById('dirTelefono').value = c.telefono || '';
    document.getElementById('dirNombre').value = c.nombre || '';
    document.getElementById('dirTipoEntrega').value = c.tipo_entrega || 'domicilio';
    document.getElementById('dirColonia').value = c.colonia || '';
    document.getElementById('dirDireccion').value = c.direccion || '';
    document.getElementById('dirReferencia').value = c.referencia || '';

    document.getElementById('tituloModalCliente').innerHTML = `<i class="fa-solid fa-user-pen me-2"></i> Editar Cliente #${c.id}`;

    let modalEl = document.getElementById('modalNuevoClienteDirectorio');
    let modalInstance = new bootstrap.Modal(modalEl);
    modalInstance.show();
}

function cargarCortesParaDespieceModal() {
    fetch('<?= base_url('/productos/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        console.log(data.productos);
        let productos = Array.isArray(data) ? data : (data.productos || []);
        let tbody = document.getElementById('tbodyCortesCatalogo');
        tbody.innerHTML = '';

        let cortesFiltrados = productos.filter(p => p.tipo_unidad === 'kilos' && (p.estado == 1 || p.estado === '1'));

        if (cortesFiltrados.length === 0) {
            tbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted py-2">No hay cortes registrados en catálogo (kilos).</td></tr>';
            return;
        }

        cortesFiltrados.forEach(p => {
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-start ps-3 fw-bold">🔪 ${p.nombre}</td>
                <td>
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           class="form-control form-control-sm text-center corte-input" 
                           data-producto-id="${p.id}" 
                           data-producto="${p.nombre}" 
                           placeholder="0.00">
                </td>
            `;
            tbody.appendChild(tr);
        });
    })
    .catch(err => console.error('Error al cargar cortes para el despiece:', err));
}

<?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
// Cargar cortes dinámicamente cuando se abre el modal
document.getElementById('modalNuevoDespiece').addEventListener('show.bs.modal', function () {
    cargarCortesParaDespieceModal();
});
<?php endif; ?>


let efectivoEsperadoGlobal = 0;

// 1. Guardar Gasto Operativo
function guardarGastoOperativoAjax() {
    let tipo = document.getElementById('gastoTipo').value;
    let monto = document.getElementById('gastoMonto').value;
    let descripcion = document.getElementById('gastoDescripcion').value.trim();

    if (!monto || parseFloat(monto) <= 0 || !descripcion) {
        alert('Por favor ingresa un monto válido y una descripción.');
        return;
    }

    let formData = new FormData();
    formData.append('tipo_gasto', tipo);
    formData.append('monto', monto);
    formData.append('descripcion', descripcion);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/cierre/guardar-gasto') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let modalEl = document.getElementById('modalRegistrarGasto');
            bootstrap.Modal.getInstance(modalEl).hide();
            document.getElementById('formRegistrarGasto').reset();
            alert(data.message);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

// 2. Cargar Resumen Financiero al abrir el Modal de Cierre
function cargarResumenCierre() {
    document.getElementById('cierreEfectivoReal').value = '';
    document.getElementById('lblDiferenciaCierre').innerText = '$0.00';
    document.getElementById('lblDiferenciaCierre').className = 'fs-4 fw-bold text-dark';

    fetch('<?= base_url('/cierre/resumen-json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success') {
            let d = response.data;
            efectivoEsperadoGlobal = parseFloat(d.efectivo_esperado);

            document.getElementById('cierreFondo').innerText = `$${parseFloat(d.fondo_inicial).toFixed(2)}`;
            document.getElementById('cierreVentasEfectivo').innerText = `$${parseFloat(d.ventas_efectivo).toFixed(2)}`;
            document.getElementById('cierreTotalGastos').innerText = `-$${parseFloat(d.total_gastos).toFixed(2)}`;
            document.getElementById('cierreEfectivoEsperado').innerText = `$${efectivoEsperadoGlobal.toFixed(2)}`;
            document.getElementById('cierreVentasTransferencia').innerText = `$${parseFloat(d.ventas_transferencia).toFixed(2)}`;
            
            document.getElementById('cierreEfectivoReal').value = efectivoEsperadoGlobal.toFixed(2);
            calcularDiferenciaCierre();

            // 👉 Renderizar el desglose detallado de stock en el contenedor correcto
            let contenedorStock = document.getElementById('resumenStockDesglosado');
            if (contenedorStock) {
                let htmlStock = `
                    <div class="mb-1"><strong>Aves Vivas / Enteras:</strong> ${d.stock_aves} Pzs</div>
                    <div class="mb-1"><strong>Cortes y Derivados (${parseFloat(d.stock_cortes_kg).toFixed(2)} Kg total):</strong></div>
                    <ul class="mb-0 ps-3 text-muted">
                `;

                if (d.detalle_productos && Array.isArray(d.detalle_productos) && d.detalle_productos.length > 0) {
                    d.detalle_productos.forEach(prod => {
                        if (prod.stock > 0) {
                            htmlStock += `<li>${prod.nombre}: <strong>${parseFloat(prod.stock).toFixed(2)} ${prod.unidad}</strong></li>`;
                        }
                    });
                } else {
                    htmlStock += `<li>Sin stock disponible registrado.</li>`;
                }

                htmlStock += `</ul>`;
                contenedorStock.innerHTML = htmlStock;
            }
            let listaGastosEl = document.getElementById('listaGastosDetalle');
            if (d.gastos_detalle && d.gastos_detalle.length > 0) {
                let htmlGastos = '';
                d.gastos_detalle.forEach(g => {
                    htmlGastos += `<li><strong>${g.tipo_gasto.toUpperCase()}</strong>: $${parseFloat(g.monto).toFixed(2)} <span class="text-muted">(${g.descripcion || 'Sin descripción'})</span></li>`;
                });
                listaGastosEl.innerHTML = htmlGastos;
            } else {
                listaGastosEl.innerHTML = `<li class="text-muted fst-italic">No hay gastos operativos registrados hoy.</li>`;
            }
        }
    })
    .catch(err => console.error('Error al cargar resumen de cierre:', err));
}

// 3. Calcular Diferencia en Tiempo Real al escribir el efectivo contado
function calcularDiferenciaCierre() {
    let efectivoReal = parseFloat(document.getElementById('cierreEfectivoReal').value) || 0;
    let diferencia = efectivoReal - efectivoEsperadoGlobal;

    let lblDiferencia = document.getElementById('lblDiferenciaCierre');
    lblDiferencia.innerText = `${diferencia >= 0 ? '+' : ''}$${diferencia.toFixed(2)}`;

    if (diferencia === 0) {
        lblDiferencia.className = 'fs-4 fw-bold text-success';
    } else if (diferencia > 0) {
        lblDiferencia.className = 'fs-4 fw-bold text-primary'; // Sobrante
    } else {
        lblDiferencia.className = 'fs-4 fw-bold text-danger'; // Faltante
    }
}

// 4. Procesar y Guardar el Cierre Definitivo
function procesarCierreDefinitivoAjax() {
    let efectivoReal = document.getElementById('cierreEfectivoReal').value;
    let observaciones = document.getElementById('cierreObservaciones').value.trim();

    if (efectivoReal === '') {
        alert('Por favor ingresa el monto de efectivo contado en caja.');
        return;
    }

    if (!confirm('¿Estás totalmente seguro de realizar el cierre de jornada? Esta acción registrará el arqueo oficial del día.')) {
        return;
    }

    let formData = new FormData();
    formData.append('efectivo_real', efectivoReal);
    formData.append('observaciones', observaciones);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/cierre/procesar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let modalEl = document.getElementById('modalCierreDia');
            bootstrap.Modal.getInstance(modalEl).hide();
            alert(data.message);
            location.reload(); // Recargar para refrescar estados del día
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

// Cargar clientes en el datalist al abrir el modal de nuevo pedido
function cargarClientesDatalist() {
    fetch('<?= base_url('/clientes/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(clientes => {
        let datalist = document.getElementById('listaClientes');
        datalist.innerHTML = '';
        if (Array.isArray(clientes)) {
            clientes.forEach(c => {
                let option = document.createElement('option');
                // Formato sugerido: Carlos Jesus - 937469852
                option.value = `${c.nombre} - ${c.telefono}`;
                option.setAttribute('data-tel', c.telefono);
                option.setAttribute('data-nombre', c.nombre);
                option.setAttribute('data-colonia', c.colonia || '');
                option.setAttribute('data-direccion', c.direccion || '');
                option.setAttribute('data-referencia', c.referencia || '');
                datalist.appendChild(option);
            });
        }
    })
    .catch(err => console.error('Error al cargar datalist de clientes:', err));
}

// Autocompletar los campos al seleccionar un cliente del datalist
function seleccionarClientePorNombre(valor) {
    let datalist = document.getElementById('listaClientes');
    for (let option of datalist.options) {
        if (option.value === valor) {
            document.getElementById('pedCliente').value = option.getAttribute('data-nombre');
            document.getElementById('pedTelefono').value = option.getAttribute('data-tel');
            document.getElementById('pedColonia').value = option.getAttribute('data-colonia');
            document.getElementById('pedDireccion').value = option.getAttribute('data-direccion');
            document.getElementById('pedReferencia').value = option.getAttribute('data-referencia');
            break;
        }
    }
}

// Llamar a cargarClientesDatalist() cuando se abra el modal de nuevo pedido:
document.getElementById('modalNuevoPedido').addEventListener('show.bs.modal', function () {
    cargarClientesDatalist();
    cargarProductos();
});

let descuentoAutorizado = false;
const NIP_GERENTE = "1234"; // Puedes cambiar el NIP aquí

// 1. Mostrar el modal seguro en lugar del prompt nativo
function autorizarDescuentoNIP() {
    document.getElementById('inputNipSecreto').value = '';
    let modalNip = new bootstrap.Modal(document.getElementById('modalNipSeguridad'));
    modalNip.show();
    
    // Enfocar automáticamente el input cuando abra el modal
    setTimeout(() => {
        document.getElementById('inputNipSecreto').focus();
    }, 300);
}

// 2. Verificar el NIP ingresado de forma oculta
function verificarNipSeguridad() {
    let nipIngresado = document.getElementById('inputNipSecreto').value.trim();
    
    if (nipIngresado.length !== 4) {
        alert("⚠️ El NIP debe ser de exactamente 4 dígitos.");
        return;
    }

    let formData = new FormData();
    formData.append('nip', nipIngresado);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/auth/verificar-nip') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            descuentoAutorizado = true;
            
            // Ocultar el modal de NIP
            let modalEl = document.getElementById('modalNipSeguridad');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            alert(`✅ ¡Descuento autorizado por ${data.usuario}!`);
            renderizarDetallePedido(); // Habilita la edición de precios
        } else {
            alert(`❌ ${data.message}`);
            document.getElementById('inputNipSecreto').value = '';
            document.getElementById('inputNipSecreto').focus();
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Ocurrió un error al verificar el NIP.');
    });
}

// Modificamos renderizarDetallePedido para permitir editar el precio si está autorizado
function renderizarDetallePedido() {
    let html = '';
    let total = 0;

    if (itemsTemporal.length === 0) {
        html = '<tr><td colspan="5" class="text-center text-muted fs-7">No se han añadido productos.</td></tr>';
    } else {
        itemsTemporal.forEach((i, index) => {
            total += i.subtotal;
            
            // Si está autorizado por NIP, mostramos un input editable para el precio unitario rebajado
            let columnaPrecio = descuentoAutorizado 
                ? `<input type="number" step="0.50" class="form-control form-control-sm text-center" value="${i.precio}" style="width: 90px; display:inline-block;" onchange="actualizarPrecioItem(${index}, this.value)">`
                : `$${i.precio.toFixed(2)}`;

            html += `
                <tr>
                    <td>${i.nombre}</td>
                    <td>${i.cant}</td>
                    <td>${columnaPrecio}</td>
                    <td>$${i.subtotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarItemPedido(${index})" title="Quitar producto">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }

    document.getElementById('tbodyDetallePedido').innerHTML = html;
    document.getElementById('lblTotalPedido').innerText = total.toFixed(2);
}

// Función para actualizar el precio unitario y recalcular el subtotal al aplicar descuento
function actualizarPrecioItem(index, nuevoPrecio) {
    let precioVal = parseFloat(nuevoPrecio);
    if (isNaN(precioVal) || precioVal < 0) {
        alert("Por favor ingresa un precio válido.");
        renderizarDetallePedido();
        return;
    }
    
    itemsTemporal[index].precio = precioVal;
    itemsTemporal[index].subtotal = precioVal * itemsTemporal[index].cant;
    renderizarDetallePedido();
}

function calcularTotalPiezasOinverso() {
    let jaulas = parseInt(document.getElementById('loteJaulas').value) || 0;
    let pzaPorJaula = parseInt(document.getElementById('lotePiezasPorJaula').value) || 0;
    
    if (jaulas > 0 && pzaPorJaula > 0) {
        let totalPzas = jaulas * pzaPorJaula;
        document.getElementById('lotePiezas').value = totalPzas;
    } else {
        document.getElementById('lotePiezas').value = '';
    }
}

function cambiarLogicaTipoAve(tipo) {
    let lblPeso = document.getElementById('lblPesoTotal');
    let inputPeso = document.getElementById('lotePesoTotal');
    let helpPeso = document.getElementById('helpPeso');

    if (tipo === 'gallina') {
        lblPeso.innerHTML = 'Peso Total Referencial (Kg)';
        inputPeso.removeAttribute('required');
        helpPeso.innerText = 'En gallinas se compra por pieza; el peso queda como referencia.';
    } else {
        lblPeso.innerHTML = 'Peso Total (Kg) <span class="text-danger">*</span>';
        inputPeso.setAttribute('required', 'true');
        helpPeso.innerText = 'Para pollos el peso total es obligatorio.';
    }
}

function calcularTotalesLote() {
    let jaulas = parseInt(document.getElementById('loteJaulas').value) || 0;
    let pzaPorJaula = parseInt(document.getElementById('lotePiezasPorJaula').value) || 0;
    let tipoAve = document.getElementById('mockTipo').value;
    
    // 1. Calcular Total de Piezas
    let totalPzas = 0;
    if (jaulas > 0 && pzaPorJaula > 0) {
        totalPzas = jaulas * pzaPorJaula;
        document.getElementById('lotePiezas').value = totalPzas;
    } else {
        document.getElementById('lotePiezas').value = '';
    }

    // 2. Calcular Peso Total (Total de Piezas * Peso Unitario)
    let pesoUnitario = parseFloat(document.getElementById('lotePesoUnitario').value) || 0;
    let inputPesoTotal = document.getElementById('lotePesoTotal');

    if (totalPzas > 0 && pesoUnitario > 0) {
        let pesoTotalCalculado = totalPzas * pesoUnitario;
        inputPesoTotal.value = pesoTotalCalculado.toFixed(2);
    } else {
        inputPesoTotal.value = '';
    }
}

function cambiarLogicaTipoAve(tipo) {
    let lblPesoUnit = document.getElementById('lblPesoUnitario');
    let inputPesoUnit = document.getElementById('lotePesoUnitario');
    let helpPesoUnit = document.getElementById('helpPesoUnit');

    if (tipo === 'gallina') {
        lblPesoUnit.innerHTML = 'Peso Unitario Referencial (Kg)';
        inputPesoUnit.removeAttribute('required');
        helpPesoUnit.innerText = 'En gallinas se compra por pieza; el peso es referencial.';
    } else {
        lblPesoUnit.innerHTML = 'Peso Unitario (Kg) <span class="text-danger">*</span>';
        inputPesoUnit.setAttribute('required', 'true');
        helpPesoUnit.innerText = 'Peso promedio por pollito/ave.';
    }
    
    // Recalcular al cambiar de tipo
    calcularTotalesLote();
}

function abrirModalSubirComprobante(loteId) {
    document.getElementById('subirLoteId').value = loteId;
    document.getElementById('formSubirComprobanteRapido').reset();
    let modal = new bootstrap.Modal(document.getElementById('modalSubirComprobanteRapido'));
    modal.show();
}

function enviarComprobanteRapidoAjax() {
    let formEl = document.getElementById('formSubirComprobanteRapido');
    let formData = new FormData(formEl);
    let loteId = document.getElementById('subirLoteId').value;

    let fileInput = document.getElementById('archivoComprobanteRapido');
    if (fileInput.files.length === 0) {
        alert('Por favor selecciona un archivo.');
        return;
    }

    // 1. SELECCIONAR EL BOTÓN Y BLOQUEARLO
    let btnGuardar = document.getElementById('btnGuardarComprobante');
    let textoOriginal = btnGuardar.innerHTML;
    
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Guardando...`;

    fetch('<?= base_url('/produccion/subir-comprobante') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        // 2. RESTAURAR EL BOTÓN
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;

        if (data.status === 'success') {
            let modalEl = document.getElementById('modalSubirComprobanteRapido');
            bootstrap.Modal.getInstance(modalEl).hide();
            alert(data.message);

            // Actualizar la celda de la tabla dinámicamente sin recargar
            let celda = document.getElementById(`celda-comprobante-${loteId}`);
            let urlArchivo = data.url_archivo;
            celda.innerHTML = `
                <div class="d-flex justify-content-center gap-1">
                    <button type="button" class="btn btn-sm btn-success" onclick="verComprobanteModal('${urlArchivo}')" title="Ver comprobante">
                        <i class="fa-solid fa-file-lines me-1"></i> Ver
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="abrirModalSubirComprobante(${loteId})" title="Cambiar archivo">
                        <i class="fa-solid fa-upload"></i>
                    </button>
                </div>
            `;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        // 3. RESTAURAR EL BOTÓN SI OCURRE UN ERROR DE RED
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
        alert('Ocurrió un error al subir el archivo.');
    });
}

function abrirModalVerificarTransferencia(id, cliente, total) {
    document.getElementById('verificarPedidoId').value = id;
    document.getElementById('verificarFolioTxt').innerText = `#${id}`;
    document.getElementById('verificarClienteTxt').innerText = cliente;
    document.getElementById('verificarMontoTxt').innerText = `$${parseFloat(total).toFixed(2)}`;

    let modal = new bootstrap.Modal(document.getElementById('modalVerificarTransferencia'));
    modal.show();
}

function confirmarLlegadaTransferencia() {
    let id = document.getElementById('verificarPedidoId').value;
    if (!id) return;

    let formData = new FormData();
    formData.append('id', id);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/pedidos/verificar-transferencia') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let fila = document.getElementById(`fila-pedido-${id}`);
            if (fila) {
                // 1. Quitar el resaltado de advertencia del renglón
                fila.classList.remove('table-warning');

                // 2. Cambiar el botón por la insignia fija de verificado
                let celdas = fila.querySelectorAll('td');
                if (celdas.length > 3) {
                    celdas[3].innerHTML = `<span class="badge bg-primary text-white" title="Transferencia Verificada"><i class="fa-solid fa-circle-check me-1"></i> Transferencia</span>`;
                }
            }

            // Ocultar modal
            let modalEl = document.getElementById('modalVerificarTransferencia');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();
        } else {
            alert('Error al verificar la transferencia.');
        }
    })
    .catch(err => console.error('Error:', err));
}

// Función para mostrar/ocultar el desglose al hacer clic en "(Ver detalle)"
function toggleDesgloseGastos() {
    let fila = document.getElementById('filaDetalleGastos');
    let btn = document.getElementById('btnToggleGastos');
    if (fila.style.display === 'none') {
        fila.style.display = '';
        btn.innerText = '(Ocultar)';
    } else {
        fila.style.display = 'none';
        btn.innerText = '(Ver detalle)';
    }
}

// Carga AJAX del Historial de Pedidos Pasados
function cargarHistorialPedidosDinamico() {
    let inicio = document.getElementById('repFechaInicio').value;
    let fin = document.getElementById('repFechaFin').value;
    let buscar = document.getElementById('repBusquedaHistorial').value.trim();

    let tbody = document.querySelector('#tablaHistorialPedidos tbody');
    tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-3"><i class="fa-solid fa-spinner fa-spin me-1"></i> Buscando historial de pedidos...</td></tr>';

    fetch(`<?= base_url('/reportes/historial-pedidos-json') ?>?inicio=${inicio}&fin=${fin}&buscar=${encodeURIComponent(buscar)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let html = '';
            if (data.pedidos && data.pedidos.length > 0) {
                data.pedidos.forEach(p => {
                    let badgeEstado = p.estado === 'entregado' ? 'bg-success' : (p.estado === 'cancelado' ? 'bg-danger' : 'bg-warning text-dark');
                    let textoEstado = p.estado === 'entregado' ? 'Entregado' : (p.estado === 'cancelado' ? 'Cancelado' : 'En Proceso');

                    html += `
                        <tr>
                            <td><span class="badge bg-secondary">#${p.id}</span></td>
                            <td class="fw-bold">${p.cliente}</td>
                            <td>${p.telefono || 'N/D'}</td>
                            <td><span class="badge bg-light text-dark border">${(p.tipo_entrega || '').toUpperCase()}</span></td>
                            <td><small>${p.colonia || p.direccion || 'N/D'}</small></td>
                            <td class="text-success fw-bold">$${parseFloat(p.total).toFixed(2)}</td>
                            <td><span class="badge ${badgeEstado}">${textoEstado}</span></td>
                            <td><small class="text-muted">${p.created_at}</small></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info" onclick="verDetallePedidoHistorico(${p.id})">
                                    <i class="fa-solid fa-eye me-1"></i> Detalle
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                html = '<tr><td colspan="9" class="text-center text-muted py-3">No se encontraron pedidos pasados en el rango de búsqueda.</td></tr>';
            }
            tbody.innerHTML = html;
        }
    })
    .catch(err => {
        console.error('Error al cargar historial de pedidos:', err);
        tbody.innerHTML = '<tr><td colspan="9" class="text-center text-danger py-3">Error al consultar el historial de pedidos.</td></tr>';
    });
}

// Abrir modal de detalle para un pedido histórico
function verDetallePedidoHistorico(idPedido) {
    document.getElementById('lblIdPedidoModal').innerText = idPedido;
    let tbody = document.getElementById('tbodyDetalleHistorico');
    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Cargando productos...</td></tr>';

    fetch(`<?= base_url('/reportes/detalle-pedido-historico') ?>/${idPedido}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let html = '';
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                    let cant = item.cantidad || item.peso || 0;
                    let pU = item.precio_unitario || 0;
                    let sub = item.subtotal || (cant * pU);
                    html += `
                        <tr>
                            <td class="text-start fw-bold">Producto #${item.producto_id}</td>
                            <td>${cant}</td>
                            <td>$${parseFloat(pU).toFixed(2)}</td>
                            <td class="text-end pe-3 fw-bold text-success">$${parseFloat(sub).toFixed(2)}</td>
                        </tr>
                    `;
                });
            } else {
                html = '<tr><td colspan="4" class="text-center text-muted py-2">Sin detalles registrados para este pedido.</td></tr>';
            }
            tbody.innerHTML = html;

            let modal = new bootstrap.Modal(document.getElementById('modalDetalleHistorico'));
            modal.show();
        }
    })
    .catch(err => console.error('Error al cargar detalle histórico:', err));
}

// Búsqueda en tiempo real del historial al presionar Enter en el filtro
document.addEventListener('DOMContentLoaded', function() {
    let inputBusqueda = document.getElementById('repBusquedaHistorial');
    if (inputBusqueda) {
        inputBusqueda.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                cargarHistorialPedidosDinamico();
            }
        });
    }
});

// Carga AJAX del Concentrado por Colonia (Zonas)
function cargarConcentradoColonias() {
    let inicio = document.getElementById('repFechaInicio').value;
    let fin = document.getElementById('repFechaFin').value;

    let tbodyZonas = document.querySelector('#tablaZonasReporte tbody');
    tbodyZonas.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3"><i class="fa-solid fa-spinner fa-spin me-1"></i> Cargando datos por colonia...</td></tr>';

    fetch(`<?= base_url('/reportes/datos-json') ?>?inicio=${inicio}&fin=${fin}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let htmlZonas = '';
            if (data.zonas && data.zonas.length > 0) {
                data.zonas.forEach(z => {
                    htmlZonas += `
                        <tr>
                            <td class="fw-bold"><i class="fa-solid fa-location-dot text-danger me-2"></i> ${z.colonia}</td>
                            <td><span class="badge bg-secondary">${z.total_pedidos} pedidos</span></td>
                            <td class="text-success fw-bold">$${parseFloat(z.volumen_venta).toFixed(2)}</td>
                            <td><span class="badge bg-success">Zona Fuerte</span></td>
                        </tr>
                    `;
                });
            } else {
                htmlZonas = `<tr><td colspan="4" class="text-center text-muted py-3">No hay pedidos registrados por colonia en este periodo.</td></tr>`;
            }
            tbodyZonas.innerHTML = htmlZonas;
        } else {
            tbodyZonas.innerHTML = `<tr><td colspan="4" class="text-center text-danger py-3">Error al obtener los datos de zonas.</td></tr>`;
        }
    })
    .catch(err => {
        console.error('Error al cargar concentrado por colonia:', err);
        tbodyZonas.innerHTML = `<tr><td colspan="4" class="text-center text-danger py-3">Ocurrió un error de conexión con el servidor.</td></tr>`;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Cargar métricas y reportes al iniciar la página
    if (typeof cargarReportesDinamicos === 'function') {
        cargarReportesDinamicos();
    }
});

// Cargar la lista de usuarios y sus grupos desde el servidor
function cargarUsuariosPermisos() {
    let tbody = document.getElementById('tablaUsuariosShield');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Cargando usuarios y roles...</td></tr>';

    fetch('<?= base_url('/admin/permisos/usuarios-json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' && data.usuarios.length > 0) {
            let html = '';
            data.usuarios.forEach(u => {
                let badgeRol = 'bg-secondary';
                if (u.grupo === 'superadmin') badgeRol = 'bg-danger';
                else if (u.grupo === 'gerente') badgeRol = 'bg-warning text-dark';
                else if (u.grupo === 'repartidor') badgeRol = 'bg-info text-dark';
                else if (u.grupo === 'mostrador') badgeRol = 'bg-primary';

                html += `
                    <tr>
                        <td class="ps-3">#${u.id}</td>
                        <td class="fw-bold">
                            <i class="fa-solid fa-user text-secondary me-1"></i> ${u.username} <br>
                            <small class="text-muted">${u.email}</small>
                        </td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-center">
                            <span class="badge ${badgeRol} fs-6">${u.grupo_titulo || u.grupo || 'Sin Rol'}</span>
                        </td>
                        <td class="text-center">
                            <select class="form-select form-select-sm d-inline-block w-auto" onchange="cambiarRolUsuario(${u.id}, this.value)">
                                <!--<option value="superadmin" ${u.grupo === 'superadmin' ? 'selected' : ''}>Super Admin</option>-->
                                <option value="gerente" ${u.grupo === 'gerente' ? 'selected' : ''}>Gerente</option>
                                <option value="mostrador" ${u.grupo === 'mostrador' ? 'selected' : ''}>Mostrador</option>
                                <!--<option value="repartidor" ${u.grupo === 'repartidor' ? 'selected' : ''}>Repartidor</option>-->
                            </select>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No hay usuarios registrados en el sistema.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error al cargar usuarios:', err);
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-3">Error al conectar con el servidor.</td></tr>';
    });
}

// Cambiar el rol de un usuario dinámicamente con AJAX
function cambiarRolUsuario(userId, nuevoGrupo) {
    let formData = new FormData();
    formData.append('user_id', userId);
    formData.append('grupo', nuevoGrupo);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/admin/permisos/asignar-grupo') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            cargarUsuariosPermisos(); // Recargar la tabla dinámicamente
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error al cambiar rol:', err));
}

function guardarNuevoUsername() {
    let nuevoNombre = document.getElementById('inputNuevoUsername').value.trim();
    if (!nuevoNombre) {
        alert('Ingresa un nombre de usuario válido.');
        return;
    }

    let formData = new FormData();
    formData.append('username', nuevoNombre);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/perfil/actualizar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload(); // Recarga para actualizar el nombre en el Navbar
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

function guardarUsuarioShieldAjax() {
    let username = document.getElementById('shUsername').value.trim();
    let email = document.getElementById('shEmail').value.trim();
    let password = document.getElementById('shPassword').value;
    let grupo = document.getElementById('shGrupo').value;

    if (!username || !email || !password || !grupo) {
        alert('Por favor completa todos los campos.');
        return;
    }

    let formData = new FormData();
    formData.append('username', username);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('grupo', grupo);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/admin/permisos/crear-usuario') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let modalEl = document.getElementById('modalNuevoUsuarioShield');
            bootstrap.Modal.getInstance(modalEl).hide();
            document.getElementById('formNuevoUsuarioShield').reset();
            alert(data.message);
            
            // Recargar la tabla de usuarios automáticamente
            if (typeof cargarUsuariosPermisos === 'function') {
                cargarUsuariosPermisos();
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

function cargarConfiguracionSistema() {
    fetch('<?= base_url('/admin/configuracion/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('inputCompanyNombre').value = data.company_name;
        }
    })
    .catch(err => console.error('Error al cargar configuración:', err));
}

function guardarConfiguracionSistema() {
    let nuevoNombre = document.getElementById('inputCompanyNombre').value.trim();
    if (!nuevoNombre) {
        alert('El nombre no puede estar vacío.');
        return;
    }

    let formData = new FormData();
    formData.append('company_name', nuevoNombre);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/admin/configuracion/guardar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            // Actualizar visualmente en tiempo real sin recargar
            document.getElementById('lblBrandNombre').innerText = data.company_name;
            let tickBrand = document.getElementById('tickBrandNombre');
            if (tickBrand) tickBrand.innerText = data.company_name;

            let modalEl = document.getElementById('modalConfiguracionSistema');
            bootstrap.Modal.getInstance(modalEl).hide();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

function cambiarSucursal(sucursalId, nombreSucursal) {
    // 1. Actualizar texto visual en el Navbar de forma dinámica
    let lblNav = document.getElementById('lblSucursalNavbar');
    if (lblNav) lblNav.innerText = nombreSucursal;

    // 2. Enviar petición por AJAX para actualizar la sesión en PHP
    let formData = new FormData();
    formData.append('sucursal_id', sucursalId);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/admin/sucursal/cambiar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Recargar datos y tablas operativas de la sucursal activa
            if (typeof actualizarMetricasCards === 'function') actualizarMetricasCards();
            if (typeof cargarInventarioGeneral === 'function') cargarInventarioGeneral();
            if (typeof cargarReportesDinamicos === 'function') cargarReportesDinamicos();
            
            location.reload(); 
        } else {
            alert('No se pudo cambiar de sucursal.');
        }
    })
    .catch(err => console.error('Error al cambiar de sucursal:', err));
}

let cacheSucursalesDirectorio = [];

// 1. Cargar la tabla del directorio de sucursales
function cargarSucursalesDirectorio() {
    let tbody = document.getElementById('tablaDirectorioSucursales');
    if (!tbody) return;
    
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Cargando sucursales...</td></tr>';

    fetch('<?= base_url('/admin/sucursales/json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(sucursales => {
        cacheSucursalesDirectorio = sucursales || [];
        let html = '';

        if (!sucursales || sucursales.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-muted">No hay sucursales registradas.</td></tr>';
            return;
        }

        sucursales.forEach(s => {
            let badgeEstado = s.estado == 1 ? '<span class="badge bg-success">Activa</span>' : '<span class="badge bg-danger">Inactiva</span>';
            
            html += `
            <tr id="fila_sucursal_${s.id}">
                <td class="ps-3">#${s.id}</td>
                <td class="fw-bold"><i class="fa-solid fa-store text-warning me-2"></i>${s.nombre_municipio} ${badgeEstado}</td>
                <td><small>${s.direccion || 'Sin dirección registrada'}</small></td>
                <td><i class="fa-solid fa-phone text-success me-1"></i>${s.telefono || 'N/D'}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editarSucursalDirectorio(${s.id})" title="Editar datos de sucursal">
                        <i class="fa-solid fa-pen-to-square"></i> Editar
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarSucursalAjax(${s.id})" title="Dar de baja">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
            `;
        });
        tbody.innerHTML = html;
    })
    .catch(err => {
        console.error('Error al cargar sucursales:', err);
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-3">Error al cargar datos desde el servidor.</td></tr>';
    });
}

// 2. Limpiar modal para crear una nueva sucursal
function prepararModalSucursalNueva() {
    document.getElementById('sucursalId').value = '';
    document.getElementById('formSucursalDirectorio').reset();
    document.getElementById('tituloModalSucursal').innerHTML = '<i class="fa-solid fa-store me-2"></i> Alta de Sucursal';
}

// 3. Llenar modal para editar sucursal existente
function editarSucursalDirectorio(id) {
    let s = cacheSucursalesDirectorio.find(x => parseInt(x.id) === parseInt(id));
    if (!s) return;

    document.getElementById('sucursalId').value = s.id;
    document.getElementById('sucNombre').value = s.nombre_municipio || '';
    document.getElementById('sucDireccion').value = s.direccion || '';
    document.getElementById('sucTelefono').value = s.telefono || '';

    document.getElementById('tituloModalSucursal').innerHTML = `<i class="fa-solid fa-pen-to-square me-2"></i> Editar Sucursal #${s.id}`;

    let modalEl = document.getElementById('modalNuevaSucursal');
    let modalInstance = new bootstrap.Modal(modalEl);
    modalInstance.show();
}

// 4. Guardar o Actualizar sucursal por AJAX
function guardarSucursalAjax() {
    let id        = document.getElementById('sucursalId').value;
    let nombre    = document.getElementById('sucNombre').value.trim();
    let direccion = document.getElementById('sucDireccion').value.trim();
    let telefono  = document.getElementById('sucTelefono').value.trim();

    if (!nombre) {
        alert('Por favor ingresa el nombre o municipio de la sucursal.');
        return;
    }

    let formData = new FormData();
    if (id) formData.append('id', id);
    formData.append('nombre_municipio', nombre);
    formData.append('direccion', direccion);
    formData.append('telefono', telefono);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/admin/sucursales/guardar') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let modalEl = document.getElementById('modalNuevaSucursal');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            prepararModalSucursalNueva();
            alert(data.message);
            cargarSucursalesDirectorio(); // Recargar la tabla
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Ocurrió un error al procesar la solicitud.');
    });
}

// 5. Eliminar / Dar de baja sucursal por AJAX
function eliminarSucursalAjax(id) {
    if (!confirm('¿Estás seguro de dar de baja esta sucursal?')) return;

    fetch(`<?= base_url('/admin/sucursales/eliminar') ?>/${id}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            cargarSucursalesDirectorio();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

function cargarUsuariosPermisos() {
    let tbody = document.getElementById('tablaUsuariosShield');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Cargando usuarios y roles...</td></tr>';

    fetch('<?= base_url('/admin/permisos/usuarios-json') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' && data.usuarios.length > 0) {
            window.cacheUsuariosList = data.usuarios; // Guardamos en caché para editar fácil
            let html = '';
            data.usuarios.forEach(u => {
                let badgeEstado = u.active == 1 
                ? '<span class="badge bg-success">Activo</span>' 
                : '<span class="badge bg-danger">Inactivo</span>';
                let badgeRol = 'bg-secondary';
                if (u.grupo === 'superadmin') badgeRol = 'bg-danger';
                else if (u.grupo === 'gerente') badgeRol = 'bg-warning text-dark';
                else if (u.grupo === 'mostrador') badgeRol = 'bg-primary';

                html += `
                    <tr>
                        <td class="ps-3">#${u.id}</td>
                        <td class="fw-bold">
                            <i class="fa-solid fa-user text-secondary me-1"></i> ${u.username} <br>
                            <small class="text-muted">${u.email}</small>
                        </td>
                        <td>${badgeEstado}</td>
                        <td class="text-center">
                            <span class="badge ${badgeRol} fs-6">${u.grupo_titulo || u.grupo || 'Sin Rol'}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary fw-bold" onclick="abrirModalEditarUsuario(${u.id})">
                                <i class="fa-solid fa-user-pen me-1"></i> Editar Completo
                            </button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-muted">No hay usuarios registrados.</td></tr>';
        }
    })
    .catch(err => console.error('Error al cargar usuarios:', err));
}

function abrirModalEditarUsuario(id) {
    let usuario = window.cacheUsuariosList.find(x => parseInt(x.id) === parseInt(id));
    if (!usuario) return;
    console.log(usuario);
    document.getElementById('editUserId').value = usuario.id;
    document.getElementById('editUsername').value = usuario.username || '';
    document.getElementById('editEmail').value = usuario.email || '';
    document.getElementById('editPassword').value = '';
    document.getElementById('editGrupo').value = usuario.grupo || 'mostrador';
    document.getElementById('editActive').value = usuario.active !== undefined ? usuario.active : 1; // Cargar estado actual

    document.getElementById('tituloEditUser').innerHTML = `<i class="fa-solid fa-user-pen me-2 text-warning"></i> Editar Usuario: ${usuario.username}`;

    let modal = new bootstrap.Modal(document.getElementById('modalEditarUsuarioCompleto'));
    modal.show();
}

function guardarEdicionUsuarioAjax() {
    let id       = document.getElementById('editUserId').value;
    let username = document.getElementById('editUsername').value.trim();
    let email    = document.getElementById('editEmail').value.trim();
    let password = document.getElementById('editPassword').value;
    let grupo    = document.getElementById('editGrupo').value;
    let active   = document.getElementById('editActive').value;
    let sucursalId = document.getElementById('editSucursalId').value;


    if (!username || !email) {
        alert('El nombre de usuario y el correo son obligatorios.');
        return;
    }

    let formData = new FormData();
    formData.append('user_id', id);
    formData.append('username', username);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('grupo', grupo);
    formData.append('active', active); // Enviar el estado seleccionado
    formData.append('sucursal_id', sucursalId);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('/admin/permisos/actualizar-usuario') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            let modalEl = document.getElementById('modalEditarUsuarioCompleto');
            bootstrap.Modal.getInstance(modalEl).hide();
            alert(data.message);
            cargarUsuariosPermisos(); // Refrescar tabla
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error('Error al actualizar usuario:', err));
}

<?php if (auth()->user()->inGroup('superadmin', 'gerente')): ?>
document.getElementById('editSucursalId').value = usuario.sucursal_id || '';
<?php endif; ?>
</script>
</body>
</html>