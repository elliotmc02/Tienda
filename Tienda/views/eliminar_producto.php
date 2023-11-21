<?php
require "../util/db_tienda.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "eliminarProducto") {
        $idProducto = $_POST["idProducto"];
        $sql = "DELETE FROM productoscestas WHERE idProducto = '$idProducto'";
        $conexion->query($sql);

        $sql = "SELECT imagen from productos where idProducto = '$idProducto'";
        $ruta_imagen = $conexion->query($sql)->fetch_assoc()["imagen"];

        // borrar la imagen
        if (file_exists($ruta_imagen)) {
            unlink($ruta_imagen);
        }
        $sql = "DELETE FROM productos WHERE idProducto = '$idProducto'";
        $conexion->query($sql);
        header('location: administrar_productos.php');
    }
}
?>