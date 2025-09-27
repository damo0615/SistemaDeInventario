<?php 
  	session_start();
 	include '../../../db/db.php';
 	include '../../php_inyec.php';
 	$userid = $_SESSION['id'];
 	$query = mysqli_query($conn,"SELECT id,password FROM usuario WHERE id='$userid'");
 	if(isset($_POST['send'])){
    	if ($comp = $query->fetch_object()) {
    		$hash = $comp->password;
			$clave  = limpiar_cadena($_POST['clave']);
			$id = limpiar_cadena($_POST['id']);
			if (password_verify($clave,$hash)) {
				$query = mysqli_query($conn,"SELECT * FROM producto WHERE id_tag='$id'");
				if($comp = $query->fetch_object()){
					$_SESSION['mensaje_error'] = "No puede borrar la etiqueta porque hay registros asociadas a esta";
					header('location:../tags.php');
				}else{
					$borrar = mysqli_query($conn,"DELETE FROM tag WHERE id='$id'");
					if(!$borrar){
		                die("Query Failed");
		            }
		            $_SESSION['mensaje_exito'] = "Etiqueta borrada con exito";
		            header('location:../tags.php');
				}
			}else{
				$_SESSION['mensaje_error'] = "La contraseña no coincide";
				header('location:../tags.php');
			}
		}
 	}else{
			header('location:../tags.php');
	}
?>