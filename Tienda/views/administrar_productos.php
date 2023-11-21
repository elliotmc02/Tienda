<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>GPU Galaxy</title>

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
    <?php require "objetos/producto.php"; ?>
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

    // Añádir producto al stock
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST["action"] == "anadirStock") {
            $idProducto = $_POST["idProducto"];
            $sql = "UPDATE productos SET cantidad = cantidad + 1 WHERE idProducto = '$idProducto'";
            $conexion->query($sql);
        }
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
    $sql = "SELECT * FROM productos";
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
    <!-- Contenido de la página -->
    <h2 class="mb-5 text-center">Administrar Productos</h2>
    <div class="container text-center">
        <?php
        if (count($productos) == 0) {
            echo "<h4>No se encontraron productos</h4>";
        } else {
        ?>
            <table class="table table-striped table-hover table-bordered" data-bs-theme="dark">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Unidades</th>
                        <th>Precio</th>
                        <th></th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($productos as $producto) {
                    ?>
                        <tr class="bg-dark">
                            <td><?php echo $producto->idProducto ?></td>
                            <td><?php echo $producto->nombreProducto ?></td>
                            <td><?php echo $producto->descripcion ?></td>
                            <td><?php echo $producto->cantidad ?></td>
                            <td><?php echo $producto->precio ?>€</td>
                            <td><img class="ampliarImg fotoTabla" src="<?php echo $producto->imagen ?>" alt="Foto"></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="action" value="anadirStock">
                                    <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                    <button class="btn text-success" type="submit">
                                        <i class="bi bi-plus-lg fs-5"></i>
                                    </button>
                                </form>
                                <form action="modificar_producto.php" method="get">
                                    <input type="hidden" name="action" value="modificarProducto">
                                    <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                    <input type="hidden" name="nombreProducto" value="<?php echo $producto->nombreProducto ?>">
                                    <input type="hidden" name="descripcion" value="<?php echo $producto->descripcion ?>">
                                    <input type="hidden" name="cantidad" value="<?php echo $producto->cantidad ?>">
                                    <input type="hidden" name="precio" value="<?php echo $producto->precio ?>">
                                    <button class="btn text-warning" type="submit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                </form>
                                <?php
                                if ($rol == "admin") {
                                    $modalId = 'exampleModal' . $producto->idProducto;
                                }
                                ?>
                                <form action="eliminar_producto.php" method="post">
                                    <input type="hidden" name="action" value="eliminarProducto">
                                    <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                    <button class="btn text-danger" type="button" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <i class="bi bi-exclamation-octagon-fill fs-3 text-danger"></i>
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
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
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