  <?php 
  	session_start();
 	include '../../db/db.php';
 	include '../php_inyec.php';
 	$userid = $_SESSION['id'];
 	$query = "SELECT * FROM producto WHERE id='$userid'";
 	if(isset($_POST['send'])){
    	if ($comp = $query->fetch_object()) {
    		$hash = $comp->password;
			$clave = limpiar_cadena($_POST['clave']);
			$id = limpiar_cadena($_POST['id']);
			if (password_verify($clave,$hash)) {
				$query = mysqli_query($conn,"SELECT * FROM producto WHERE id_tag='$id'");
				if($comp = $query->fetch_object()){
					echo "No se puede borrar la etiqueta porque hay productos asociadas a esta";
				}else{
					$borrar = mysqli_query($conn,"DELETE FROM tag WHERE id='$id'");
					if(!$borrar){
		                die("Query Failed");
		            }
		            header('location:tags.php');
				}
			}
		}
 	}else{
			header('location:tags.php');
	}
?>