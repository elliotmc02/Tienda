<?php
require "../util/db_tienda.php";
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "eliminarProductoCesta") {
        $usuario = $_SESSION["usuario"];
        $sql = "select idCesta from cestas where usuario = '$usuario'";
        $idCesta = $conexion->query($sql)->fetch_assoc()["idCesta"];
        $idProducto = $_POST["idProducto"];

        $sql = "select cantidad from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'";
        $cantidadEnCesta = $conexion->query($sql)->fetch_assoc()["cantidad"];

        $sql = "update productos set cantidad = (cantidad + '$cantidadEnCesta') where idProducto = '$idProducto'";
        $conexion->query($sql);

        $sql = "delete from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'";
        $conexion->query($sql);

        $sql = "select precio from productos where idProducto = '$idProducto'";
        $precio = $conexion->query($sql)->fetch_assoc()["precio"];

        $sql = "update cestas set precioTotal = (precioTotal - ('$precio' * '$cantidadEnCesta')) where idCesta = '$idCesta'";
        $conexion->query($sql);

        if ($_POST["location"] == "index") {
            header("Location: ./");
        } else {
            header("Location: ./cesta.php");
        }
    }
}
?>