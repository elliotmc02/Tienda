<?php
require "../util/db_tienda.php";
session_start();
$usuario = $_SESSION["usuario"];
$correcto = false;
if ($_SESSION["rol"] != "invitado") {
    if ($_POST["action"] == "anadir") {
        $sql = "select idCesta from cestas where usuario = '$usuario'";
        $idCesta = $conexion->query($sql)->fetch_assoc()["idCesta"];
        $idProducto = $_POST["idProducto"];
        $sql = "select cantidad from productos where idProducto = '$idProducto'";
        $cantidadProducto = $conexion->query($sql)->fetch_assoc()["cantidad"];
        if ($cantidadProducto > 0) {
            if (isset($_POST["cantidad"])) {
                $cantidad = $_POST["cantidad"];
                $sql = "INSERT INTO productoscestas values ('$idProducto', '$idCesta', '$cantidad')";
                $duplicado = mysqli_query($conexion, "select * from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'");
                if (mysqli_num_rows($duplicado) == 0) {
                    $conexion->query($sql);
                    echo "inserto por primera vez aqui";
                    $correcto = true;
                } else {
                    $sql = "select cantidad from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'";
                    $cantidadEnCesta = $conexion->query($sql)->fetch_assoc()["cantidad"];
                    if ($cantidad + $cantidadEnCesta <= 10) {
                        $cantidadActual = mysqli_query($conexion, "select cantidad from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'")->fetch_assoc()["cantidad"];
                        $conexion->query("update productoscestas set cantidad = ('$cantidadActual' + '$cantidad') where idProducto = '$idProducto' and idCesta = '$idCesta'");
                        $correcto = true;
                    }
                }
                if ($correcto) {
                    $sql = "update productos set cantidad = (cantidad - '$cantidad') where idProducto = '$idProducto'";
                    $conexion->query($sql);
                    // Añadir precio a la cesta
                    $sql = "select precio from productos where idProducto = '$idProducto'";
                    $precio = $conexion->query($sql)->fetch_assoc()["precio"];
                    $sql = "update cestas set precioTotal = (precioTotal + ('$precio' * '$cantidad')) where idCesta = '$idCesta'";
                    $conexion->query($sql);
                }
            }
        }
    }
}

header("Location: ./");
