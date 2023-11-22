<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "realizarPedido") {

        // seleccionamos idCesta
        $sql = "SELECT idCesta from cestas where usuario = '$usuario'";
        $idCesta = $conexion->query($sql)->fetch_assoc()["idCesta"];
        // seleccionamos preciototal
        $sql = "SELECT precioTotal from cestas where idCesta = '$idCesta'";
        $precioTotal = $conexion->query($sql)->fetch_assoc()["precioTotal"];
        // insertamos en pedidos
        $sql = "INSERT into pedidos (usuario, precioTotal) values ('$usuario', '$precioTotal')";
        $conexion->query($sql);
        // seleccionamos idPedido
        $sql = "SELECT idPedido from pedidos where usuario = '$usuario' order by idPedido desc limit 1";
        $idPedido = $conexion->query($sql)->fetch_assoc()["idPedido"];
        // seleccionamos idProducto, cantidad y precio de productoscestas y productos
        $sql = "SELECT pc.idProducto, pc.cantidad, p.precio from productoscestas pc JOIN productos p ON pc.idProducto = p.idProducto where pc.idCesta = '$idCesta'";
        $resultado = $conexion->query($sql);
        $pedido = 1;
        // cada vuelta inserto en lineaspedidos los valores
        while ($fila = $resultado->fetch_assoc()) {
            $idProducto = $fila["idProducto"];
            $cantidad = $fila["cantidad"];
            $precio = $fila["precio"];
            $sql = "INSERT into lineaspedidos values ('$pedido','$idProducto', '$idPedido', '$precio', '$cantidad')";
            $conexion->query($sql);
            $pedido++;
        }
        // eliminamos productoscestas
        $sql = "DELETE from productoscestas where idCesta = '$idCesta'";
        $conexion->query($sql);
        // actualizamos precio total a 0
        $sql = "UPDATE cestas set precioTotal = 0 where idCesta = '$idCesta'";
        $conexion->query($sql);
        $mensaje = "Pedido realizado correctamente";
        $correcto = true;
    }
}
