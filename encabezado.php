<?php
// encabezado.php: parte superior común. Cada página define antes $titulo.
$titulo = $titulo ?? 'TecnoStock';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?> | TecnoStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="listar_productos.php">TecnoStock</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="listar_productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="nuevo_producto.php">Nuevo producto</a></li>
                <li class="nav-item"><a class="nav-link" href="nuevo_movimiento.php">Registrar movimiento</a></li>
                <li class="nav-item"><a class="nav-link" href="listar_movimientos.php">Historial</a></li>
            </ul>
            <span class="navbar-text me-3"><?= e($_SESSION['nombre'] ?? '') ?></span>
            <a class="btn btn-outline-light btn-sm" href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</nav>
<main class="container py-4">
<?php if (!empty($_SESSION['mensaje'])): ?>
    <div class="alert alert-<?= e($_SESSION['tipo'] ?? 'success') ?> alert-dismissible fade show" role="alert">
        <?= e($_SESSION['mensaje']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
<?php endif; ?>