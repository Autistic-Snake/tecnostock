<?php
require_once 'auth.php';
require_once 'conexion.php';
require_once 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}

[$d, $errores] = validar_producto($_POST, true);

if ($errores) {
    mensaje(implode(' ', $errores), 'danger');
    ir_a('nuevo_producto.php');
}

$sql = 'INSERT INTO productos
            (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, id_categoria)
        VALUES
            (:codigo, :nombre, :descripcion, :precio, :stock_actual, :stock_minimo, :id_categoria)';

try {
    $pdo->prepare($sql)->execute([
        ':codigo' => $d['codigo'],
        ':nombre' => $d['nombre'],
        ':descripcion' => $d['descripcion'] !== '' ? $d['descripcion'] : null,
        ':precio' => $d['precio'],
        ':stock_actual' => $d['stock_actual'],
        ':stock_minimo' => $d['stock_minimo'],
        ':id_categoria' => $d['categoria'],
    ]);
    mensaje('Producto guardado correctamente.');
    ir_a('listar_productos.php');
} catch (PDOException $error) {
    // 1062 es el código de MySQL para un valor UNIQUE repetido
    if ((int) ($error->errorInfo[1] ?? 0) === 1062) {
        mensaje('Ya existe un producto con ese código.', 'danger');
    } else {
        mensaje('No fue posible guardar el producto.', 'danger');
    }
    ir_a('nuevo_producto.php');
}