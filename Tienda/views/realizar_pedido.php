<?php
require "../util/db_tienda.php";
session_start();
$usuario = $_SESSION["usuario"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "realizarPedido") {
        $sql = "SELECT idCesta from cestas where usuario = '$usuario'";
        $idCesta = $conexion->query($sql)->fetch_assoc()["idCesta"];
        $sql = "SELECT precioTotal from cestas where idCesta = '$idCesta'";
        $precioTotal = $conexion->query($sql)->fetch_assoc()["precioTotal"];

        $sql = "INSERT into pedidos (usuario, precioTotal) values ('$usuario', '$precioTotal')";
        $conexion->query($sql);

        $sql = "SELECT idPedido from pedidos where usuario = '$usuario' order by idPedido desc limit 1";
        $idPedido = $conexion->query($sql)->fetch_assoc()["idPedido"];

        $sql = "SELECT pc.idProducto, p.nombreProducto, p.precio, p.descripcion, pc.cantidad, p.imagen from productoscestas pc join productos p on pc.idProducto = p.idProducto where pc.idCesta = (select idCesta from cestas where usuario = '$usuario')";

        $sql = "SELECT pc.idProducto, pc.cantidad, p.precio from productoscestas pc JOIN productos p ON pc.idProducto = p.idProducto where pc.idCesta = '$idCesta'";
        $resultado = $conexion->query($sql);
        $pedido = 1;
        while ($fila = $resultado->fetch_assoc()) {
            $idProducto = $fila["idProducto"];
            $cantidad = $fila["cantidad"];
            $precio = $fila["precio"];
            $sql = "INSERT into lineaspedidos values ('$pedido','$idProducto', '$idPedido', '$precio', '$cantidad')";
            $conexion->query($sql);
            $pedido++;
        }

        $sql = "DELETE from productoscestas where idCesta = '$idCesta'";
        $conexion->query($sql);
        $sql = "UPDATE cestas set precioTotal = 0 where idCesta = '$idCesta'";
        $conexion->query($sql);
        header("Location: cesta.php");
    }
}
