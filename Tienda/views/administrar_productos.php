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
?>
<?php
if ($rol == "admin") {
    $modalId = 'exampleModal' . $producto->idProducto;
}
?>
<form action="eliminar_producto.php" method="post">
    <input type="hidden" name="eliminarProducto" value="<?php echo $producto->idProducto ?>">
    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">
        Eliminar
    </button>
</form>
<div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
                <h5>Estás apunto de cometer una acción irreversible</h5>
                Estás seguro de que quieres eliminar este producto?
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <input class="btn btn-danger" type="submit" value="Eliminar">
            </div>
        </div>
    </div>
</div>