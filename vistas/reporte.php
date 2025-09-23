<?php
    include '../sesion_time.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }
    include '../db/db.php';

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
        SELECT c.id_user,c.total,c.fecha,u.nombres FROM compra c JOIN usuario u ON c.id_user = u.id ORDER BY c.fecha DESC LIMIT 10";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <?php include '../public/navbar.php'; ?>
    <!-- Contenido principal -->
    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                            Informes y Analisis
                        </h2>
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="reportes/Informes.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i> Informes
                            </button>
                        </a>
                        <?php
                            if($_SESSION['rol'] == 1){ ?>
                                <a href="reportes/bitacora.php">
                                    <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-plus mr-2"></i> Vista de la Bitacora
                                    </button>
                                </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabla de productos -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6" >
    <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Ultimas ventas realizadas
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                10 ventas realizadas por usuarios
            </p>
        </div>
        <div class="overflow-x-auto">
            <?php if (!empty($topProductosComprados)): ?>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Producto
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Codigo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Cantidad Comprada
                        </th>
                    </tr>
                </thead>
                <tbody id="inventory-table-body" class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 custom-scrollbar">
                    <?php $i = 1; foreach ($topProductosComprados as $producto): ?> 
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo $i++; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($producto['nombre']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($producto['codigo']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($producto['total_cantidad_comprada']); ?></div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No hay datos suficientes para mostrar el análisis de rotación.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
        <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Productos mas vendidos en el mes
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Top 5 productos con mas ventas
            </p>
        </div>
        <div class="overflow-x-auto">
            <?php if (!empty($topProductosComprados)): ?>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Usuario
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Fecha
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Total
                        </th>                        
                    </tr>
                </thead>
                <tbody id="inventory-table-body" class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 custom-scrollbar">
                    <?php foreach ($historialMovimientos as $movimiento): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo $movimiento['nombres']; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo $movimiento['fecha']; ?></div>
                                </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($movimiento['total']); ?></div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No hay datos suficientes para mostrar el análisis de rotación.</p>
            <?php endif; ?>
        </div>
    </div>
    </div>
    <script src="../js/main.js"></script>
</body>
    <?php include '../public/footer.html'; ?>
</html>