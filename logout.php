<?php 
session_start();
unset($_SESSION['user']);
unset($_SESSION['lang']);
header('Location: ./index.php');
?>