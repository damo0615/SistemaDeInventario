<?php
    include '../../sesion_time.php';
    include '../../db/db.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    $user = $_SESSION['usern'];
    $result = mysqli_query($conn, "SELECT * FROM usuario INNER JOIN permisos ON usuario.id_permiso = permisos.id INNER JOIN bitacora ON usuario.id = bitacora.id_user WHERE username='$user' ORDER BY bitacora.fecha_accion DESC");
    if($comp = $result->fetch_object()){
                $name = $comp->username;
                $c_name = $comp->nombres;
                $rol = $comp->nombre;
                $email_c = $comp->email;
                $dni_c = $comp->dni;
                $accion = $comp->fecha_accion;
                $status = $comp->estatus;
                if ($status) {
                    $status = "Activo";
                }else{
                    $status = "Bloqueado";
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
</head>
<body class="bg-gray-100 min-h-screen">
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
                        <a href="../dashboard.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="../caja.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Caja
                        </a>
                        <a href="../inventario.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Inventario
                        </a>
                        <a href="../reporte.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                            <a href="../usuario.php">
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
                <a href="../dashboard.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Dashboard
                </a>
                <a href="../inventario.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Inventario
                </a>
                <a href="../reporte.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Reportes
                </a>
            </div>
        </div>
    </nav>
    <div class="pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
    <!-- Header -->
                <div class="md:flex md:items-center">
                    <div class="flex-1 border-b-4 border-primary-500">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Datos del Usuario
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">            
            <div class="profile-card bg-white rounded-xl overflow-hidden mb-8">
                <div class="relative bg-gradient-to-r from-gray-500 to-primary-600 h-32">
                    <a href="editar_user.php?edit=<?php echo $_SESSION['id']?>" class="absolute top-4 right-4 bg-black/40 hover:bg-white/30 text-white px-4 py-2 rounded-full transition flex items-center gap-2">
                        <i class="fas fa-pencil-alt"></i> Editar
                    </a>
                </div>
                
                <div class="pt-20 px-6 pb-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 id="user-name" class="text-2xl font-bold text-gray-800"><?php echo $c_name;?></h2>
                            <p id="user-username" class="text-gray-600"><?php echo $name;?></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="info-item bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">DNI</p>
                                    <p id="user-dni" class="font-medium"><?php echo $dni_c;?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p id="user-email" class="font-medium"><?php echo $email_c;?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Estado del usuario</p>
                                    <p id="user-phone" class="font-medium"><?php echo $status; ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Rol de Usuario</p>
                                    <p id="user-address" class="font-medium"><?php echo $rol;?></p>
                                </div>
                            </div>
                        </div>
                        <div class="info-item bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-orange-600">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Ultimo inicio de sesion</p>
                                    <p id="user-register-date" class="font-medium"><?php echo $accion; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="../../sesion/logout.php">
                        <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-x mr-2"></i>Cerrar Sesion
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
<script src="../../js/main.js"></script>
</body>
</html>