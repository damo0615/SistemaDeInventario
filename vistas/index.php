<?php
    include 'db/db.php';
    include '../public/footer.html';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }else{
        header("location:vistas/dashboard.php");
    }

?>