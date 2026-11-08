<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Precios Diarios - Expendio Avícola</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-tags text-warning me-2"></i> Control de Precios Diarios</h2>
        <span class="badge bg-primary fs-6"><?= date('d/m/Y') ?></span>
    </div>

    <!-- Tabla de Productos -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaPrecios">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Unidad de Medida</th>
                            <th>Precio Actual</th>
                            <th>Nuevo Precio</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPrecios">
                        <!-- Carga dinámica vía AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle & jQuery/Fetch AJAX -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    cargarPrecios();
});

function cargarPrecios() {
    fetch('<?= base_url('precios/listar') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                let html = '';
                data.data.forEach(prod => {
                    html += `
                        <tr>
                            <td class="fw-bold">${prod.nombre}</td>
                            <td><span class="badge bg-secondary">${prod.tipo_unidad}</span></td>
                            <td class="text-success fw-bold">$${parseFloat(prod.precio_venta_actual).toFixed(2)}</td>
                            <td>
                                <input type="number" step="0.50" class="form-control form-control-sm w-50" id="precio_${prod.id}" value="${prod.precio_venta_actual}">
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" onclick="actualizarPrecio(${prod.id})">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Guardar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('tbodyPrecios').innerHTML = html;
            }
        });
}

function actualizarPrecio(id) {
    let nuevoPrecio = document.getElementById(`precio_${id}`).value;

    let formData = new FormData();
    formData.append('producto_id', id);
    formData.append('precio_nuevo', nuevoPrecio);

    fetch('<?= base_url('precios/actualizar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            cargarPrecios();
        } else {
            alert('Error: ' + data.message);
        }
    });
}
</script>
</body>
</html>