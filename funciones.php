<?php
// funciones.php: ayudas usadas en varias páginas

// Escapa texto antes de mostrarlo en HTML (evita que se ejecute código)
function e($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

// Guarda un mensaje para mostrarlo en la siguiente página
function mensaje($texto, $tipo = 'success')
{
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo'] = $tipo;
}

// Redirige a otra página y detiene el script
function ir_a($pagina)
{
    header('Location: ' . $pagina);
    exit;
}

// Valida los datos del formulario de producto.
// Devuelve [datos limpios, lista de errores].
function validar_producto(array $post, $esNuevo)
{
    $errores = [];
    $d = [
        'codigo' => strtoupper(trim($post['codigo'] ?? '')),
        'nombre' => trim($post['nombre'] ?? ''),
        'descripcion' => trim($post['descripcion'] ?? ''),
        'categoria' => filter_var($post['categoria_id'] ?? '', FILTER_VALIDATE_INT),
        'precio' => filter_var($post['precio'] ?? '', FILTER_VALIDATE_FLOAT),
        'stock_actual' => filter_var($post['stock_actual'] ?? '', FILTER_VALIDATE_INT),
        'stock_minimo' => filter_var($post['stock_minimo'] ?? '', FILTER_VALIDATE_INT),
    ];

    if ($esNuevo && !preg_match('/^[A-Z0-9-]{1,20}$/', $d['codigo'])) {
        $errores[] = 'El código es obligatorio (letras, números y guion, máximo 20).';
    }
    if (mb_strlen($d['nombre']) < 3 || mb_strlen($d['nombre']) > 100) {
        $errores[] = 'El nombre debe tener entre 3 y 100 caracteres.';
    }
    if (mb_strlen($d['descripcion']) > 255) {
        $errores[] = 'La descripción no puede superar los 255 caracteres.';
    }
    if ($d['categoria'] === false || $d['categoria'] < 1) {
        $errores[] = 'Seleccione una categoría válida.';
    }
    if ($d['precio'] === false || $d['precio'] < 0 || $d['precio'] > 99999999) {
        $errores[] = 'El precio debe ser un número mayor o igual a 0.';
    }
    if ($d['stock_actual'] === false || $d['stock_actual'] < 0 || $d['stock_actual'] > 1000000) {
        $errores[] = 'El stock actual debe ser un entero entre 0 y 1.000.000.';
    }
    if ($d['stock_minimo'] === false || $d['stock_minimo'] < 0 || $d['stock_minimo'] > 1000000) {
        $errores[] = 'El stock mínimo debe ser un entero entre 0 y 1.000.000.';
    }

    return [$d, $errores];
}