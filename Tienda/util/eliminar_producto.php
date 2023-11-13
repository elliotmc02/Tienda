<?php
require "../DATABASE/db_tienda.php";
$idProducto = $_GET["id"];
$sql = "DELETE FROM productos WHERE idProducto = '$idProducto'";
$conexion->query($sql);
header('location: ../');
?>