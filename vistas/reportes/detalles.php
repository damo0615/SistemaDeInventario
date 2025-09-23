<?php
    include '../../sesion_time.php';
    include '../../db/db.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }
    $item  = $_POST['id'];
    $query = mysqli_query($conn, "SELECT * FROM compra INNER JOIN usuario  ON compra.id_user = usuario.id INNER JOIN clientes ON compra.id_cliente = clientes.id WHERE compra.id = '$item'");
    $query2 = mysqli_query($conn, "SELECT * FROM detalles_compra INNER JOIN compra ON detalles_compra.id_compra = compra.id INNER JOIN producto ON detalles_compra.id_producto = producto.id  WHERE detalles_compra.id = '$item'");
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
    		<?php
		        if($query->num_rows > 0){
		            while($prod = $query->fetch_assoc()){
	                    $fecha = $prod['fecha'];
	                    $cliente = $prod['nombrec'];
	                    $total = $prod['total'];
		            ?>
		            <h3 class="flex-2 text-lg leading-6 font-medium text-gray-900 dark:text-white">
						INVERSIONES MERCAMIX MV>
		            </h3>
		            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
		            	Cliente: <?php echo $cliente; ?>
		            </p>
		            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
		            	Fecha: <?php echo $fecha; ?>
		            </p>

		            <?php } } ?>           
        </div>
        <div class="overflow-x-auto">
        	<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700"> 
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Producto
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Precio unitario
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Cantidad
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 custom-scrollbar">
        	<?php
		        if($query2->num_rows > 0){
		            while($prod = $query2->fetch_assoc()){
	                    $canntidad = $prod['cantidad'];
	                    $producto = $prod['nombre'];
	                    $precio = $prod['precio_unitario'];
	                    $total = $prod['total'];
            ?>	
            	<td class="px-6 py-4 whitespace-nowrap">
            		<div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center dark:bg-primary-200">
                            <i class="fas fa-box text-primary-600"></i>
                        </div>
                        <div class="ml-4">
	                	<div class="text-sm font-medium text-gray-900 dark:text-white"><?php echo $producto; ?></div>
	                </div>
                    </div>
	            </td>
	            <td class="px-6 py-4 whitespace-nowrap">
	                <div class="text-sm text-gray-900 dark:text-white"><?php echo $precio; ?></div>
	            </td>
	            <td class="px-6 py-4 whitespace-nowrap">
	                <div class="text-sm text-gray-900 dark:text-white"><?php echo $canntidad; ?></div>
	            </td>
	            <td class="px-6 py-4 whitespace-nowrap">
	                <div class="text-sm text-gray-900 dark:text-white"><?php echo $total; ?></div>
	            </td>
            <?php } } ?>
            	</tbody>
           	</table>
        </div>
    </div>
    <table class=" divide-y divide-gray-200 dark:divide-gray-700 float-right mr-40">
           		<thead >
	           		<tr >
	           			<th scope="col" class="px-6 py-3 text-left text-xl font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Total: 	</th>
	           			<th scope="col" class="px-6 py-3 text-left text-xl font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300"><?php echo $total; ?></th>
	           		</tr>
           		</thead>
           	</table>
    </div>
    </div>
    <script src="../../js/main.js"></script>
</body>
<?php include '../../public/footer.html'; ?>
</html>