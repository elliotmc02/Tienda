<?php
$_server = "localhost";
$_username = "root";
$_password = "medac";
$_database = "db_tienda";

$conexion = new mysqli($_server, $_username, $_password, $_database) or die("Error de conexion");
?>