<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Cesta</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Mi CSS -->
    <link rel="stylesheet" href="styles/style.css" />

    <!-- Logo Pagina -->
    <link rel="shortcut icon" href="images/logo_tn.png" />

    <!-- PHP links -->
    <?php require '../util/db_tienda.php'; ?>
    <!-- Mi JS link -->
    <script src="scripts/ampliarImg.js"></script>
</head>

<body>
    <?php
    session_start();

    if (isset($_SESSION["usuario"]) && isset($_SESSION["rol"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        $_SESSION["usuario"] = "invitado";
        $_SESSION["rol"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    }

    if ($usuario != "invitado") {
        require "funciones/eliminar_productocesta.php";
        require "funciones/realizar_pedido.php";
    }
    ?>

    <!-- Encabezado -->
    <?php
    require "header.php";
    ?>

    <!-- Panel admin -->
    <?php
    if ($rol == "admin") {
        require "sidebar.php";
    }

    if ($rol == "invitado") {
        header("Location: login.php");
    }

    // Mostrar productos en la cesta
    $sql = "SELECT pc.idProducto, p.nombreProducto, p.precio, p.descripcion, pc.cantidad, p.imagen FROM productoscestas pc JOIN productos p ON pc.idProducto = p.idProducto WHERE pc.idCesta = (SELECT idCesta FROM cestas WHERE usuario = '$usuario')";
    $resultado = $conexion->query($sql);
    $sql = "select precioTotal from cestas where usuario = '$usuario'";
    $precioTotal = $conexion->query($sql)->fetch_assoc()["precioTotal"];
    $productosCesta = [];
    while ($fila = $resultado->fetch_assoc()) {
        $nuevo_productoCesta = new Producto(
            $fila["idProducto"],
            $fila["nombreProducto"],
            $fila["precio"],
            $fila["descripcion"],
            $fila["cantidad"],
            $fila["imagen"]
        );
        array_push($productosCesta, $nuevo_productoCesta);
    }
    ?>
    <!-- Contenido de la página -->
    <h2 class="mb-5 text-center">Productos en la cesta</h2>
    <?php if (isset($mensaje)) {
    ?>
        <div class="container alert <?php if ($correcto) {
                                        echo "alert-success";
                                    } else {
                                        echo "alert-danger";
                                    }   ?> alert-dismissible fade show" role="alert">
            <?php echo $mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
    }
    ?>
    <div class="container text-center">
        <?php
        // comprobar si hay productos en la cesta
        if (count($productosCesta) == 0) {
            echo "<h4>No hay productos en la cesta actualmente</h4>";
        } else {
        ?>
            <table class="table table-striped table-hover table-bordered" data-bs-theme="dark">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Unidades</th>
                        <th>Precio unitario</th>
                        <th>Precio total</th>
                        <th></th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($productosCesta as $producto) {
                    ?>
                        <tr class="bg-dark">
                            <td><?php echo $producto->nombreProducto ?></td>
                            <td><?php echo $producto->descripcion ?></td>
                            <td><?php echo $producto->cantidad ?></td>
                            <td><?php echo $producto->precio ?>€</td>
                            <td><?php echo $producto->precio * $producto->cantidad ?>€</td>
                            <td><img class="ampliarImg fotoTabla" src="<?php echo $producto->imagen ?>" alt="Foto"></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="action" value="eliminarProductoCesta">
                                    <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                    <button class="btn text-danger" type="submit">
                                        <i class="bi bi-x fs-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="container bg-dark w-35 float-end text-light">
                <div class="row row-cols-2 h-100 p-2">
                    <p class="text-start fs-5 my-auto">Precio total: <?php echo $precioTotal ?>€</p>
                    <form class="text-end my-auto" action="" method="post">
                        <input type="hidden" name="action" value="realizarPedido">
                        <input class="btn btn-success" type="submit" value="Realizar pedido">
                    </form>
                </div>
            </div>
    </div>
    </div>
<?php
        }
?>
</div>
<!-- Jquery  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>