<?php  
    include '..\..\db\db.php';

    $query = mysqli_query($conn,"SELECT codigo FROM producto");

    if (!isset($_SESSION['lista_venta'])) {
        $_SESSION['lista_venta'] = [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto'])){
        $codigo_producto = $conn->real_escape_string($_POST['codigo_producto']);
        $cantidad_producto = $conn->real_escape_string($_POST['cantidad_producto']);
        print_r($_SESSION['lista_venta']);
        echo "</br>";
        $sql = "SELECT id, nombre, precio, codigo FROM producto WHERE codigo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $codigo_producto);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $producto = $resultado->fetch_assoc();  
            
            $existe_en_lista = false;
            foreach ($_SESSION['lista_venta'] as & $item) {
            if ($item['id'] === $producto['id']) {
                $item['cantidad'] += $cantidad_producto;
                $existe_en_lista = true;
                break;
            }
        }   
            unset($item);
            
            if (!$existe_en_lista) {
                $producto['cantidad'] = $cantidad_producto;
                $_SESSION['lista_venta'][] = $producto;
            }
        }else {
            $mensaje = "Producto con código '$codigo_producto' no encontrado.";
        }
    }
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../style.css">
    
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <nav class="bg-white dark:bg-gray-800 shadow-md fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-boxes text-primary-500 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Inventory Pro</span>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="dashboard.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="caja.php" class="border-primary-500 text-gray-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Caja
                        </a>
                        <a href="inventario.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Inventario
                        </a>
                        <a href="reporte.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Reportes
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <button id="mobile-menu-button" type="button" class="md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none rounded-lg text-sm p-2.5 mr-1">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="ml-3 relative">
                        <div>
                            <a href="usuario.php">
                                <button type="button" class="bg-white dark:bg-gray-800 rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold">AD</div>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Men� m�vil -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="dashboard.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Dashboard
                </a>
                <a href="caja.php" class="bg-primary-50 border-primary-500 text-primary-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:bg-gray-700 dark:text-primary-400">
                    Caja
                </a>
                <a href="inventario.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Inventario
                </a>
                <a href="reporte.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Reportes
                </a>
            </div>
        </div>
    </nav>
    <div class="pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
    <div class="flex-1 min-w-0">
        <h1 class="text- 2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">Gestion de Stock</h1>
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo (strpos($mensaje, 'éxito') !== false) ? 'success' : 'error'; ?>">
                <?php echo $mensaje; ?>
                <span class="close-btn">&times;</span>
            </div>
        <?php endif; ?>

        <form id="add-form" action="teststock2.php" method="POST">
            <input type="text" name="codigo_producto" placeholder="Código de Producto" list="codigos" class="mt-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
            <datalist id="codigos">
                    <?php 
                        if($query->num_rows > 0){
                            while($prod = $query->fetch_assoc()){
                                $codigo = $prod['codigo'];
                    ?>
                        <option value="<?php echo $codigo;?>"></option>
                    <?php
                            }
                        }
                    ?>
            </datalist>
            <input type="number" name="cantidad_producto" placeholder="Cantidad" min="1" class="mt-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
            <button type="submit" name="agregar_producto" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Agregar</button>
        </form>
   </div>
    </div>
        <form id="main-form" action="teststock.php" method="POST">
            <div class="form-group">
                <label for="tipo_movimiento">Tipo de Movimiento:</label>
                <select id="tipo_movimiento" name="tipo_movimiento" required>
                    <option value="entrada">Carga (Entrada)</option>
                    <option value="salida">Descarga (Salida)</option>
                </select>
            </div>
        <div class="lista-productos bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <div class="w-full">
                <ul>
                    <?php
                    $total_venta = 0;
                    foreach ($_SESSION['lista_venta'] as $item):
                        $subtotal = $item['precio'] * $item['cantidad'];
                        $total_venta += $subtotal;
                    ?>
                        <li>
                            <span>
                                <?php echo htmlspecialchars($item['nombre']); ?> (<?php echo htmlspecialchars($item['codigo']); ?>)
                                - Cantidad: <?php echo htmlspecialchars($item['cantidad']); ?> x $<?php echo number_format($item['precio'], 2); ?>
                            </span>
                            <span>
                                $<?php echo number_format($subtotal, 2); ?>
                                <form action="caja.php" method="POST" style="display:inline-block; margin-left: 10px;">
                                    <input type="hidden" name="id_producto_eliminar" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="eliminar_producto" class="eliminar-btn">X</button>
                                </form>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if (!empty($_SESSION['lista_venta'])): ?>
            <div class="action-buttons">
                <form action="caja.php" method="POST">
                    <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-400 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" name="vaciar_lista">Vaciar Lista</button>
                </form>
                <form action="caja.php" method="POST">
                    <button type="submit" name="procesar_venta" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Procesar Movimiento</button>
                </form>
            </div>
        <?php endif; ?>
        </form>
        </div>
        </div>
    </div>
    </div>
    </div>
    </div>
</body>
<script type="text/javascript" src="../../js/main.js"></script>
<script>
        document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.querySelector('.close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    closeBtn.parentNode.style.display = 'none';
                });
            }
        });
</script>
</html>