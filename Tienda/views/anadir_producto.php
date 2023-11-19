<?php
require "../util/db_tienda.php";
session_start();
$usuario = $_SESSION["usuario"];
if ($usuario == "invitado") {
    header("Location: ./");
} else {
    $sql = "select idCesta from cestas where usuario = '$usuario'";
    $idCesta = $conexion->query($sql)->fetch_assoc()["idCesta"];
    $idProducto = $_POST["anadirProducto"];
    $cantidad = $_POST["cantidad"];
    if ($cantidad != "") {
        $sql = "INSERT INTO productoscestas values ('$idProducto', '$idCesta', '$cantidad')";
        $duplicado = mysqli_query($conexion, "select * from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'");
        if (mysqli_num_rows($duplicado) == 0) {
            $conexion->query($sql);
        } else {
            $cantidadActual = mysqli_query($conexion, "select cantidad from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'")->fetch_assoc()["cantidad"];
            $conexion->query("update productoscestas set cantidad = ('$cantidadActual' + '$cantidad') where idProducto = '$idProducto' and idCesta = '$idCesta'");
        }
        $sql = "update productos set cantidad = (cantidad - '$cantidad') where idProducto = '$idProducto'";
        $conexion->query($sql);

        // Añadir precio a la cesta
        $sql = "select precio from productos where idProducto = '$idProducto'";
        $precio = $conexion->query($sql)->fetch_assoc()["precio"];
        $sql = "update cestas set precioTotal = (precioTotal + ('$precio' * '$cantidad')) where idCesta = '$idCesta'";
        $conexion->query($sql);

        header("Location: ./");
    }
}
