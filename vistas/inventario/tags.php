<?php
    session_start();
    include '../../sesion_time.php';
    include '../../db/db.php';
    include '../php_inyec.php';
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    $query = mysqli_query($conn, "SELECT nombres,id FROM tag ");
    if(isset($_POST['send'])){
        $nombre = limpiar_cadena($_POST['name']);
        if (!isset($_SESSION['mensaje_sql'])) {
            $name = mysqli_query($conn, "SELECT * FROM tag WHERE nombres='$nombre'");
            if($name -> num_rows > 0){
               $_SESSION['mensaje_error'] = 'El nombre de la etiqueta ya exite, use otro';
            }else {
                $query1 = mysqli_query($conn, "INSERT INTO tag (nombres) VALUES ('$nombre')");
                if(!$query1){
                    die("Query Failed");
                }
                $id_user = $_SESSION['id'];
                $accion = 'El usuario '.$_SESSION['usern'].' ha registrado una nueva etiqueta';
                $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
                if(!$bitacora){
                    die("Query Failed");
                }
                $_SESSION['mensaje_exito'] = 'Etiqueta agregada con exito';
                header('location:tags.php');
                }
            }
            if (isset($_POST['update'])) {
                $id = $_POST['id'];
                $nombre = $_POST['name'];
                $query = mysqli_query($conn, "UPDATE tag set nombres='$nombre' WHERE id='$id'");
                if(!$query){
                    die("Query Failed");
                }
                $id_user = $_SESSION['id'];
                $accion = 'El usuario '.$_SESSION['usern'].' ha actualizado una etiqueta';
                $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
                if(!$bitacora){
                    die("Query Failed");
                }
                $query = mysqli_query($conn, "SELECT nombres,id FROM tag ");
                $_SESSION['mensaje_exito'] = 'Etiqueta editada con exito';
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
    <!-- Contenido principal -->
    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <?php
                            if($_SESSION['rol'] != 3){ ?>
                                <form action="tags.php" method="POST">
                            <input type="text" maxlength="25" name="name" class="border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white-500 hover:bg-white-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white-500" required>
                            <input type="submit" name="send" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" value="Agregar">
                        </form><?php
                                }
                        ?>
                        
                    </div>
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        <a href="../inventario.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i> Ver Productos
                            </button>
                        </a>
                        <a href="proveedores.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i> Ver Proveedores
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabla de Etiquetas -->
    <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Listado Total de Etiquetas
            </h3>
            <?php if (isset($_SESSION['mensaje_exito'])): ?>
                <div class="message success">
                    <?php echo htmlspecialchars($_SESSION['mensaje_exito']); ?>
                    <span class="close-btn" data-form="limpiar_exito">&times;</span>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['mensaje_error'])): ?>
                <div class="message error">
                    <?php echo htmlspecialchars($_SESSION['mensaje_error']); ?>
                    <span class="close-btn" data-form="limpiar_error">&times;</span>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['mensaje_sql'])): ?>
                <div class="message error">
                    <?php echo htmlspecialchars($_SESSION['mensaje_sql']); ?>
                    <span class="close-btn" data-form="limpiar_error">&times;</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <?php
                if($query->num_rows > 0){
                    while($tag = $query->fetch_assoc()){
                        $nombre = $tag['nombres'];
                        $id = $tag['id'];
            ?>  
            <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                Nombre:
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    <?php echo $nombre;?>
                                </div>
                            </dd>
                            <button onclick="window.mydialog<?php echo $id;?>.showModal()">
                                <i class="fas fa-edit text-primary-500"></i>
                            </button>
                            <button onclick="window.mydialog0<?php echo $id;?>.showModal()">
                                <i class="fas fa-trash-alt text-red-500"></i>
                            </button>
                                <dialog id="mydialog0<?php echo $id; ?>">
                                    <p>Introduzca la contraseña para eliminar el item</p>
                                    <form action="delete/delete_tag.php" method="POST">
                                        <input type="password" name="clave">
                                        <input type="hidden" name="id" value="<?php echo $tag['id']?>">
                                        <input type="submit" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
                                    </form>
                                    <button onclick='window.mydialog0<?php echo $id; ?>.close();' class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">Cerrar modal</button>
                                </dialog>
                        </div>
                    </div>
                </div>
            </div>
            <dialog id="mydialog<?php echo $id; ?>">
                <p>Agregue el nuevo nombre de la etiqueta</p>
                <form action="tags.php" method="POST">
                    <input type="text" maxlength="25" name="name">
                    <input type="hidden" name="id" value="<?php echo $id;?>">
                    <input type="submit" name="update" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
                </form>
                <button onclick='window.mydialog<?php echo $id; ?>.close();'>Cerrar modal</button>
            </dialog>
            <?php
                    }
                }else{
                    echo '<div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                Nombre:
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    Agrege Una Etiqueta
                                </div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>';
                }
            ?>                                        
        </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 dark:bg-gray-700 dark:border-gray-700">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>

                </div>
            </div>
        </div>
    </div>
    <script src="../../js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.querySelector('.close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    <?php 
                        unset($_SESSION['mensaje_exito']);
                        unset($_SESSION['mensaje_error']);
                        unset($_SESSION['mensaje_sql']);
                    ?>
                });
            }
        });
    </script>
</body>
</html>