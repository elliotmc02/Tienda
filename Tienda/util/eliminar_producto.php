<?php
require "../DATABASE/db_tienda.php";
$idProducto = $_GET["id"];

$sql = "SELECT imagen from productos where idProducto = '$idProducto'";
$resultado = $conexion->query($sql);
$ruta_imagen = "../" . $resultado->fetch_assoc()["imagen"];
echo $ruta_imagen;

// $res = mysqli_query($conexion, "select imagen from productos where idProducto = '$idProducto'");
// echo $res->fetch_assoc()["imagen"];

// borrar la imagen
if (file_exists($ruta_imagen)) {
    unlink($ruta_imagen);
    echo "File Successfully Delete.";
}
$sql = "DELETE FROM productos WHERE idProducto = '$idProducto'";
$conexion->query($sql);
header('location: ../');
