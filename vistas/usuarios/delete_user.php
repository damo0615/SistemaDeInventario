<?php 
  	session_start();
 	include '../../db/db.php';
 	include '../php_inyec.php';
 	$userid = $_SESSION['id'];
 	$query = mysqli_query($conn,"SELECT id,password FROM usuario WHERE id='$userid'");
 	if(isset($_POST['send'])){
    	if ($comp = $query->fetch_object()) {
    		$hash = $comp->password;
			$clave  = limpiar_cadena($_POST['clave']);
			$id = limpiar_cadena($_POST['id']);
			if (password_verify($clave,$hash)) {
				$query = mysqli_query($conn,"SELECT * FROM bitacora WHERE id_user='$id'");
				if($comp = $query->fetch_object()){
					$_SESSION['mensaje_error'] = "No puede borrar el usuario porque hay registros asociadas a este";
					header('location:alluser.php');
				}else{
					$borrar = mysqli_query($conn,"DELETE FROM user WHERE id='$id'");
					if(!$borrar){
		                die("Query Failed");
		            }
		            $_SESSION['mensaje_exito'] = "usuario eliminado con exito";
		            header('location:alluser.php');
				}
			}else{
				$_SESSION['mensaje_error'] = "La contraseña no coincide";
				header('location:alluser.php');
			}
		}
 	}else{
			header('location:alluser.php');
	}
?>