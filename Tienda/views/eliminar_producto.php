<?php
require "../util/db_tienda.php";
$idProducto = $_POST["eliminarProducto"];

$sql = "DELETE FROM productoscestas WHERE idProducto = '$idProducto'";
$conexion->query($sql);

$sql = "SELECT imagen from productos where idProducto = '$idProducto'";
$ruta_imagen = $conexion->query($sql)->fetch_assoc()["imagen"];

// $res = mysqli_query($conexion, "select imagen from productos where idProducto = '$idProducto'");
// echo $res->fetch_assoc()["imagen"];

// borrar la imagen
if (file_exists($ruta_imagen)) {
    unlink($ruta_imagen);
}
$sql = "DELETE FROM productos WHERE idProducto = '$idProducto'";
$conexion->query($sql);
header('location: ./');
