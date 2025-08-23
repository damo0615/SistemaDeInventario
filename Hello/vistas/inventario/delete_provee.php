<?php 
	include '../../sesion_time.php';
    include '../../db/db.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
	if(!empty($_POST['send'])){
		$password = $_POST['clave'];
		$id = $_POST['id'];
		$user=mysqli_query($conn, "SELECT password FROM usuario WHERE id='$user_id'");
		if ($comp = $user->fetch_object()) {
			$hash = $comp->password;
			if (password_verify($password, $hash)) {
				$prod = mysqli_query($conn, "SELECT * FROM producto WHERE id_proveedor='$id'");
				if($prod->num_rows <= 0){
					$query = mysqli_query($conn, "DELETE FROM proveedor WHERE id='$id'");
					if(!$query){
			        	die("Query Failed");
			        }else{
			        	$_SESSION['mensaje'] = "Proveedor eliminado con exito";
			        	header('location:/proveedores.php');
			        }
				}else{
					$_SESSION['mensaje'] = "Proveedor con productos registrados, verifique antes de eliminar";
					header('location:proveedores.php');
				}
			}else{
				$_SESSION['mensaje'] = "Contraseña equivocada, intente de nuevo";
				header('location:proveedores.php');
			}
		}
	}
?>