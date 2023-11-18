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
    <?php require 'objetos/producto.php'; ?>

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
        header("Location: login.php");
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

    if ($usuario == "invitado") {
        header("Location: login.php");
    } else {

    ?>
        <!-- Encabezado -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                    <i class="bi bi-list"></i>
                </a>
                <a class="navbar-brand" href="./">Mi tienda</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Productos</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </ul>
                    <div class="nav-item dropdown">
                        <a class="nav-link navbar-brand" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-cart3"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Productos</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        </ul>
                    </div>
                    <div class="nav-item dropdown">
                        <a class="nav-link navbar-brand" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Ajustes</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Panel lateral -->
        <div class="offcanvas offcanvas-start bg-dark" data-bs-theme="dark" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div>
                    Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                </div>
                <div class="dropdown mt-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Dropdown button
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contenido de la página -->
        <main>
            <div class="container mt-4">
                <h4 class="card-title">Listado de productos</h4>
                <div class="table-responsive">
                    <table class="table table-bordered text-white table-dark table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th></th>
                                <th></th>
                                <?php
                                if ($rol == "admin") { ?>
                                    <th>Acciones</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($productos as $producto) {
                                echo "<tr>";
                                echo "<td>" . $producto->nombreProducto . "</td>";
                                echo "<td>" . $producto->descripcion . "</td>";
                                echo "<td>" . $producto->precio . " €</td>";
                                echo "<td>" . $producto->cantidad . "</td>";
                            ?>
                                <td><img class="ampliarImg" src="<?php echo $producto->imagen ?>"></td>
                                <td>
                                    <form action="anadir_producto.php">
                                        <input type="hidden" name="action" value="<?php echo $producto->idProducto ?>">
                                        <input class="btn btn-outline-primary" type="submit" value="Añadir a la cesta">
                                    </form>
                                </td>
                                <?php
                                if ($rol == "admin") {
                                ?>
                                    <td>
                                        <form class="mb-3" action="modificar_producto.php" method="post">
                                            <input type="hidden" name="action" value="<?php echo $producto->idProducto ?>">
                                            <input class="btn btn-outline-warning" type="submit" value="Modificar">
                                        </form>
                                        <form action="eliminar_producto.php" method="post">
                                            <input type="hidden" name="action" value="<?php echo $producto->idProducto ?>">
                                            <input class="btn btn-outline-danger" type="submit" value="Eliminar">
                                        </form>
                                    </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

    <?php
    }
    ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Jquery  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Otro JS -->
    <script src="scripts/off-canvas.js"></script>
    <script src="scripts/hoverable-collapse.js"></script>
    <script src="scripts/misc.js"></script>
    <script src="scripts/settings.js"></script>
    <script src="scripts/dashboard.js"></script>
</body>

</html>