<?php
require "../util/db_tienda.php";
$usuario = $_POST["action"];
$sql = "DELETE FROM cestas WHERE usuario = '$usuario'";
$conexion->query($sql);
$sql = "DELETE FROM usuarios WHERE usuario = '$usuario'";
$conexion->query($sql);
header("location: administrar_usuarios.php");
