<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expendio Avícola - Proveedores</title>
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
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/produccion') ?>"><i class="fa-solid fa-feather me-1"></i> Producción / Lotes</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?= base_url('/proveedores') ?>"><i class="fa-solid fa-truck-field me-1"></i> Proveedores</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- VISTA PROVEEDORES -->
        <div id="vista-proveedores">
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
                            <tbody>
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>