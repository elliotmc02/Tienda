<?php
require "../DATABASE/db_tienda.php";
$usuario = $_GET["id"];
$sql = "DELETE FROM usuarios WHERE usuario = '$usuario'";
$conexion->query($sql);
header("location: ../administrar_usuarios.php");
?>