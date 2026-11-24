<?php
require_once 'auth.php';
require_once 'conexion.php';
require_once 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}

$id = filter_var($_POST['id_producto'] ?? '', FILTER_VALIDATE_INT);
if (!$id) {
    mensaje('Producto no válido.', 'danger');
    ir_a('listar_productos.php');
}

[$d, $errores] = validar_producto($_POST, false);
if ($errores) {
    mensaje(implode(' ', $errores), 'danger');
    ir_a('editar_producto.php?id=' . $id);
}

$sql = 'UPDATE productos
        SET nombre = :nombre, descripcion = :descripcion, precio = :precio,
            stock_actual = :stock_actual, stock_minimo = :stock_minimo,
            id_categoria = :id_categoria
        WHERE id_producto = :id AND estado = 1';

try {
    $pdo->prepare($sql)->execute([
        ':nombre' => $d['nombre'],
        ':descripcion' => $d['descripcion'] !== '' ? $d['descripcion'] : null,
        ':precio' => $d['precio'],
        ':stock_actual' => $d['stock_actual'],
        ':stock_minimo' => $d['stock_minimo'],
        ':id_categoria' => $d['categoria'],
        ':id' => $id,
    ]);
    mensaje('Producto actualizado correctamente.');
    ir_a('listar_productos.php');
} catch (PDOException $error) {
    mensaje('No fue posible actualizar el producto.', 'danger');
    ir_a('editar_producto.php?id=' . $id);
}