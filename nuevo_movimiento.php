<?php
require_once 'auth.php';
require_once 'conexion.php';
require_once 'funciones.php';

$titulo = 'Registrar movimiento';
$productos = $pdo->query(
    'SELECT id_producto, codigo, nombre, stock_actual
     FROM productos WHERE estado = 1 ORDER BY nombre'
)->fetchAll();
$seleccionado = filter_input(INPUT_GET, 'producto', FILTER_VALIDATE_INT);

require_once 'encabezado.php';
?>
<div class="card shadow-sm mx-auto" style="max-width: 760px;">
    <div class="card-body p-4">
        <h1 class="h3 mb-3">Registrar entrada o salida</h1>
        <form id="form-movimiento" action="guardar_movimiento.php" method="post">
            <div class="row g-3">
                <div class="col-12">
                    <label for="id_producto" class="form-label">Producto</label>
                    <select class="form-select" id="id_producto" name="id_producto" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= (int) $p['id_producto'] ?>"
                                    data-stock="<?= (int) $p['stock_actual'] ?>"
                                    <?= $p['id_producto'] == $seleccionado ? 'selected' : '' ?>>
                                <?= e($p['codigo'] . ' - ' . $p['nombre']) ?>
                                (stock: <?= (int) $p['stock_actual'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <span class="form-label d-block">Tipo de movimiento</span>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo"
                               id="tipo_entrada" value="entrada" required>
                        <label class="form-check-label" for="tipo_entrada">Entrada</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo"
                               id="tipo_salida" value="salida">
                        <label class="form-check-label" for="tipo_salida">Salida</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad"
                           min="1" step="1" required>
                </div>
                <div class="col-12">
                    <label for="observacion" class="form-label">Observación (opcional)</label>
                    <input type="text" class="form-control" id="observacion" name="observacion" maxlength="255">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Registrar movimiento</button>
                    <a class="btn btn-outline-secondary" href="listar_productos.php">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once 'pie.php'; ?>