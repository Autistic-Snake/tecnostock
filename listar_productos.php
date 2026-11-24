<?php
require_once 'auth.php';
require_once 'conexion.php';
require_once 'funciones.php';

$titulo = 'Productos';
$buscar = trim($_GET['buscar'] ?? '');
$like = '%' . $buscar . '%';

// Un marcador con nombre no se puede repetir, por eso b1, b2 y b3
$sql = 'SELECT p.id_producto, p.codigo, p.nombre, c.nombre AS categoria,
               p.precio, p.stock_actual, p.stock_minimo, p.estado
        FROM productos p
        INNER JOIN categorias c ON c.id_categoria = p.id_categoria
        WHERE p.codigo LIKE :b1 OR p.nombre LIKE :b2 OR c.nombre LIKE :b3
        ORDER BY p.estado DESC, p.nombre';
$stmt = $pdo->prepare($sql);
$stmt->execute([':b1' => $like, ':b2' => $like, ':b3' => $like]);
$productos = $stmt->fetchAll();

// Cuenta los productos activos con stock bajo el mínimo
$bajos = 0;
foreach ($productos as $p) {
    if ($p['estado'] == 1 && $p['stock_actual'] < $p['stock_minimo']) {
        $bajos++;
    }
}

require_once 'encabezado.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Productos</h1>
    <a class="btn btn-primary" href="nuevo_producto.php">Nuevo producto</a>
</div>

<?php if ($bajos > 0): ?>
    <div class="alert alert-warning">
        <strong>Atención:</strong> hay <?= $bajos ?> producto(s) con stock bajo el mínimo.
    </div>
<?php endif; ?>

<form class="row g-2 mb-3" method="get" action="listar_productos.php">
    <div class="col-md-8">
        <input type="search" class="form-control" name="buscar" value="<?= e($buscar) ?>"
               placeholder="Buscar por nombre, código o categoría">
    </div>
    <div class="col-md-4 d-flex gap-2">
        <button class="btn btn-secondary" type="submit">Buscar</button>
        <a class="btn btn-outline-secondary" href="listar_productos.php">Limpiar</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle bg-white">
        <thead>
            <tr>
                <th>Código</th><th>Nombre</th><th>Categoría</th><th>Precio</th>
                <th>Stock</th><th>Estado</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$productos): ?>
                <tr><td colspan="7" class="text-center text-muted">No se encontraron productos.</td></tr>
            <?php endif; ?>
            <?php foreach ($productos as $p):
                $bajo = $p['estado'] == 1 && $p['stock_actual'] < $p['stock_minimo'];
            ?>
                <tr class="<?= $bajo ? 'table-warning' : '' ?>">
                    <td><?= e($p['codigo']) ?></td>
                    <td><?= e($p['nombre']) ?></td>
                    <td><?= e($p['categoria']) ?></td>
                    <td>$<?= number_format((float) $p['precio'], 0, ',', '.') ?></td>
                    <td><?= (int) $p['stock_actual'] ?>
                        <small class="text-muted">(mín. <?= (int) $p['stock_minimo'] ?>)</small></td>
                    <td>
                        <?php if ($p['estado'] == 0): ?>
                            <span class="badge text-bg-secondary">Inactivo</span>
                        <?php elseif ($bajo): ?>
                            <span class="badge text-bg-warning">Stock bajo</span>
                        <?php else: ?>
                            <span class="badge text-bg-success">Disponible</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-nowrap">
                        <?php if ($p['estado'] == 1): ?>
                            <a class="btn btn-sm btn-outline-primary"
                               href="editar_producto.php?id=<?= (int) $p['id_producto'] ?>">Editar</a>
                            <a class="btn btn-sm btn-outline-success"
                               href="nuevo_movimiento.php?producto=<?= (int) $p['id_producto'] ?>">Movimiento</a>
                            <form action="desactivar_producto.php" method="post" class="d-inline"
                                  onsubmit="return confirm('¿Desactivar este producto?');">
                                <input type="hidden" name="id_producto" value="<?= (int) $p['id_producto'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Desactivar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once 'pie.php'; ?>