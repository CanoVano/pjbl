<?php
    session_start();
    $_SESSION['username'];
    if($_SESSION['login']== false){
        header ('location: login.php');
    }
?>