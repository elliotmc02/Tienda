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
    <link rel="shortcut icon" href="images/logo.png" />

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
    <div class="container text-center">
        <?php
        if (count($productos) == 0) {
            echo "<h4>No hay productos actualmente</h4>";
        } else {
        ?>
            <div class="row row-cols-4 justify-content-center">
                <?php
                foreach ($productos as $producto) {
                ?>
                    <div class="col m-2">
                        <div class="card h-100 bg-dark" data-bs-theme="dark">
                            <img class="card-img-top ampliarImg" src="<?php echo $producto->imagen ?>" alt="Foto">
                            <div class="card-header h-10">
                                <h5 class="card-title"><?php echo $producto->nombreProducto ?></h5>
                            </div>
                            <div class="card-body h-10 fs-6 overflow-auto">
                                <p class="card-text text-start"><?php echo $producto->descripcion ?></p>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-start fs-5">En stock: <?php echo $producto->cantidad ?> unidades</p>
                            </div>
                            <div class="card-footer row row-cols-2">
                                <p class="card-text text-success text-start fs-4"><?php echo $producto->precio ?>€</p>
                                <form action="anadir_producto.php" method="post">
                                    <input type="hidden" name="anadirProducto" value="<?php echo $producto->idProducto ?>">
                                    <select name="cantidad">
                                        <?php
                                        $maxCantidad = min(5, $producto->cantidad);
                                        for ($i = 1; $i <= $maxCantidad; $i++) {
                                        ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    if ($usuario == "invitado") {
                                    ?>
                                        <button class="btn btn-success" type="submit" disabled><i class="bi bi-cart-plus-fill fs-3"></i></button>
                                    <?php
                                    } else {
                                    ?>
                                        <button class="btn btn-success" type="submit"><i class="bi bi-cart-plus-fill fs-3"></i></button>
                                    <?php
                                    }
                                    ?>
                                </form>
                            </div>
                            <div class="card-footer">
                                <?php
                                if ($rol == "admin") {
                                    $modalId = 'exampleModal' . $producto->idProducto;
                                ?>
                                    <div class="row row-cols-2">
                                        <form action="modificar_producto.php" method="post">
                                            <input type="hidden" name="modificarProducto" value="<?php echo $producto->idProducto ?>">
                                            <input class="btn btn-outline-warning" type="submit" value="Modificar">
                                        </form>
                                        <form action="eliminar_producto.php" method="post">
                                            <input type="hidden" name="eliminarProducto" value="<?php echo $producto->idProducto ?>">
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">
                                                Eliminar
                                            </button>
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