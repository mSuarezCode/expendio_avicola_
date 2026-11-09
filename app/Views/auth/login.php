<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Expendio Avícola Yireth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card{
            border-radius: 2em;
        }
    </style>
</head>
<body class="bg-dark d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow p-4" style="width: 400px;">
        <!-- LOGOTIPO DEL EXPENDIO -->
        <div class="text-center mb-3">
            <img src="<?= base_url('images/logo.jpg') ?>" alt="Expendio de gallina Yireth" class="img-fluid rounded-circle shadow-sm border border-2 border-warning" style="width: 110px; height: 110px; object-fit: cover;">
            <h5 class="fw-bold mt-2 mb-0">Expendio Avícola Yireth</h5>
            <p class="text-muted small">Panel de Control Interno</p>
        </div>

        <!-- Alertas de error de Shield si las hay -->
        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger small py-2"><?= session('error') ?></div>
        <?php endif ?>
        <?php if (session()->has('errors')) : ?>
            <ul class="alert alert-danger small py-2 mb-3">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>

        <form action="<?= url_to('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label small fw-bold"><i class="fa-solid fa-envelope me-1"></i> Correo Electrónico</label>
                <input type="email" class="form-control" name="email" value="<?= old('email') ?>" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold"><i class="fa-solid fa-lock me-1"></i> Contraseña</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember" <?php if (old('remember')): ?> checked <?php endif ?>>
                <label class="form-check-label small" for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn btn-warning w-100 fw-bold text-dark shadow-sm">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Iniciar Sesión
            </button>
        </form>
    </div>

</body>
</html>