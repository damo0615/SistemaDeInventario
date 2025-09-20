<?php
    include '../../sesion_time.php';
    session_start();
        $user_id = $_SESSION['id'];
        if(!isset($user_id)) {
            header("location:../sesion/login.php");
        }
        include '../../db/db.php';

    // --- Resumen de Inventario en Tiempo Real ---
    $stockTotal = 0;
    $valorTotalInventario = 0;
    $sqlResumen = "SELECT SUM(stock) AS total_productos, SUM(stock * precio) AS valor_total_inventario FROM Producto";
    $resultadoResumen = $conn->query($sqlResumen);

    if ($resultadoResumen && $resultadoResumen->num_rows > 0) {
        $datosResumen = $resultadoResumen->fetch_assoc();
        $stockTotal = $datosResumen['total_productos'];
        $valorTotalInventario = $datosResumen['valor_total_inventario'];
    }

    // ---  Análisis de Rotación (Top 5 Productos más comprados) ---
    $topProductosComprados = [];
    $sqlTopComprados = "
        SELECT p.nombre, p.codigo, SUM(dc.cantidad) AS total_cantidad_comprada FROM detalles_compra dc JOIN Producto p ON dc.id_producto = p.id GROUP BY p.id ORDER BY total_cantidad_comprada DESC LIMIT 5";
    $resultadoTopComprados = $conn->query($sqlTopComprados);

    if ($resultadoTopComprados && $resultadoTopComprados->num_rows > 0) {
        while ($fila = $resultadoTopComprados->fetch_assoc()) {
            $topProductosComprados[] = $fila;
        }
    }

    // --- Historial de Movimientos (Últimas 10 compras) ---
    $historialMovimientos = [];
    $sqlHistorial = "
        SELECT dc.id_compra, c.fecha, p.nombre, dc.cantidad, dc.precio_unitario
        FROM detalles_compra dc
        JOIN Compra c ON dc.id_compra = c.id
        JOIN Producto p ON dc.id_producto = p.id
        ORDER BY c.fecha DESC
        LIMIT 10";
    $resultadoHistorial = $conn->query($sqlHistorial);

    if ($resultadoHistorial && $resultadoHistorial->num_rows > 0) {
        while ($fila = $resultadoHistorial->fetch_assoc()) {
            $historialMovimientos[] = $fila;
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo de Informes y Análisis</title>
    <link rel="stylesheet" href="css.css"> </head>
<body>

<div class="container">
    <h1>Informes y Análisis de Inventario</h1>

    <div class="card">
        <h2>Resumen General del Inventario</h2>
        <p><strong>Total de Productos en Stock:</strong> <?php echo number_format($stockTotal); ?></p>
        <p><strong>Valor Total del Inventario:</strong> $<?php echo number_format($valorTotalInventario, 2); ?></p>
    </div>

    <div class="card">
        <h2>Alertas de Stock Bajo</h2>
        <?php if (!empty($alertaStockBajo)): ?>
            <div class="alert alert-danger">
                <h3>¡Atención! Productos que necesitan reabastecimiento:</h3>
                <ul>
                    <?php foreach ($alertaStockBajo as $alerta): ?>
                        <li><?php echo htmlspecialchars($alerta['nombre']); ?> - Stock actual: <?php echo $alerta['stock']; ?> (Mínimo: <?php echo $alerta['inventario_minimo']; ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <p>No hay productos con stock bajo en este momento.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Análisis de Rotación (Top 5 más comprados)</h2>
        <?php if (!empty($topProductosComprados)): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Cantidad Comprada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($topProductosComprados as $producto): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['codigo']); ?></td>
                            <td><?php echo number_format($producto['total_cantidad_comprada']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay datos suficientes para mostrar el análisis de rotación.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Historial de Movimientos (Últimas 10 Compras)</h2>
        <?php if (!empty($historialMovimientos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Compra</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historialMovimientos as $movimiento): ?>
                        <tr>
                            <td><?php echo $movimiento['id_compra']; ?></td>
                            <td><?php echo $movimiento['fecha']; ?></td>
                            <td><?php echo htmlspecialchars($movimiento['nombre']); ?></td>
                            <td><?php echo number_format($movimiento['cantidad']); ?></td>
                            <td>$<?php echo number_format($movimiento['precio_unitario'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron movimientos de compra recientes.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>