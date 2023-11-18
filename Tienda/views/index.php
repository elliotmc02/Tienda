<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Mi tienda</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Mi CSS -->
    <link rel="stylesheet" href="styles/style.css" />

    <!-- Logo Pagina -->
    <link rel="shortcut icon" href="imagenes/logo.png" />

    <!-- PHP links -->
    <?php require '../util/db_tienda.php'; ?>
    <?php require 'Objetos/producto.php'; ?>
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
        $_SESSION["rol"] = "cliente";
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    }

    // comprobar si el usuario ha sido eliminado
    $res = mysqli_query($conexion, "select usuario from usuarios where usuario = '$usuario'");
    if (mysqli_num_rows($res) == 0) {
        session_destroy();
    }

    $sql = "SELECT * from productos";
    $resultado = $conexion->query($sql);
    $productos = [];

    while ($fila = $resultado->fetch_assoc()) {
        $nuevo_producto = new Producto(
            $fila["idProducto"],
            $fila["nombreProducto"],
            $fila["precio"],
            $fila["descripcion"],
            $fila["cantidad"],
            $fila["imagen"]
        );
        array_push($productos, $nuevo_producto);
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
    ?>

    <!-- Contenido de la página -->
    <h2 class="mb-5 text-center">Lista de Productos</h2>
    <div class="container text-center cajaProductos">
        <?php
        if (count($productos) == 0) {
            echo "<h4>No hay productos actualmente</h4>";
        } else {
        ?>
            <div class="row row-cols-5 justify-content-center">
                <?php
                foreach ($productos as $producto) {
                ?>
                    <div class="col m-2">
                        <div class="card bg-dark" data-bs-theme="dark">
                            <img class="card-img-top ampliarImg" src="<?php echo $producto->imagen ?>" alt="Foto">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $producto->nombreProducto ?></h5>
                                <p class="card-text"><?php echo $producto->descripcion ?></p>
                                <p class="card-text"><?php echo $producto->precio ?>€</p>
                                <?php
                                if ($usuario != "invitado") {
                                ?>
                                    <form action="anadir_producto.php">
                                        <input type="hidden" name="action" value="<?php echo $producto->idProducto ?>">
                                        <input class="btn btn-outline-primary" type="submit" value="Añadir a la cesta">
                                    </form>
                                <?php
                                }
                                if ($rol == "admin") {
                                ?>
                                    <div class="row row-cols-2 mt-4">
                                        <form action="modificar_producto.php" method="post">
                                            <input type="hidden" name="action" value="<?php echo $producto->idProducto ?>">
                                            <input class="btn btn-outline-warning" type="submit" value="Modificar">
                                        </form>
                                        <form action="eliminar_producto.php" method="post">
                                            <input type="hidden" name="action" value="<?php echo $producto->idProducto ?>">
                                            <input class="btn btn-outline-danger" type="submit" value="Eliminar">
                                        </form>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        <?php
        }
        ?>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Jquery  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>