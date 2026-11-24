<?php
require_once 'auth.php';
require_once 'conexion.php';
require_once 'funciones.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    mensaje('Producto no válido.', 'danger');
    ir_a('listar_productos.php');
}

$stmt = $pdo->prepare('SELECT * FROM productos WHERE id_producto = :id AND estado = 1');
$stmt->execute([':id' => $id]);
$p = $stmt->fetch();
if (!$p) {
    mensaje('El producto no existe o está inactivo.', 'danger');
    ir_a('listar_productos.php');
}

$categorias = $pdo->query(
    'SELECT id_categoria, nombre FROM categorias WHERE estado = 1 ORDER BY nombre'
)->fetchAll();

$titulo = 'Editar producto';
require_once 'encabezado.php';
?>
<div class="card shadow-sm mx-auto" style="max-width: 760px;">
    <div class="card-body p-4">
        <h1 class="h3 mb-3">Editar producto</h1>
        <form action="actualizar_producto.php" method="post">
            <input type="hidden" name="id_producto" value="<?= (int) $p['id_producto'] ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" class="form-control" id="codigo" value="<?= e($p['codigo']) ?>" disabled>
                </div>
                <div class="col-md-8">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre"
                           value="<?= e($p['nombre']) ?>" minlength="3" maxlength="100" required>
                </div>
                <div class="col-md-6">
                    <label for="categoria_id" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= (int) $c['id_categoria'] ?>"
                                <?= $c['id_categoria'] == $p['id_categoria'] ? 'selected' : '' ?>>
                                <?= e($c['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="precio" name="precio"
                           value="<?= e($p['precio']) ?>" min="0" step="0.01" required>
                </div>
                <div class="col-md-6">
                    <label for="stock_actual" class="form-label">Stock actual</label>
                    <input type="number" class="form-control" id="stock_actual" name="stock_actual"
                           value="<?= (int) $p['stock_actual'] ?>" min="0" step="1" required>
                </div>
                <div class="col-md-6">
                    <label for="stock_minimo" class="form-label">Stock mínimo</label>
                    <input type="number" class="form-control" id="stock_minimo" name="stock_minimo"
                           value="<?= (int) $p['stock_minimo'] ?>" min="0" step="1" required>
                </div>
                <div class="col-12">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" maxlength="255"><?= e($p['descripcion']) ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a class="btn btn-outline-secondary" href="listar_productos.php">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once 'pie.php'; ?>