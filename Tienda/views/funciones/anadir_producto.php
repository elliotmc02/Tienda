<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "anadir") {
        $correcto = false;
        if ($rol != "invitado") {
            // seleccionamos idCesta
            $sql = "SELECT idCesta from cestas where usuario = '$usuario'";
            $idCesta = $conexion->query($sql)->fetch_assoc()["idCesta"];
            // selccionamos idProducto
            $idProducto = $_POST["idProducto"];
            // seleccionamos cantidad de ese producto
            $sql = "SELECT cantidad from productos where idProducto = '$idProducto'";
            $cantidadProducto = $conexion->query($sql)->fetch_assoc()["cantidad"];
            // si hay cantidad suficiente
            if ($cantidadProducto > 0) {
                if (isset($_POST["cantidad"])) {
                    $cantidad = $_POST["cantidad"];
                    // insertamos en productoscestas
                    $sql = "INSERT INTO productoscestas values ('$idProducto', '$idCesta', '$cantidad')";
                    // comprobamos si ya existe ese producto en la cesta
                    $duplicado = "select * from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'";
                    if ($conexion->query($duplicado)->num_rows == 0) {
                        $conexion->query($sql);
                        $correcto = true;
                    } else {
                        // si ya existe, sumamos la cantidad
                        $sql = "SELECT cantidad from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'";
                        $cantidadEnCesta = $conexion->query($sql)->fetch_assoc()["cantidad"];
                        // si no se pasa de 10
                        if ($cantidad + $cantidadEnCesta <= 10) {
                            $sql = "SELECT cantidad from productoscestas where idProducto = '$idProducto' and idCesta = '$idCesta'";
                            $cantidadActual = $conexion->query($sql)->fetch_assoc()["cantidad"];
                            // sumamos la cantidad
                            $conexion->query("UPDATE productoscestas set cantidad = ('$cantidadActual' + '$cantidad') where idProducto = '$idProducto' and idCesta = '$idCesta'");
                            $correcto = true;
                        } else {
                            $mensaje = "No puedes añadir más de 10 unidades de un mismo producto";
                        }
                    }
                    if ($correcto) {
                        // restamos la cantidad del producto menos la que ha seleccionado el usuario
                        $sql = "update productos set cantidad = (cantidad - '$cantidad') where idProducto = '$idProducto'";
                        $conexion->query($sql);
                        // Añadir precio a la cesta
                        $sql = "select precio from productos where idProducto = '$idProducto'";
                        $precio = $conexion->query($sql)->fetch_assoc()["precio"];
                        $sql = "update cestas set precioTotal = (precioTotal + ('$precio' * '$cantidad')) where idCesta = '$idCesta'";
                        $conexion->query($sql);
                        $mensaje = "Producto añadido con éxito";
                    }
                }
            }
        }
    }
}
