<?php
require_once 'auth.php';
require_once 'conexion.php';
require_once 'funciones.php';

$titulo = 'Historial de movimientos';
$sql = 'SELECT m.fecha_hora, m.tipo, m.cantidad, m.observacion,
               p.codigo, p.nombre AS producto, u.nombre AS usuario
        FROM movimientos m
        INNER JOIN productos p ON p.id_producto = m.id_producto
        INNER JOIN usuarios u ON u.id_usuario = m.id_usuario
        ORDER BY m.id_movimiento DESC
        LIMIT 100';
$movimientos = $pdo->query($sql)->fetchAll();

require_once 'encabezado.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Historial de movimientos</h1>
    <a class="btn btn-primary" href="nuevo_movimiento.php">Registrar movimiento</a>
</div>
<div class="table-responsive">
    <table class="table table-hover align-middle bg-white">
        <thead>
            <tr>
                <th>Fecha</th><th>Producto</th><th>Tipo</th><th>Cantidad</th>
                <th>Observación</th><th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$movimientos): ?>
                <tr><td colspan="6" class="text-center text-muted">Aún no hay movimientos.</td></tr>
            <?php endif; ?>
            <?php foreach ($movimientos as $m): ?>
                <tr>
                    <td><?= e(date('d-m-Y H:i', strtotime($m['fecha_hora']))) ?></td>
                    <td><?= e($m['codigo'] . ' - ' . $m['producto']) ?></td>
                    <td>
                        <?php if ($m['tipo'] === 'entrada'): ?>
                            <span class="badge text-bg-success">Entrada</span>
                        <?php else: ?>
                            <span class="badge text-bg-danger">Salida</span>
                        <?php endif; ?>
                    </td>
                    <td><?= (int) $m['cantidad'] ?></td>
                    <td><?= e($m['observacion']) ?></td>
                    <td><?= e($m['usuario']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once 'pie.php'; ?>