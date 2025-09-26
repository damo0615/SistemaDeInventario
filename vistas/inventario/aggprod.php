<?php
    include '../../sesion_time.php';
    include '..\..\db\db.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    $prove = mysqli_query($conn, "SELECT id,nombrep FROM proveedor");
    $tag = mysqli_query($conn, "SELECT id,nombres FROM tag");

    if(!empty($_POST['send'])){
        $name = $_POST['product-name'];
        $categoria = $_POST['product-category'];
        $precio = $_POST['product-price'];
        $codigo = $_POST['product-codigo'];
        $prove = $_POST['product-prov'];
        $descrip = $_POST['product-descrip'];
        $codi = mysqli_query($conn, "SELECT * FROM producto WHERE codigo='$codigo'");
        if($codi -> num_rows > 0){
           $_SESSION['mensaje_error'] = 'El codigo de producto ya existe, intente con otro';
           header('location:../inventario.php');
        }else{
            try {
            mysqli_begin_transaction($conn);
            $query = mysqli_query($conn, "INSERT INTO producto (nombre, codigo, precio, id_tag, id_proveedor, descripcion) VALUES ('$name','$codigo','$precio','$categoria','$prove','$descrip')");
            if(!$query){
                die("Query Failed");
            }
            $id_producto = mysqli_insert_id($conn);
            $inv = mysqli_query($conn, "INSERT INTO inventario (id_producto,cantidad) VALUES ('$id_producto',0)");
            if(!$inv){
                die("Query Failed");
            }
            mysqli_commit($conn);   
            } catch (Exception $e) {
                mysqli_rollback($conn);
                echo "Error: " . $e->getMessage();
            }
            $id_user = $_SESSION['id'];
            $accion = 'El usuario '.$_SESSION['usern'].' ha registrado un nuevo producto';
            $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
            if(!$bitacora){
                die("Query Failed");
            }
            $_SESSION['mensaje_exito'] = 'Producto Guardado con exito!';
            header('location:../inventario.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../style.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <?php include '../../public/navbar.php'; ?>
    <div id="add-product-modal" class="inset-0 ">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-800">
                <div class="mb-6"></div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Agregar Nuevo Producto
                        </h3>
                    </div>
                    <div class="mt-2">
                        <form action="aggprod.php" method="POST">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <label for="product-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Producto</label>
                                <input type="text" maxlength="25" name="product-name" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                                <select id="product-category" name="product-category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                    <?php
                                        if($tag->num_rows > 0){
                                            while($tags = $tag->fetch_assoc()){
                                                $ntag = $tags['nombres'];
                                                $itag = $tags['id'];
                                    ?>
                                    <option value="<?php echo $itag;?>"><?php  echo $ntag; ?></option>
                                    <?php  }}
                                    else{
                                        echo "<script>window.alert('No existen etiquetas o proveedore, antes de agregar un producto, agregue al menos una etiqueta o un proveedor');window.location.href='../inventario.php'</script>";
                                    } ?>
                                </select>

                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Codigo</label>
                                <input type="text" maxlength="25" name="product-codigo" id="product-codigo" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="text" maxlength="25" name="product-price" id="product-price" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="0.00" required>
                                    
                                </div>
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-prov" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Proveedor</label>
                                <select id="product-prov" name="product-prov" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                    <?php
                                        if($prove->num_rows > 0){
                                            while($provee = $prove->fetch_assoc()){
                                                $nprove = $provee['nombrep'];
                                                $iprove = $provee['id'];
                                    ?>
                                    <option value="<?php echo $iprove;?>"><?php  echo $nprove; ?></option>
                                    <?php  }}else{
                                        echo "<script>window.alert('No existen etiquetas o proveedore, antes de agregar un producto, agregue al menos una etiqueta o un proveedor');window.location.href='../inventario.php'</script>";
                                    } ?>
                                </select>
                            </div>
                            
                            <div class="col-span-6">
                                <label for="product-descrip" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripcion</label>
                                <textarea id="product-descrip" name="product-descrip" rows="3" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="Maximo 255 caracteres" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                    <input type="submit" name="send" id="save-product" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Guardar Producto">
                    <a href="../inventario.php">
                        <button type="button" id="cancel-add" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-700">                        Cancelar
                    </button>
                    </a>
                </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    
    
    <script src="../../js/main.js"></script>
</body>
</html>