<?php
	include '../../db/db.php';
	include '../php_inyec.php';
	if (isset($_GET['edit'])) {
		$id = limpiar_cadena($_GET['edit']);
		$status = limpiar_cadena($_GET['status']);
		if ($status == 1) {
			$newstatus = 0;
		}else{
			$newstatus = 1;
		}
		$query = mysqli_query($conn,"UPDATE usuario SET estatus='$newstatus' WHERE id='$id'");
		if(!$query){
        die("Query Failed");
        }
        header('location:alluser.php');


	}

?>