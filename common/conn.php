<?php 
$server   = 'localhost';
$username = 'root';
$password = '';
$database = 'multiplication_table';

$conn = mysqli_connect($server, $username, $password, $database);
if (mysqli_connect_errno()) {
	$error_msg = 'Failed to connect to the database.';
	exit();
}
?>