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

try {
    $stmt = $pdo->prepare('UPDATE productos SET estado = 0 WHERE id_producto = :id');
    $stmt->execute([':id' => $id]);
    mensaje('Producto desactivado. Su historial se conserva.');
} catch (PDOException $error) {
    mensaje('No fue posible desactivar el producto.', 'danger');
}
ir_a('listar_productos.php');