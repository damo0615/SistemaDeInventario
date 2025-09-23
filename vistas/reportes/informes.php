<?php
    include '../../sesion_time.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }
    include '../../db/db.php';
    $query = mysqli_query($conn, "SELECT compra.id,compra.fecha,compra.total,usuario.username,clientes.nombrec,clientes.codigo FROM compra INNER JOIN usuario ON compra.id_user = usuario.id INNER JOIN clientes ON compra.id_cliente = clientes.id");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <?php include '../../public/navbar.php'; ?>
    <!-- Contenido principal -->
    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                            Movimiento de Ventas
                        </h2>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Acciones realizadas
                        </p>
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
                Movimiento de Ventas
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Acciones realizadas
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            fecha
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            cliente
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            codigo cliente
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            total
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            usuario
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Tasa
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Cuentas
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            
                        </th>
                    </tr>
                </thead>
                <tbody id="inventory-table-body" class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 custom-scrollbar">
                    <?php
                        if($query->num_rows > 0){
                            while($prod = $query->fetch_assoc()){
                                $id = $prod['id'];
                                $fecha = $prod['fecha'];
                                $accion = $prod['total'];
                                $user = $prod['username'];
                                $cliente = $prod['nombrec'];
                                $codigo = $prod['codigo'];
                            ?>  
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center dark:bg-primary-200">
                                        <i class="fas fa-box text-primary-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white"><?php echo $fecha; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo $cliente; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo $codigo; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo $accion; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo $user; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">180,00</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">Entregado</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">Cerrada</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <form action="detalles.php" method="POST">
                                        <div class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <i class="fas fa-plus mr-2"></i>
                                            <input type="submit" name="ver" value="VER DETALLES">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            
                                        </div>
                                    </form>
                                </div>
                            </td>
                            </tbody>
                                        <?php
                                    }
                                }
                            ?>
            </table>
        </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 dark:bg-gray-700 dark:border-gray-700">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="../../js/main.js"></script>
</body>
<?php include '../../public/footer.html'; ?>
</html>